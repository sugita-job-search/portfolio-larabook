<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Common\Common;
use App\Common\Session;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\Recommendation;
use App\Rules\Isbn;
use Illuminate\Database\Eloquent\Builder;

class BookController extends Controller
{
    /**
     * isbn入力ページを表示
     *
     * @return \Illuminate\Http\Response
     */
    public function isbn()
    {
        if (session()->has(Session::ISBN_INPUT)) {
            $old = session(Session::ISBN_INPUT);
        } else {
            $old = null;
        }

        //セッションに入力値があったら保持
        self::keepInput();
        return view('book.isbn', compact('old'));
    }

    /**
     * isbnのバリデーション
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function isbnPost(Request $request)
    {
        $input = $request->validate(['isbn' => ['required', 'string', new Isbn()]]);

        //ハイフンを削除
        $isbn = str_replace(['-', 'ー'], '', $input['isbn']);

        //10桁のとき13桁に変換
        if (strlen($isbn) == 10) {
            $isbn = Common::convertIsbn10To13($isbn);
        }

        //本が登録済みのときidを取得
        $id = Book::getBookIdByIsbn($isbn);

        //登録済みの本でないときはセッションにisbnを保存して次の画面に進む
        if (!$id) {
            //セッションに入力値があったら保持
            self::keepInput();
            return redirect(route('book.create'))
                ->with([Session::ISBN13 => $isbn, Session::ISBN_INPUT => $input['isbn']]);
        }

        //登録済みの本のときは重複登録防止用のページにリダイレクト
        return redirect(route('duplicate', ['book' => $id]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //isbnが入力されていないときはリダイレクト
        $redirect = $this->checkIsbn();
        if ($redirect != null) {
            return $redirect;
        }

        //セッションに保存されているisbnを保持
        self::keepIsbn();

        //セッションに以前の入力値があるときはそれを表示
        $book = new Book();
        if (session()->has(Session::BOOK_CREATE_INPUT)) {
            $book->fill(session(Session::BOOK_CREATE_INPUT));
        }

        return view('book.create', compact('book'));
    }

    /**
     * 確認ページを表示
     *
     * @param　App\Http\Request\BookRequest $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(BookRequest $request)
    {
        //isbnが入力されていないときはリダイレクト
        $redirect = $this->checkIsbn();
        if ($redirect != null) {
            return $redirect;
        }

        //セッションに保存されているisbnを保持
        self::keepIsbn();

        //書影がアップロードされたときファイルとmimeタイプをセッションに保存
        $existsImage = false;
        if ($request->hasFile('image')) {
            session()->flash(Session::IMAGE, $request->image->get());
            session()->flash(Session::MIME, $request->image->getMimeType());
            session()->flash(Session::IMAGE_NAME, $request->image->hashName());
            $existsImage = true;
        }

        $inputs = $request->validated();

        return view('book.confirm', compact('inputs', 'existsImage'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        //isbnが入力されていないときはリダイレクト
        $redirect = $this->checkIsbn();
        if ($redirect != null) {
            return $redirect;
        }

        if ($request->has('submit')) {
            //完了ボタンが押されたとき
            if ($request->submit == 1) {

                //登録済みの本だったときは重複登録防止用のページにリダイレクト
                $id = Book::getBookIdByIsbn(session(Session::ISBN13));
                if ($id != false) {
                    return redirect(route('duplicate', $id));
                }

                //未登録の本だったときはデータベースに登録
                $model = new Book();
                $model->fill($request->validated());

                //書影がアップロードされたとき保存
                if (session()->has(Session::IMAGE)) {
                    $isPut = Storage::put('public/' . session(Session::IMAGE_NAME), session(Session::IMAGE));

                    if (!$isPut) {
                        return redirect(route('book.create'))
                            ->withInput()
                            ->withErrors(['image' => 'ファイルのアップロードに失敗しました']);
                    }

                    $model->image = session(Session::IMAGE_NAME);
                }

                $model->isbn = session(Session::ISBN13);
                $model->save();

                //登録された本の情報を取得
                $book = Book::where('isbn', session(Session::ISBN13))->first();

                return view('book.complete', compact('book'));
            }

            //前の画面に戻るが押されたとき入力画面に戻る
            if ($request->submit == 0) {
                return redirect(route('book.create'))
                    ->withInput();
            }
        }

        //それ以外のときisbn入力に戻る
        $input = $request->validated();
        unset($input['image']);
        return redirect(route('isbn'))
            ->with(Session::BOOK_CREATE_INPUT, $input);
    }

    /**
     * 本の二重登録防止ページ表示
     *
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Book $book)
    {
        return view('book.error', compact('book'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //推薦文と投稿者とハート数取得、ログインしている場合はログインユーザーがハート済みかも取得
        $recommendations = $book
            ->recommendations()
            ->cards()
            ->withUser()
            ->latest()
            ->paginate(5);

        //ログインしていない場合はログイン後元のページが表示されるようにする
        // if (!Auth::check()) {
        //     Common::loginHere();
        // }

        return view('book.show', compact('book', 'recommendations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        return view('book.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, Book $book)
    {
        //現在の書影を取得
        $old_image = $book->image;

        //入力値をbookインスタンスに代入
        $book->fill($request->validated());

        //ファイルがアップロードされた場合は保存
        if ($request->hasFile('image')) {
            $new_image = $request->image->store('public');

            if ($new_image == false) {
                return redirect(route('book.edit', ['book' => $book->id]))
                    ->withInput()
                    ->withErrors(['image' => 'ファイルのアップロードに失敗しました']);
            }

            //前の書影があれば削除
            if ($old_image != Book::NO_IMAGE) {
                Storage::delete(str_replace('storage', 'public', $old_image));
            }

            //新しい書影の名前をbookインスタンスに代入
            $book->image = str_replace('public/', '', $new_image);
        };

        //データベースに保存
        $book->save();

        return redirect(route('book.show', ['book' => $book->id]));
    }

    /**
     * セッションにisbnが保存されていないときisbn入力画面へのリダイレクトインスタンスを返す
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function checkIsbn()
    {
        if (!session()->has(Session::ISBN13)) {
            return redirect(route('isbn'));
        }
    }

    /**
     * セッションに一時保存されているisbnを次のリクエストまで保存する
     *
     * @return void
     */
    public static function keepIsbn()
    {
        session()->keep([Session::ISBN13, Session::ISBN_INPUT]);
    }

    /**
     * セッションに一時保存されている入力値を次のリクエストまで保存する
     *
     * @return void
     */
    public static function keepInput()
    {
        session()->keep(Session::BOOK_CREATE_INPUT);
    }

    /**
     * 確認画面で書影を表示
     *
     * @return \Illuminate\Http\Response
     */
    public function image()
    {
        self::keepIsbn();
        session()->keep([Session::IMAGE, Session::MIME, Session::IMAGE_NAME]);
        return response(session(Session::IMAGE))
            ->header('Content-Type', session(Session::MIME));
    }
}
