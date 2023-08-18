@extends('layouts.bookapp')

@section('title')
    推薦文投稿完了
@endsection

@section('content')
    <h2>推薦文投稿完了</h2>
    <p>推薦文が投稿できました！</p>

    <x-book-recommendation-card :recommendation="$recommendation" footer="auth" />

    <div class="row">
        <div class="col-sm-4 d-grid mb-1">
            <a class="btn btn-info" href="{{ route('recommendation.search') }}" role="button">違う本の推薦文を書く</a>
        </div>
        <div class="col-sm-4 d-grid mb-1">
            <a class="btn btn-success" href="{{ route('book.show', ['book' => $recommendation->book->id]) }}"
                role="button">他の人の推薦文を見る</a>
        </div>
        <div class="col-sm-4 d-grid mb-1">
            <a class="btn btn-outline-secondary" href="{{ url('/') }}" role="button">トップページに戻る</a>
        </div>
    </div>
@endsection
