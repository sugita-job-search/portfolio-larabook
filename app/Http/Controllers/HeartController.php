<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Heart;

class HeartController extends Controller
{
    public function index()
    {
        $recommendations = Auth::user()
            ->recommendationsThroughHearts()
            ->cards(false)
            ->withUser()
            ->withBook(true)
            ->orderByPivot('created_at', 'desc')
            ->paginate(5);

        if($recommendations->isEmpty()) {
            // 最終ページ以上のページがリクエストされたときは最終ページにリダイレクト
            $last_page = $recommendations->lastPage();
            if($recommendations->currentPage() > $last_page) {
                return redirect($recommendations->url($last_page));
            }
        } else {
            // ハートボタンの状態を決定するためのプロパティ追加
            foreach($recommendations as $recommendation) {
                $recommendation->hearts_exists = true;
            }
        }

        
        return view('heart.index', compact('recommendations'));
    }

    /**
     * ハート登録
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Heart::$rules);

        Auth::user()->hearts()->firstOrCreate($validated);

        $count = Heart::where('recommendation_id', $validated['recommendation_id'])->count();

        return response()->json(['count' => $count]);
    }

    /**
     * ハート削除
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate(Heart::$rules);
        Auth::user()->hearts()->where('recommendation_id', $validated['recommendation_id'])->delete();
        
        $count = Heart::where('recommendation_id', $validated['recommendation_id'])->count();

        return response()->json(['count' => $count]);
    }
}
