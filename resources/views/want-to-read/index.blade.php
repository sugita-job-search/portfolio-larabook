@extends('layouts.bookapp')

@section('title')
    読みたい本
@endsection

@section('content')
    <h2>あなたの読みたい本</h2>

    @forelse ($want_to_read_books as $want_to_read_book)
        <x-book-card :book="$want_to_read_book" footer="button" button="delete" />

    @empty
        <div class="alert alert-dark mt-5" role="alert">
            <div class="my-3">
                読みたい本はまだありません
            </div>
        </div>
    @endforelse

    <div class="float-end">
        <a class="btn btn-outline-secondary" href="{{ url('/') }}" role="button">トップページに戻る</a>
    </div>

    {{ $want_to_read_books->links() }}
@endsection
