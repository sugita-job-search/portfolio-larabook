@extends('layouts.bookapp')

@section('title')
    登録完了
@endsection

@section('content')
    <div class="alert alert-info" role="alert">
        <h2 class="alert-heading">登録完了</h2>
        <p>
            新しい本が登録できました！<br>
            ぜひ推薦文を投稿してください！
        </p>
    </div>

    <x-book-information :book="$book" isbn="10" />

    <form action="{{ route('recommendation.create') }}" method="get" class="d-grid gap-2 d-md-block">
        <input type="hidden" name="{{ App\Common\UrlParameter::BOOK_ID }}" value="{{ $book->id }}">
        <button type="submit" class="btn btn-info mx-2">この本の推薦文を書く</button>
        <a class="btn btn-outline-secondary mx-2" href="{{ url('/') }}" role="button">トップページに戻る</a>
    </form>
@endsection
