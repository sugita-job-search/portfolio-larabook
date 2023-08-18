@extends('layouts.bookapp')

@section('title')
    {{ $book->title }}
@endsection

@section('content')
    <x-book-information :book="$book" />

    @auth
        <div class="row mt-3">
            @if ($book->loginWant->isEmpty())
                {{-- <form action="{{ route('want-to-read.store') }}" method="post" class="col-sm-4 d-grid mb-2">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <button type="submit" class="btn btn-warning">この本を読みたい本に追加</button>
                </form> --}}
                <div class="col-sm-4 d-grid mb-1">
                    <button type="button" class="btn btn-warning want-button" data-book-id="{{ $book->id }}"
                        aria-live="polite">読みたい本に追加</button>
                </div>
            @else
                <div class="col-sm-4 d-grid mb-2">
                    <button type="button" class="btn btn-dark" disabled>読みたい本に追加済み</button>
                </div>
            @endif
            <form action="{{ route('recommendation.create') }}" method="get" class="col-sm-4 d-grid mb-2">
                <input type="hidden" name="{{ App\Common\UrlParameter::BOOK_ID }}" value="{{ $book->id }}">
                <button type="submit" class="btn btn-info">この本の推薦文を書く</button>
            </form>
            <div class="col-sm-4 d-grid mb-2">
                <a href="{{ route('book.edit', ['book' => $book->id]) }}" class="btn btn-outline-success">本の情報を変更</a>
            </div>
        </div>
    @endauth

    <div class="recommendation-area my-4">
        <h3>この本の推薦文</h3>

        @auth
            @forelse ($recommendations as $r)
                <div class="card my-4 recommendation-card">
                    <div class="card-body">
                        <x-card-body :recommendation="$r" />
                        <x-heart-button :recommendation="$r" />
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('recommendation.user', ['user' => $r->user->id]) }}" class="link-dark">
                            {{ $r->user->name }}
                        </a>
                    </div>
                </div>
            @empty
                <x-empty-message>
                    この本の推薦文はまだありません
                </x-empty-message>
            @endforelse
        @else
            @forelse ($recommendations as $r)
                <div class="card my-4 recommendation-card">
                    <div class="card-body">
                        <x-card-body :recommendation="$r" />
                        <x-heart-login-button :recommendation="$r" />
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('recommendation.user', ['user' => $r->user->id]) }}" class="link-dark">
                            {{ $r->user->name }}
                        </a>
                    </div>
                </div>
            @empty
                <x-empty-message>
                    この本の推薦文はまだありません
                </x-empty-message>
            @endforelse
        @endauth

        <div class="float-end">
            <a class="btn btn-outline-secondary" href="{{ url('/') }}" role="button">トップページに戻る</a>
        </div>

        {{ $recommendations->links() }}
    </div>
@endsection
