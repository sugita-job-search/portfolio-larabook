@extends('layouts.book-form')

@section('title')
本の登録
@endsection

@section('action', route('book.confirm'))

@section('next', '入力内容の確認')

@section('back', route('isbn'))