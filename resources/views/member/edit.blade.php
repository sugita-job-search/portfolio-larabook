@extends('layouts.bookapp')

@section('content')
    <div class="row justify-content-center form-area py-2">
        <div class="col-sm-9">
            <h2 class="my-4">会員情報変更</h2>
            <p>新しい会員情報を入力してください</p>
            <form action="{{ route('member.update') }}" method="post" class="my-3">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">ニックネーム</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        id="name" value="{{ old('name', Auth::user()->name) }}" />
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" value="{{ old('email', Auth::user()->email) }}" />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="Password" class="form-label">パスワード</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        id="Password" />
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>

                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="genre" class="form-label">好きなジャンル（任意）</label>
                    <select name="genre_id" class="form-select @error('genre_id') is-invalid @enderror" id="genre">
                        <option value="">選択されていません</option>

                        @foreach ($genres as $id => $genre)
                            <option value="{{ $id }}" @if ($id == old('genre_id', Auth::user()->genre_id)) selected @endif>
                                {{ $genre }}</option>
                        @endforeach
                    </select>
                    @error('genre_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>好きなジャンルを正しく選択してください。</strong>
                        </span>
                    @enderror
                </div>
                <div class="d-grid gap-2 d-md-block mt-5">
                    <button type="submit" class="btn btn-primary mx-2">変更</button>
                    <a class="btn btn-outline-secondary mx-2" href="{{ route('member') }}" role="button">会員情報確認ページに戻る</a>
                </div>
            </form>
        </div>
    </div>
@endsection
