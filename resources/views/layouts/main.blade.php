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
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.1/moment-with-locales.min.js"></script>
    <script>
        const enableJira = {{ \App\Helper\JiraHelper::isEnabled() ? 'true' : 'false' }};
        window.default_locale = "{{ config('app.locale') }}";
        window.fallback_locale = "{{ config('app.fallback_locale') }}";
        window.messages = @json(getTranslatationMessages())
    </script>
</head>

<body>
<section class="hero mainBgColor" id="header">
    <div class="heading-bar">{{__('general.welcome', ['name' => Auth::user()->name])}}</div>
    <div class="hero-body bs-hero">
        <div class="container">
            <div class="columns" >
                <div class="column appinfo">
                    <img src="{{ asset('images/logo.svg') }}" class="bear-logo">
                    <h1 class="title">
                        {{ env('APP_NAME', 'Projects') }}
                    </h1>
                    <h2 class="subtitle">
                        @lang('general.project_schedule')
                    </h2>
                </div>
            </div>

        </div>

    </div>
    <div class="logout-area">
        <a href="{{ locale_route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <button class="button is-primary ">{{ __('general.logout') }}</button>
        </a>
        <form id="logout-form" action="{{ locale_route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</section>
<section class="hero" id="content">
    @section('pre-content')
    @show
   <div class="menu-button-hide-show"><b-button v-on:click="showMenu = !showMenu"><b-icon icon="menu-open"></b-icon> </b-button></div>
    <div class="correct-hero">
        <div class="columns">
            <x-main-menu projectid="{{isset($project) ? $project : 0 }}"></x-main-menu>

            <div class="column timelineContent">
                @section('content')
                    @lang('general.content_not_loaded')
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
