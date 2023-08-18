<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Common\Common;
use App\Common\UrlParameter;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Recommendation;
use App\Rules\Isbn;

class TopController extends Controller
{
    /**
     * トップページ表示
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //ログインしているかチェック
        $is_login = Auth::check();

        //共通のクエリビルダインスタンス
        $builder = Recommendation::cards()
            ->withUser();

        // ソートがハート順になっているときはソートのためのクエリ追加
        $sort = $request->query(UrlParameter::SORT);
        if ($sort == UrlParameter::SORT_VALUES[0]) {
            $builder->orderBy('hearts_count', 'desc');
        }

        // 新着順に並べるためのクエリ追加
        $builder->latest();

        //ログインしているときは読みたい本に登録しているかもロード、それに伴って本もロード
        if ($is_login) {
            $builder->withBook(true);
        }

        //表示ジャンル初期値
        $genres = ['すべて'];

        //表示ジャンルが選択されているか調べる
        $validator = Validator::make(
            $request->query(),
            [
                'genres' => ['required', 'array'],
                'genres.*' => ['numeric', 'integer', 'min:0']
            ]
        );

        //表示ジャンルが選択されていないか整数以外が含まれるとき
        if ($validator->fails()) {
            //ログインユーザーが好きなジャンルを登録していればジャンル名取得
            if ($is_login) {
                if (Auth::user()->genre_id != null) {
                    $fetched = Genre::where('id', Auth::user()->genre_id)
                        ->value('genre');

                    $genre_ids = [Auth::user()->genre_id];
                    $genres = [$fetched];
                }
            }
            //すべて以外の表示ジャンルが選択されているときジャンル名取得
        } elseif (!in_array(0, $request->query('genres'))) {
            $ids = array_unique($request->query('genres'));
            $fetched = Genre::whereIn('id', $ids)
                ->pluck('genre');

            //ジャンル名が取得できればそのジャンルを表示
            if (!$fetched->isEmpty()) {
                $genre_ids = $ids;
                $genres = $fetched->all();
            }
        }

        //表示ジャンルがすべてでないとき指定ジャンルの本だけロード
        if ($genres != ['すべて']) {
            $builder->withWhereHas('book', function ($query) use ($genre_ids) {
                $query->select(Book::$withCards)
                    ->whereIn('genre_id', $genre_ids);
            });
        } elseif (!$is_login) {
            //表示ジャンルがすべてかつログインしていない場合は本のデータロードのための要素をここで追加
            $builder->withBook(false);
        }

        $recommendations = $builder
            ->paginate(5)
            ->withQueryString();

        //ログインしていない場合はログイン後現在のページが表示されるようにする
        if (!$is_login) {
            //表示ジャンルを選択しているとき
            if (!$validator->fails()) {
                Common::loginHere();
            } else {
                //表示ジャンルを選択しておらずかつクエリパラメータにページが含まれるとき
                if ($request->query('page') != null) {
                    $url = '/?genres[]=0&page=';
                    $url .= $request->query('page');
                    Common::loginHere($url);
                } else {
                    //ジャンル未選択かつページなしのときトップページへ
                    session()->forget('url.intended');
                }
            }
        }

        return view('index', compact('recommendations', 'genres', 'sort'));
    }

    /**
     * ジャンル選択ページを表示
     *
     * @return \Illuminate\Http\Response
     */
    public function genre()
    {
        $genres = Genre::getGenres();

        // ソートが指定されているページから遷移してきた場合はフォームから送信する内容にソートも含める
        $sort_parameter = request()->query(UrlParameter::SORT);

        if ($sort_parameter !== null && is_string($sort_parameter)) {
            $sort = $sort_parameter;
        } else {
            $sort = null;
        }

        //ログインしていない場合はログイン後元のページが表示されるようにする
        // if (!Auth::check()) {
        //     Common::loginHere();
        // }

        return view('genre', compact('genres', 'sort'));
    }

    /**
     * 検索結果画面を表示
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        //ログインしていない場合はログイン後元のページが表示されるようにする
        // if (!Auth::check()) {
        //     Common::loginHere();
        // }

        //入力値が文字列か調べる
        $validator = Validator::make(
            $request->query(),
            [
                UrlParameter::ALL => ['required_without_all:author,series', 'string'],
                UrlParameter::AUTHOR => ['required_without_all:all,series', 'string'],
                UrlParameter::SERIES => ['required_without_all:all,author', 'string'],
            ]
        );

        //入力値が文字列でないとき検索結果なし
        if ($validator->fails()) {
            return view('search', ['books' => [], 'search_word' => '']);
        }

        $validated = $validator->validated();

        $column = null;
        //検索項目が著者のとき
        if (
            isset($validated[UrlParameter::AUTHOR])
            && !isset($validated[UrlParameter::SERIES])
            && !isset($validated[UrlParameter::ALL])
        ) {
            $search_word = $validated[UrlParameter::AUTHOR];
            $column = 'author';
            //検索項目がシリーズ名のとき
        } elseif (
            isset($validated[UrlParameter::SERIES])
            && !isset($validated[UrlParameter::AUTHOR])
            && !isset($validated[UrlParameter::ALL])
        ) {
            $search_word = $validated[UrlParameter::SERIES];
            $column = 'series_title';
        }

        //検索項目が全てのとき
        if ($column == null) {
            $search_word = $validated[UrlParameter::ALL];
            //ワイルドカードをカードをエスケープ
            $escaped_word = addcslashes($search_word, '%_\\');
            $builder = Book::whereRaw('(title||author||IFNULL(series_title, "")) LIKE ? ESCAPE "\"', "%{$escaped_word}%");

            //入力値がisbnの可能性があるとき
            $string = mb_convert_kana($search_word, 'a');
            $string = str_replace(['-', 'ー'], '', $string);
            if (Isbn::isIsbn($string)) {
                //10桁なら13桁に変換
                if (strlen($string) == 10) {
                    $string = Common::convertIsbn10To13($string);
                }

                //isbnを検索対象にしたクエリを付け足す
                $builder->orWhere('isbn', $string);
            }

            //検索項目が全て以外のとき
        } else {
            //ワイルドカードをカードをエスケープ
            $escaped_word = addcslashes($search_word, '%_\\');
            $builder = Book::whereRaw($column . ' LIKE ? ESCAPE "\"', "%{$escaped_word}%");
        }

        // ログインしているときは読みたい本に登録しているか調べる
        if (Auth::check()) {
            $builder->with('loginWant');
        }

        $books = $builder
            ->select('id', 'title', 'author', 'publisher', 'year', 'month', 'series_title', 'image')
            ->paginate(5)
            ->withQueryString();

        return view('search', compact('books', 'search_word'));
    }
}
