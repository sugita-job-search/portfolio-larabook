@extends('layouts.bookapp')

@section('content')
    <div class="row justify-content-center form-area pt-5 pb-2">
        <div class="col-sm-9">

            <h2>@yield('title')</h2>
            <p>@yield('description')</p>

            <form action="@yield('action')" method="post" enctype="multipart/form-data" class="my-3">
                @csrf
            @section('form')

                <div class="mb-3">
                    <label for="title" class="form-label">タイトル（必須）</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                        id="title" value="{{ old('title', $book) }}" />
                    @error('title')
                        <x-form-error-message :message="$message" />
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="author" class="form-label">著者（必須）</label>
                    <div class="form-text @error('author') is-invalid @enderror">複数の著者がいる場合は改行して入力してください</div>
                    <textarea class="form-control @error('author') is-invalid @enderror" name="author" id="exampleFormControlTextarea1"
                        rows="3">{{ old('author', implode("\n", $book->author)) }}</textarea>
                    @error('author')
                        <x-form-error-message :message="$message" />
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="publisher" class="form-label">出版社（必須）</label>
                    <input type="text" class="form-control @error('publisher') is-invalid @enderror" name="publisher"
                        id="publisher" value="{{ old('publisher', $book) }}" />
                    @error('publisher')
                        <x-form-error-message :message="$message" />
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="form-label">出版年月（必須）</div>
                    <div class="form-text">西暦で入力してください</div>
                    <div class="input-group has-validation">
                        <input type="text" id="year" class="form-control @error('year') is-invalid @enderror"
                            name="year" aria-label="出版年" value="{{ old('year', $book) }}">
                        <span class="input-group-text">年</span>
                        <select class="form-select @error('month') is-invalid @enderror" name="month" aria-label="出版月">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value={{ $i }} @if (old('month', $book) == $i) selected @endif>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        <span class="input-group-text">月</span>
                        @if ($errors->has('year'))
                            <x-form-error-message :message="$errors->first('year')" />
                        @elseif ($errors->has('month'))
                            <x-form-error-message message="出版年月を正しく入力してください。" />
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label for="series" class="form-label">シリーズ名</label>
                    <input type="text" name="series_title"
                        class="form-control @error('series_title') is-invalid @enderror" id="series"
                        value="{{ old('series_title', $book) }}" />
                    @error('series_title')
                        <x-form-error-message :message="$message" />
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="genre" class="form-label">ジャンル（必須）</label>
                    <select class="form-select @error('genre_id') is-invalid @enderror" name="genre_id" id="genre">
                        <option value="" selected>選択されていません</option>
                        @foreach (App\Models\Genre::getGenres() as $id => $genre)
                            <option value="{{ $id }}" @if (old('genre_id', $book) == $id) selected @endif>
                                {{ $genre }}</option>
                        @endforeach
                    </select>
                    @error('genre_id')
                        <x-form-error-message :message="$message" />
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">表紙の画像</label>
                    @yield('old-image')
                    <div class="form-text">PNGまたはJPEGファイルがアップロードできます</div>
                    <input class="form-control @error('image') is-invalid @enderror" type="file" name="image"
                        id="formFile">
                    @error('image')
                        <x-form-error-message :message="$message" />
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-block mt-4">
                    <button type="submit" class="btn btn-primary mx-2">@yield('next')</button>
                    <a href="@yield('back')" class="btn btn-secondary mx-2">戻る</a>
                </div>
            @show
        </form>
    </div>
</div>
@endsection
