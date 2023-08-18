@extends('layouts.recommendation-form')

@section('title')
    推薦文編集
@endsection

@section('form')
    @parent
    @method('PATCH')
@endsection

@section('action')
    {{ route('recommendation.update', $recommendation->id) }}
@endsection

@section('description')
    推薦文を編集してください（500文字以内）
@endsection

@section('old', old('recommendation', $recommendation))

@section('submit')
    完了
@endsection

@section('back-url')
    {{ route('recommendation.index') }}
@endsection

@section('back')
    あなたの推薦文一覧に戻る
@endsection
