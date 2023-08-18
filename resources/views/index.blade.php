@extends('layouts.bookapp')

@section('title')
    LaraBook
@endsection

@section('top')
    @parent
    <div class="row">
        <div class="col-lg-9">
            <h2>みんなの推薦文</h2>
            <div class="row row-cols-auto justify-content-end">
                <div class="col">
                    <p>
                        表示中のジャンル：
                    </p>
                </div>
                <ul class="col genre-list">
                    <div class="row row-cols-auto justify-content-end">
                        @foreach ($genres as $g)
                            <div class="col">
                                <li>{{ $g }}</li>
                            </div>
                        @endforeach
                    </div>
                </ul>
                <div class="col">
                    <a href="{{ $genre_change_url }}">変更</a>
                </div>
            </div>

            <div class="row row-cols-auto justify-content-end mt-2">
                <div class="col">
                    <label for="sort-select" class="float-end mt-1">表示順序：</label>
                </div>
                <div class="col">
                    <select name="sort" id="sort-select" class="form-select form-select-sm white-input">
                        <option value="{{ $latest_sort_url }}">新着順</option>
                        <option value="{{ $heart_sort_url }}" @if ($sort == 'heart') selected @endif>参考にした人が多い順
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @auth
        @forelse ($recommendations as $recommendation)
            <x-top-recommendation-card :recommendation="$recommendation" />
        @empty
            <x-empty-message>
                <div class="mb-3">
                    このジャンルの本の推薦文はまだ投稿されていません
                </div>
                <a href="{{ route('recommendation.search') }}" class="alert-link">推薦文を投稿</a>
            </x-empty-message>
        @endforelse
    @else
        @forelse ($recommendations as $recommendation)
            <x-book-recommendation-card :recommendation="$recommendation">
                <x-slot:heart_slot>
                    <x-heart-login-button :recommendation="$recommendation" />
                </x-slot>
            </x-book-recommendation-card>
        @empty
            <x-empty-message>
                <div class="mb-3">
                    このジャンルの本の推薦文はまだ投稿されていません
                </div>
                <a href="{{ route('recommendation.search') }}" class="alert-link">ログインして推薦文を投稿</a>
            </x-empty-message>
        @endforelse
    @endauth

    {{ $recommendations->links() }}
@endsection
