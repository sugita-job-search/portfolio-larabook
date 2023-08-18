@extends('layouts.bookapp')

@section('title')
    削除確認
@endsection

@section('content')
    <h2>推薦文削除確認</h2>
    <p>以下の投稿を削除します</p>

    <x-book-recommendation-card :recommendation="$recommendation" footer="name" :name="$name">
        <x-slot:heart_slot>
            <x-heart-button :recommendation="$recommendation" disabled />
        </x-slot>
    </x-book-recommendation-card>

    <form action="{{ route('recommendation.destroy', ['recommendation' => $recommendation->id]) }}" method="post">
        <div class="d-grid gap-2 d-md-block">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-secondary mx-2">削除</button>
            <a class="btn btn-outline-secondary mx-2" href="{{ url()->previous(route('recommendation.index')) }}"
                role="button">戻る</a>
        </div>
    </form>
@endsection
