<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-md shadow-sm py-1">
            <div class="container">
                <a href="{{ url('/') }}" class="navbar-brand fs-2">{{ config('app.name') }}</a>
            </div>
        </nav>
    </header>
    <div class="container">
        <main class="py-4">
            @section('top')
                <form action="{{ route('search') }}" method="get" class="d-flex justify-content-end top-search">
                    <input type="text" name="all" class="form-control form-control-sm top-input white-input"
                        id="search" placeholder="書名や著者名で検索" aria-label="検索" value="@yield('search-word')">
                    <button type="submit" class="btn btn-primary btn-sm">検索</button>
                </form>
            @show

            <div class="row">
                <div class="col-lg-9">
                    @yield('content')
                </div>

                <div class="col-lg-3">
                    @auth
                        <div class="card float-lg-end side-menu">
                            <div class="card-header">
                                {{ Auth::user()->name }}さん
                            </div>
                            <div class="list-group">
                                <a href="{{ route('recommendation.search') }}"
                                    class="list-group-item list-group-item-action">新しい推薦文を書く</a>
                                <a href="{{ route('want-to-read.index') }}"
                                    class="list-group-item list-group-item-action">読みたい本</a>
                                <a href="{{ route('recommendation.index') }}"
                                    class="list-group-item list-group-item-action">あなたの推薦文</a>
                                <a href="{{ route('heart.index') }}"
                                    class="list-group-item list-group-item-action">参考になった推薦文</a>
                                <a href="{{ route('member') }}" class="list-group-item list-group-item-action">会員情報確認</a>
                                <a class="list-group-item list-group-item-action" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mt-5 text-center" role="alert">
                            会員登録すれば<br>
                            読みたい本を記録したり<br>
                            推薦文を書いたりできます<br>
                            <div class="d-grid gap-2 mt-3">
                                <a href="{{ route('register') }}" class="btn btn-primary">会員登録</a>
                                <a href="{{ route('login') }}" class="btn btn-success">ログイン</a>
                            </div>
                        </div>
                    @endauth
                </div>
        </main>
        <footer>
            <p>&copy;Copyright sugita. All rights reserved</p>
        </footer>
    </div>
    <script></script>
</body>

</html>
