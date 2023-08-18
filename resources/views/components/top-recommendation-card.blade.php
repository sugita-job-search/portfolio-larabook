@props(['recommendation', 'footer' => null, 'name' => null])
<div class="card book-recommendation-card my-4" id="{{ $recommendation->id }}">
    <div class="card-header">
        <div class="row">
            <div class="col-md-1">
                <a href="{{ route('book.show', ['book' => $recommendation->book->id]) }}">
                    <img src="{{ asset($recommendation->book->image) }}" alt="" class="img-fluid">
                </a>
            </div>
            <div class="col-md-8">
                <div class="book-title">
                    <a href="{{ route('book.show', ['book' => $recommendation->book->id]) }}" class="link-dark">
                        {{ $recommendation->book->title }}
                    </a>
                </div>
                <div class="row row-cols-auto">
                    @foreach ($recommendation->book->author as $a)
                        <div class="col">
                            <a href="{{ route('search', [App\Common\UrlParameter::AUTHOR => $a]) }}" class="link-dark">
                                {{ $a }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
                @if ($recommendation->book->loginWant->isEmpty())
                    <div class="d-grid">
                        <button type="button" class="btn btn-warning btn-sm want-button"
                            data-book-id="{{ $recommendation->book_id }}" aria-live="polite">読みたい本に追加</button>
                    </div>
                @else
                    <div class="d-grid">
                        <button type="button" class="btn btn-dark btn-sm" disabled>読みたい本に追加済み</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card-body pb-0">
        <x-card-body :recommendation="$recommendation" />
        <x-heart-button :recommendation="$recommendation" />
    </div>
    <div class="card-footer">
        @if ($footer == 'name')
            {{ $name }}
        @else
            <a href="{{ route('recommendation.user', ['user' => $recommendation->user->id]) }}" class="link-dark">
                {{ $recommendation->user->name }}
            </a>
        @endif
    </div>
</div>
