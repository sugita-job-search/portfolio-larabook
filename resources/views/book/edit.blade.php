@extends('layouts.book-form')

@section('title')
    本の情報変更
@endsection

@section('description')
    新しい情報を入力してください
@endsection

@section('form')
    @parent
    @method('PATCH')
@endsection

@section('action', route('book.update', $book))

@if ($book->image != App\Models\Book::NO_IMAGE)
    @section('old-image')
        <div class="row mb-3">
            <div class="col-sm-3">
                <div class="form-text">現在の画像</div>
                <img src="{{ asset($book->image) }}" alt="" class="img-fluid">
            </div>
        </div>
    @endsection
@endif

@section('next', '完了')

@section('back', route('book.show', $book))
