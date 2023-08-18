@props(['recommendation', 'heart_slot' => null, 'footer' => null, 'name' => null])
<div class="card book-recommendation-card my-4" id="{{ $recommendation->id }}">
    <div class="card-header">
        <div class="row">
            <div class="col-md-1">
                <a href="{{ route('book.show', ['book' => $recommendation->book->id]) }}">
                    <img src="{{ asset($recommendation->book->image) }}" alt="" class="img-fluid">
                </a>
            </div>
            <div class="col-md-11">
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
        </div>
    </div>

    <div class="card-body pb-0">
        <x-card-body :recommendation="$recommendation" />
        {{-- ハートボタンを表示するスロット --}}
        {{ $heart_slot }}
    </div>
    <div class="card-footer">
        @switch($footer)
            @case('button')
                <div class="row row-cols-md-auto justify-content-end">
                    <div class="col d-grid">
                        <a href="{{ route('recommendation.edit', $recommendation->id) }}" class="btn btn-info btn-sm">編集</a>
                    </div>
                    <div class="col d-grid">
                        <a href="{{ route('recommendation.delete', $recommendation->id) }}"
                            class="btn btn-secondary btn-sm">削除</a>
                    </div>
                </div>
            @break

            @case('name')
                {{ $name }}
            @break

            @default
                <a href="{{ route('recommendation.user', ['user' => $recommendation->user->id]) }}" class="link-dark">
                    {{ $recommendation->user->name }}
                </a>
        @endswitch
    </div>
</div>
