@extends('layouts.bookapp')

@section('title')
    あなたの推薦文
@endsection

@section('content')
    <h2>あなたの推薦文</h2>

    @forelse ($recommendations as $recommendation)
        @if ($loop->first)
            <p>参考にされた回数：{{ $total_hearts }}</p>
        @endif
        <x-book-recommendation-card :recommendation="$recommendation" footer="button">
            <x-slot:heart_slot>
                <x-heart-button :recommendation="$recommendation" disabled />
            </x-slot>
        </x-book-recommendation-card>
    @empty
        <x-empty-message>
            推薦文はまだありません
        </x-empty-message>
    @endforelse

    <div class="float-end">
        <a class="btn btn-outline-secondary" href="{{ url('/') }}" role="button">トップページに戻る</a>
    </div>

    {{ $recommendations->links() }}
@endsection
