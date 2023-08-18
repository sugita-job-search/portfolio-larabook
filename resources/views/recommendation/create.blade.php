@extends('layouts.recommendation-form')

@section('title')
    推薦文投稿
@endsection

@section('form')
    @parent
    <input type="hidden" name="book_id" value="{{ $book->id }}">
@endsection

@section('action')
    {{ route('recommendation.confirm') }}
@endsection

@section('description')
この本の素晴らしさを伝えてください。<br>
どちらかの項目だけでも投稿できます。
@endsection

@section('old', old('recommendation'))

@section('submit')
    入力内容の確認
@endsection

@section('back-url')
    {{url('/')}}
@endsection

@section('back')
    トップページに戻る
@endsection