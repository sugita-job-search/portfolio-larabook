@extends('layouts.bookapp')

@section('title')
削除確認
@endsection

@section('content')
    <h2>読みたい本削除確認</h2>
    <p>以下の本を読みたい本から削除します</p>

    <x-book-card :book="$book" :footer="null" />
    <form action="{{route('want-to-read.destroy')}}" method="post">
        @csrf
        <input type="hidden" name="book_id" value="{{ $book->id }}">
        <div class="d-grid gap-2 d-md-block">
            <button type="submit" class="btn btn-secondary mx-2">削除</button>
            <a class="btn btn-outline-secondary mx-2" href="{{ url()->previous(route('want-to-read.index')) }}"
                role="button">戻る</a>
        </div>
    </form>
@endsection
