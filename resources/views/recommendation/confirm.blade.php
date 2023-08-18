@extends('layouts.bookapp')

@section('title')
    投稿内容確認
@endsection

@section('content')
    <x-book-information :book="$book" />

    <h2>推薦文投稿</h2>
    <form action="{{ route('recommendation.store') }}" method="post">
        @csrf
        <p>以下の内容を投稿します</p>
        <div class="card mb-3">
            <div class="card-body">
                @foreach ($merits as $merit)
                    <span class="badge text-dark mb-2 merit">{{ $merit }}</span>
                @endforeach
                @isset($input['recommendation'])
                    <p class="card-text">
                        {!! nl2br(e($input['recommendation'])) !!}
                    </p>
                @endisset
            </div>
        </div>
        <input type="hidden" name="book_id" value="{{ $input['book_id'] }}">
        @isset($input['recommendation'])
            <input type="hidden" name="recommendation" value="{{ $input['recommendation'] }}">
        @endisset
        @isset($input['merits'])
            @foreach ($input['merits'] as $merit)
                <input type="hidden" name="merits[]" value="{{ $merit }}">
            @endforeach
        @endisset
        <div class="d-grid gap-2 d-md-block">
            <button type="submit" class="btn btn-primary mx-2" name="complete" value="1">完了</button>
            <button type="submit" class="btn btn-secondary mx-2" name="complete" value="0">戻る</button>
        </div>
    </form>
@endsection
