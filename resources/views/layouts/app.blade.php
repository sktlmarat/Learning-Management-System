<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
        rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/connect.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin3.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dark_theme.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
</head>
<body>
<div id="app">
<div class="connect-container align-content-stretch d-flex flex-wrap">
    <Navbar :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></Navbar>
    <div class="page-container">
        <div class="page-header">
            <nav class="navbar navbar-expand">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item small-screens-sidebar-link">
                            <a href="#" class="nav-link"><i class="material-icons-outlined">menu</i></a>
                        </li>
                        <li class="nav-item nav-profile dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img src="http://127.0.0.1:8000{{ Storage::url(Auth::user()->avatar) }}" alt="">
                                <span>{{ Auth::user()->name }}</span><i class="material-icons dropdown-icon">keyboard_arrow_down</i>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Settings &amp Privacy</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="material-icons-outlined">mail</i></a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="material-icons-outlined">notifications</i></a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" id="dark-theme-toggle"><i class="material-icons-outlined">brightness_2</i><i
                                    class="material-icons">brightness_2</i></a>
                        </li>
                </ul>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="#" class="nav-link">Projects</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Tasks</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Reports</a>
                        </li>
                    </ul>
                </div>

                <div class="navbar-search">
                    <form>
                        <div class="form-group">
                            <input type="text" name="search" id="nav-search" placeholder="Search...">
                        </div>
                    </form>
                </div>
            </nav>
            @endguest
        </div>
    <App :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></App>
</div>
</div>
</div>
{{--<script src="{{ asset('js/jquery-3.4.1.min.js') }}" defer></script>--}}
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="{{ asset('js/popper.min.js') }}" defer></script>
<script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
<script src="{{ asset('js/jquery.sparkline.min.js') }}" defer></script>
<script src="{{ asset('js/connect.min.js') }}" defer></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}" defer></script>

</body>
</html>
