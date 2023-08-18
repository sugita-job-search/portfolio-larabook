@extends('layouts.bookapp')

@section('title')
    登録エラー
@endsection

@section('content')
    <h2>登録エラー</h2>
    <p>この本はすでに登録されています</p>

    <x-book-card :book="$book" footer="button" />

    <div class="d-grid gap-2 d-md-block mt-3">
        <a class="btn btn-secondary mx-2" href="{{ route('isbn') }}" role="button">戻る</a>
        <a class="btn btn-outline-secondary mx-2" href="{{ url('/') }}" role="button">トップページに戻る</a>
    </div>
@endsection
