@extends('layouts.bookapp')

@section('title')
    ISBN入力
@endsection

@section('content')
    <h2>本の登録</h2>
    <p>登録する本のISBNを入力してください。<br>
        ISBNが付与されていない本には未対応です。
    </p>

    <form action="" method="post" class="my-5">
        <div class="row">
            <div class="col-md-9 mb-3">
                @csrf
                <input type="text" name="isbn" class="form-control white-input @error('isbn') is-invalid @enderror"
                    id="recommend-search" aria-label="ISBN" value="{{ old('isbn', $old) }}">
                @error('isbn')
                    <x-form-error-message :message="$message" />
                @enderror
            </div>
        </div>
        <div class="d-grid gap-2 d-md-block">
            <button type="submit" class="btn btn-primary mx-2">次へ</button>
            <a class="btn btn-outline-secondary mx-2" href="{{ url('/') }}" role="button">トップページに戻る</a>
        </div>
    </form>
@endsection
