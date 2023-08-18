<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Common\UrlParameter;
use App\Models\Book;
use App\Models\Merit;
use App\Models\Recommendation;
use App\Models\User;

class RecommendationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ログインユーザーの推薦文を取得、更新順に並べる
        $recommendations = Auth::user()
            ->cards()
            ->orderBy('updated_at', 'desc')
            ->paginate(5);

        // ログインユーザーが獲得したハートの総数取得
        $total_hearts = null;
        if (!$recommendations->isEmpty()) {
            $total_hearts = Auth::user()
                ->heartsThroughRecommendations()
                ->count();
        }

        return view('recommendation.index', compact('recommendations', 'total_hearts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $book = Book::findOrFail($request->query(UrlParameter::BOOK_ID));
        $old_merits = old('merits', []);
        return view('recommendation.create', compact('book', 'old_merits'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = Recommendation::storeRules();

        //完了ボタンが押されたとき1、戻るが押されたとき0が送信されている
        $rules['complete'] = ['required', 'boolean'];
        $input = $request->validate($rules);

        //完了ボタンが押されたとき
        if ($input['complete'] == 1) {
            // $input['user_id'] = Auth::id();
            // Recommendation::create($input);

            $recommendation = DB::transaction(function () use ($input) {
                //ログイン中のユーザーモデルとリレーションした推薦文モデルをデータベースに挿入
                $recommendation = Auth::user()
                    ->recommendations()
                    ->create($input);

                //挿入した推薦文モデルとリレーションした推薦文長所をデータベースに挿入
                if (!empty($input['merits'])) {
                    $recommendation->merits()
                        ->attach($input['merits']);
                }

                return $recommendation;
            });

            //完了ページ表示
            return view('recommendation.complete', compact('recommendation'));
        }

        //それ以外のときは前のページに戻る
        $url = route('recommendation.create', [UrlParameter::BOOK_ID => $input['book_id']]);
        $url .= '#form';
        return redirect($url)
            ->withInput();
    }

    /**
     * 確認ページ
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request)
    {
        $rules = Recommendation::confirmRules();
        $validator = validator($request->all(), $rules);
        if ($validator->fails()) {
            $url = url()->previous();
            $url .= '#form';
            return redirect($url)
                ->withInput()
                ->withErrors($validator);
        }
        $input = $validator->validated();

        // 画面に表示する本取得
        $book = Book::findOrFail($input['book_id']);

        // 長所が入力されているときは長所取得
        if (!empty($input['merits'])) {
            $merits = Merit::whereIn('id', $input['merits'])->pluck('merit');
        } else {
            $merits = [];
        }

        return view('recommendation.confirm', compact('input', 'book', 'merits'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Recommendation  $recommendation
     * @return \Illuminate\Http\Response
     */
    public function edit(Recommendation $recommendation)
    {
        $this->checkUserId($recommendation);
        $book = $recommendation->book;

        // バリデーションエラーで戻ってきたときは入力値、それ以外のときはデータベースに保存されている値表示
        $old_merits = old('merits');

        if ($old_merits === null) {
            $old_merits = $recommendation->getMerits();
        }
        return view('recommendation.edit', compact('recommendation', 'book', 'old_merits'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recommendation  $recommendation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recommendation $recommendation)
    {
        $this->checkUserId($recommendation);

        // バリデーション
        $rules = Recommendation::updateRules();

        $validator = validator($request->all(), $rules);
        if ($validator->fails()) {
            $url = url()->previous();
            $url .= '#form';
            // 長所が選択されなかったときはフィールドごと存在しないので空配列追加
            $request->mergeIfMissing(['merits' => []]);
            return redirect($url)
                ->withInput()
                ->withErrors($validator);
        }

        $input = $validator->validated();

        DB::transaction(function () use ($recommendation, $input) {

            // 推薦文モデルに送信されてきた推薦文代入
            if (isset($input['recommendation'])) {
                $recommendation->recommendation = $input['recommendation'];
            } else {
                $recommendation->recommendation = null;
            }

            // 内容に変化がなくても更新日時を現在にする
            $recommendation->updated_at = now();
            $recommendation->save();

            // 長所が選択されていないときは元あった長所削除、選択されているときはそれを保存、
            if (empty($input['merits'])) {
                $recommendation->merits()->detach();
            } else {
                $recommendation->merits()->sync($input['merits']);
            }
        });

        return redirect(route('recommendation.index'));
    }

    /**
     * 削除確認画面を表示
     * 
     * @param  \App\Models\Recommendation  $recommendation
     * @return \Illuminate\Http\Response
     */
    public function delete(Recommendation $recommendation)
    {
        $this->checkUserId($recommendation);

        // ハート数取得
        $recommendation->loadCount('hearts')
            ->loadExists(['hearts' => function (Builder $query) {
                $query->where('user_id', Auth::id());
            }]);

        //フッターにユーザー名表示
        $name = Auth::user()->name;
        return view('recommendation.delete', compact('recommendation', 'name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recommendation  $recommendation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recommendation $recommendation)
    {
        $this->checkUserId($recommendation);
        DB::transaction(function () use ($recommendation) {
            $recommendation->delete();
        });
        return redirect(route('recommendation.index'));
    }

    /**
     * 推薦文を投稿する本を検索する画面を表示
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        return view('recommendation.search');
    }


    /**
     * 特定のユーザーの推薦文一覧表示
     *
     * @param App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function user(User $user)
    {
        //表示するユーザーのニックネーム
        $user_name = $user->name;

        // 推薦文表示に必要な情報取得
        $recommendations = $user
            ->cards(Auth::check())
            ->latest()
            ->paginate(5);

        //ログインしていない場合はログイン後元のページが表示されるようにする
        // if (!$is_login) {
        //     Common::loginHere();
        // }
        return view('recommendation.user', compact('user_name', 'recommendations'));
    }

    /**
     * 推薦文の投稿者とログインユーザーが一致しない場合は404
     * 
     * @param \App\Models\Recommendation $recommendation
     * @return \Illuminate\Http\Response
     */
    public function checkUserId(Recommendation $recommendation)
    {
        if ($recommendation->user_id != Auth::id()) {
            abort(404);
        }
    }
}
