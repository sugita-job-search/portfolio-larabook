@props(['book', 'footer' => null, 'button' => null])
<div class="card my-4 book-card">
    <div class="card-header">
        <a href="{{ route('book.show', ['book' => $book->id]) }}" class="link-dark">{{ $book->title }}</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <a href="{{ route('book.show', ['book' => $book->id]) }}">
                    <img src="{{ asset($book->image) }}" alt="" class="img-fluid">
                </a>
            </div>
            <div class="col-md-10">
                <table class="table table-borderless">
                    <tr>
                        <th>著者</th>
                        <td>
                            <div class="row row-cols-auto">
                                @foreach ($book->author as $a)
                                    <div class="col">
                                        <a href="{{ route('search', [App\Common\UrlParameter::AUTHOR => $a]) }}"
                                            class="link-dark">{{ $a }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>出版社</th>
                        <td>{{ $book->publisher }}</td>
                    </tr>
                    <tr>
                        <th>出版年月</th>
                        <td>{{ $book->year }}年{{ $book->month }}月</td>
                    </tr>
                    @if ($book->series_title !== null)
                        <tr>
                            <th>シリーズ名</th>
                            <td>
                                <a href="{{ route('search', [App\Common\UrlParameter::SERIES => $book->series_title]) }}"
                                    class="link-dark">{{ $book->series_title }}</a>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        @if ($footer == 'button')
            <div class="row mt-3">
                <form action="{{ route('recommendation.create') }}" method="get" class="col-sm-4 d-grid mb-1">
                    <input type="hidden" name="{{ App\Common\UrlParameter::BOOK_ID }}" value="{{ $book->id }}">
                    <button type="submit" class="btn btn-info">この本の推薦文を書く</button>
                </form>
                <div class="col-sm-4 d-grid mb-1">
                    <a href="{{ route('book.show', ['book' => $book->id]) }}" class="btn btn-success">この本の推薦文を見る</a>
                </div>
                @if ($button == 'delete')
                    <div class="col-sm-4 d-grid mb-1">
                        <a href="{{ route('want-to-read.delete', ['book_id' => $book->id]) }}"
                            class="btn btn-secondary">読みたい本から削除</a>
                    </div>
                @else
                    @if ($book->loginWant->isEmpty())
                        <div class="col-sm-4 d-grid mb-1">
                            <button type="button" class="btn btn-warning btn-sm want-button"
                                data-book-id="{{ $book->id }}" aria-live="polite">読みたい本に追加</button>
                        </div>
                    @else
                        <div class="col-sm-4 d-grid mb-1">
                            <button type="button" class="btn btn-dark btn-sm" disabled>読みたい本に追加済み</button>
                        </div>
                    @endif
                @endif
            </div>
        @endif

    </div>
</div>
