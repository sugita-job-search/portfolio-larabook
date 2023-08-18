@extends('layouts.bookapp')

@section('content')
    <h2>表示ジャンル選択</h2>
    <form action="{{ url('/') }}" method="get" class="mx-1 my-3">
        <div class="row">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="genres[]" value="0" id="all-check">
                <label class="form-check-label" for="all-genre">
                    すべて
                </label>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3">
            @foreach ($genres as $id => $genre)
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input one-check" type="checkbox" name="genres[]"
                            value="{{ $id }}" id="{{ $genre }}">
                        <label class="form-check-label" for="{{ $genre }}">
                            {{ $genre }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
        @isset($sort)
            <input type="hidden" name="{{ App\Common\UrlParameter::SORT }}" value="{{ $sort }}">
        @endisset
        <div class="d-grid gap-2 d-md-block mt-3">
            <button type="submit" class="btn btn-primary mx-2">決定</button>
            <a class="btn btn-outline-secondary mx-2" href="{{ url('/') }}" role="button">トップページに戻る</a>
        </div>
    </form>
@endsection
