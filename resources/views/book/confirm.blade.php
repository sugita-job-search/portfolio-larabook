@extends('layouts.bookapp')

@section('title')
    登録内容確認
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-sm-9">
            <h2>登録内容確認</h2>
            <p>以下の内容で登録します</p>

            <form action="{{ route('book.store') }}" method="post" class="my-3">
                @csrf
                <div class="mb-1">
                    <label for="title" class="form-label">タイトル</label>
                    <input type="text" name="title" readonly class="form-control-plaintext" id="title"
                        value="{{ $inputs['title'] }}" />
                </div>
                <div class="mb-1">
                    <label for="author" class="form-label">著者</label>
                    <textarea readonly name="author" class="form-control-plaintext" id="author" rows="3">{{ $inputs['author'] }}</textarea>
                </div>
                <div class="mb-1">
                    <label for="publisher" class="form-label">出版社</label>
                    <input type="text" name="publisher" readonly class="form-control-plaintext" id="publisher"
                        value="{{ $inputs['publisher'] }}" />
                </div>
                <div class="mb-1">
                    <label for="series" class="form-label">出版年月</label>
                    <input type="text" name="year-month" readonly class="form-control-plaintext" id="year-month"
                        value="{{ $inputs['year'] }}年 {{ $inputs['month'] }}月" />
                    <input type="hidden" name="year" value="{{ $inputs['year'] }}">
                    <input type="hidden" name="month" value="{{ $inputs['month'] }}">
                </div>
                @if ($inputs['series_title'] !== null)
                    <div class="mb-1">
                        <label for="series" class="form-label">シリーズ名</label>
                        <input type="text" name="series_title" readonly class="form-control-plaintext" id="series"
                            value="{{ $inputs['series_title'] }}" />
                    </div>
                @endif
                <div class="mb-1">
                    <label for="genre" class="form-label">ジャンル</label>
                    <input type="hidden" name="genre_id" value="{{ $inputs['genre_id'] }}">
                    <input type="text" name="genre" readonly class="form-control-plaintext" id="genre"
                        value="{{ App\Models\Genre::getGenreById($inputs['genre_id']) }}" />
                </div>
                <div class="mb-1">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" name="isbn" readonly class="form-control-plaintext" id="isbn"
                        value="{{ session('isbn_input') }}" />
                </div>
                @if ($existsImage)
                    <div class="mb-1">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-label">表紙の画像</div>
                                <img src="{{ route('image') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                @endif
                <div class="d-grid gap-2 d-md-block mt-3">
                    <button type="submit" class="btn btn-primary mx-2" name="submit" value="1">登録</button>
                    <button type="submit" class="btn btn-secondary mx-2" name="submit" value="0">前の画面に戻る</button>
                    <button type="submit" class="btn btn-secondary mx-2" name="submit" value="-1">ISBN入力に戻る</button>
                </div>
            </form>
        </div>
    </div>
@endsection
