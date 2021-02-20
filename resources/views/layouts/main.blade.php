<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo64.png') }}?v2">
    <title>{{ isset($pageTitle) ? $pageTitle .' - ' : '' }}{{ env('APP_NAME', 'Education') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ mix('css/bulma.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
</head>

<body>
<section class="hero mainBgColor" id="header">
    <div class="heading-bar">{{__('Welcome')}} {{ Auth::user()->name }}</div>
    <div class="hero-body bs-hero">
        <div class="container">
            <div class="columns" >
                <div class="column appinfo">
                    <img src="{{ asset('images/logo.svg') }}" class="bear-logo">
                    <h1 class="title">
                        {{ env('APP_NAME', 'Projects') }}
                    </h1>
                    <h2 class="subtitle">
                        {{ __('Project schedule') }}
                    </h2>
                </div>
            </div>

        </div>

    </div>
    <div class="logout-area">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <button class="button is-primary ">{{ __('Logout') }}</button>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</section>
<section class="hero" id="content">
    @section('pre-content')
    @show
    <div class="correct-hero">
        <div class="columns">
            <x-main-menu projectid="{{isset($project) ? $project : 0 }}"></x-main-menu>

            <div class="column timelineContent">
                @section('content')
                    Content not loaded
                @show
            </div>
        </div>
    </div>
</section>
<script>
    window.KoukyWebSocket = {{ \App\Helper\TimelineHelper::useWebsocket() ? 'true' : 'false' }};
</script>
<script async type="module" src="{{ mix('js/app.js') }}"></script>
</body>

</html>
