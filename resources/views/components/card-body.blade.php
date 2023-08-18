{{-- div class="card-body"　の中の推薦文 --}}
@props(['recommendation'])

@foreach ($recommendation->merits as $merit)
    <span class="badge text-dark mb-2 merit">{{ $merit->merit }}</span>
@endforeach
@if ($recommendation->recommendation !== null)
    <p class="card-text">
        {!! nl2br(e($recommendation->recommendation)) !!}
    </p>
@endif
