@extends('layouts.bookapp')

@section('content')
    <x-book-information :book="$book" />

    <h2 class="mt-5">@yield('title')</h2>
    <form action="@yield('action')" method="post" id="form">
    @section('form')
        @csrf
        <p>@yield('description')</p>
        <div class="mb-3">
            <p>1.この本のいいところを選んでください</p>
            <div class="row row-cols-md-2 row-cols-1 ps-3">
                {{-- バリデーションエラーのときは赤くする --}}
                @error('merits')
                    @foreach (App\Models\Merit::getMerits() as $id => $merit)
                    <div class="form-check col">
                        <input class="form-check-input is-invalid" type="checkbox" name="merits[]" value="{{ $id }}"
                            id="merit{{ $id }}" @if (in_array($id, $old_merits)) checked @endif>
                        <label class="form-check-label" for="merit{{ $id }}">
                            {{ $merit }}
                        </label>
                    </div>
                    @endforeach
                @else
                    @foreach (App\Models\Merit::getMerits() as $id => $merit)
                        <div class="form-check col">
                            <input class="form-check-input" type="checkbox" name="merits[]" value="{{ $id }}"
                                id="merit{{ $id }}" @if (in_array($id, $old_merits)) checked @endif>
                            <label class="form-check-label" for="merit{{ $id }}">
                                {{ $merit }}
                            </label>
                        </div>
                    @endforeach
                @enderror

            </div>
        </div>
        <div class="mb-3">
            <p>2.推薦文を入力してください（500文字以内）</p>
            <textarea name="recommendation" class="form-control white-input @error('recommendation') is-invalid @enderror"
                rows="5" aria-label="推薦文">@yield('old')</textarea>
            @error('recommendation')
                <x-form-error-message :message="$message" />
            @enderror
        </div>
        <div class="d-grid gap-2 d-md-block">
            <button type="submit" class="btn btn-primary mx-2">@yield('submit')</button>
            <a class="btn btn-outline-secondary mx-2" href="@yield('back-url')" role="button">@yield('back')</a>
        </div>
    @show
</form>
@endsection
