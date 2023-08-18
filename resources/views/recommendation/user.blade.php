@extends('layouts.bookapp')

@section('title')
    {{ $user_name }}さんの推薦文
@endsection

@section('content')
    <h2>{{ $user_name }}さんの推薦文</h2>

    @auth
        @forelse ($recommendations as $recommendation)
            <x-top-recommendation-card :recommendation="$recommendation" footer="name" :name="$user_name" />
        @empty
            <x-empty-message>
                推薦文はまだありません
            </x-empty-message>
        @endforelse
    @else
        @forelse ($recommendations as $recommendation)
            <x-book-recommendation-card :recommendation="$recommendation" footer="name" :name="$user_name" >
                <x-slot:heart_slot>
                    <x-heart-login-button :recommendation="$recommendation" />
                </x-slot>
            </x-book-recommendation-card>
        @empty
            <x-empty-message>
                推薦文はまだありません
            </x-empty-message>
        @endforelse
    @endauth

    <div class="float-end">
        <a class="btn btn-outline-secondary" href="{{ url('/') }}" role="button">トップページに戻る</a>
    </div>

    {{ $recommendations->links() }}
@endsection
