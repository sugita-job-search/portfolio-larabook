@props(['book', 'isbn' => null])
<div class="row">
    <div class="col-sm-3 mb-2">
        <img src="{{ asset($book->image) }}" alt="" class="img-fluid">
    </div>
    <div class="col-sm-8 mx-2">
        <h3>{{ $book->title }}</h3>
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
            @if ($isbn == '10')
                <tr>
                    <th>ISBN-10</th>
                    <td>{{ App\Common\Common::convertIsbn13To10($book->isbn) }}</td>
                </tr>
                <tr>
                    <th>ISBN-13</th>
                    <td>{{ $book->isbn }}</td>
                </tr>
            @else
                <tr>
                    <th>ISBN</th>
                    <td>{{ $book->isbn }}</td>
                </tr>
            @endif
            @if ($book->series_title !== null)
                <tr>
                    <th>シリーズ名</th>
                    <td><a href="{{ route('search', [App\Common\UrlParameter::SERIES => $book->series_title]) }}"
                            class="link-dark">{{ $book->series_title }}</a></td>
                </tr>
            @endif
            <tr>
                <th>ジャンル</th>
                <td>{{ $book->genre->genre }}</td>
            </tr>
        </table>
    </div>
</div>
