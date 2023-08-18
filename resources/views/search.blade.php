@extends('layouts.bookapp')

@section('title')
    検索結果
@endsection

@section('search-word', $search_word)

@section('content')
    <h2>検索結果</h2>

    @forelse ($books as $book)
        @if ($loop->first)
            <div class="row row-cols-auto justify-content-end">
                <div class="col">
                    <p>
                        お探しの本が見つからない方はこちら：
                    </p>
                </div>
                <div class="col">
                    @auth
                        <a href="{{ route('isbn') }}">新しい本を登録</a>
                    @else
                        <a href="{{ route('isbn') }}">ログインして新しい本を登録</a>
                    @endauth
                </div>
            </div>
        @endif

        <x-book-card :book="$book" @auth footer="button" @endauth />

        @if ($loop->last)
            <div class="float-end">
                <a class="btn btn-outline-secondary" href="{{ url('/') }}" role="button">トップページに戻る</a>
            </div>
            {{ $books->links() }}
        @endif
    @empty
        <div class="alert alert-dark mt-5" role="alert">
            <div class="mb-3">
                検索ワードに一致する本は見つかりませんでした
            </div>
            @auth
                <a href="{{ route('isbn') }}" class="alert-link">新しい本を登録</a><br>
            @else
                <a href="{{ route('isbn') }}" class="alert-link">ログインして新しい本を登録</a><br>
            @endauth
            <a href="{{ url('/') }}" class="alert-link">トップページに戻る</a>
        </div>
    @endforelse
    
@endsection
