<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WantToReadBook;

class WantToReadBookController extends Controller
{
    /**
     * 読みたい本一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $want_to_read_books = Auth::user()
            ->books()
            ->orderByPivot('created_at', 'desc')
            ->paginate(5);

        return view('want-to-read.index', compact('want_to_read_books'));
    }

    /**
     * 読みたい本に登録
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(WantToReadBook::$rules);

        $want = Auth::user()->wantToReadBooks()->firstOrCreate($validated);

        // return back();

        return response()->json(['book_id' => $want->book_id]);
    }

    /**
     * 読みたい本削除確認
     *
     * @param string|int $book_id
     * @return \Illuminate\Http\Response
     */
    public function delete($book_id)
    {
        $want_to_read_book = Auth::user()
            ->books()
            ->findOrFail($book_id);
        
        return view('want-to-read.delete', ['book' => $want_to_read_book]);
    }

    /**
     * 読みたい本を削除
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate(WantToReadBook::$rules);
        
        //読みたい本取得
        $want_to_read_book = Auth::user()
            ->wantToReadBooks()
            ->where('book_id', $validated['book_id'])
            ->firstOrFail();

        //削除
        $want_to_read_book->delete();

        //読みたい本一覧にリダイレクト
        return redirect(route('want-to-read.index'));
    }
}