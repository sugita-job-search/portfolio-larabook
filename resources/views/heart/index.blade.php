@extends('layouts.bookapp')

@section('title')
    参考になった推薦文
@endsection

@section('content')
    <h2>参考になった推薦文</h2>

    @forelse ($recommendations as $recommendation)
        <x-top-recommendation-card :recommendation="$recommendation" />
    @empty
        <x-empty-message>
            参考になった推薦文はまだありません
        </x-empty-message>
    @endforelse

    <div class="float-end">
        <a class="btn btn-outline-secondary" href="{{ url('/') }}" role="button">トップページに戻る</a>
    </div>

    {{ $recommendations->links() }}
@endsection
