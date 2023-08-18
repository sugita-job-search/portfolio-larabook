@extends('layouts.bookapp')

@section('title')
    推薦文投稿
@endsection

@section('content')
    <h2>推薦文を書く本の検索</h2>
    <p>推薦文を書く本を検索してください<br>
        タイトル、著者、シリーズ名、ISBNから検索できます
    </p>

    <form action="{{ route('search') }}" method="get" class="my-5">
        <div class="row">
            <div class="col-md-9 mb-3">
                <input type="text" name="{{ App\Common\UrlParameter::ALL }}" class="form-control white-input" id="recommend-search"
                    aria-label="検索">
            </div>
        </div>
        <div class="d-grid gap-2 d-md-block">
            <button type="submit" class="btn btn-primary mx-2">検索</button>
            <a class="btn btn-outline-secondary mx-2" href="{{ url('/') }}" role="button">トップページに戻る</a>
        </div>
    </form>
    <div>
        新しい本を登録する方はこちら：
        <a href="{{ route('isbn') }}">本を登録</a>
    </div>
@endsection
