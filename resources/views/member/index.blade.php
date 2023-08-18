@extends('layouts.bookapp')

@section('content')
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <h2>会員情報</h2>
            <div class="my-4">
                <dl>
                    <div class="mb-3">
                        <dt>ニックネーム</dt>
                        <dd> {{ $user->name }} </dd>
                    </div>
                    <div class="mb-3">
                        <dt>メールアドレス</dt>
                        <dd>{{ $user->email }}</dd>
                    </div>
                    <div class="mb-3">
                        <dt>好きなジャンル</dt>
                        <dd>{{ $user->genre_id == null ? '未選択' : $user->genre->genre }}</dd>
                    </div>
                </dl>
            </div>
            <div class="d-grid gap-2 d-md-block mt-3">
                <a href="{{route('member.edit')}}" class="btn btn-primary mx-2">会員情報変更</a>
                <a class="btn btn-outline-secondary mx-2" href="{{ url('/') }}" role="button">トップページに戻る</a>
            </div>
        </div>
    </div>
@endsection
