<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo64.png') }}">
    <title>{{ isset($pageTitle) ? $pageTitle .' - ' : '' }}{{ env('APP_NAME', 'Education') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ mix('css/bulma.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
</head>

<body>
<section class="hero mainBgColor" id="header">
    <div class="heading-bar">{{__('Welcome')}} {{ Auth::user()->name }}</div>
    <div class="hero-body">
        <div class="container">
            <img src="{{ asset('images/logo64.png') }}" class="bear-logo">
            <h1 class="title">
                {{ env('APP_NAME', 'Projects') }}
            </h1>
            <h2 class="subtitle">
                {{ __('Project schedule') }}
            </h2>
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
                <div class="show-menu-btn">
                    <b-button size="is-small" @click="showMenu = !showMenu" title="Collapse Menu"
                              icon-left="arrow-expand-left">

                    </b-button>
                </div>
                @section('content')
                    Content not loaded
                @show
            </div>
        </div>
    </div>
</section>
<script async type="text/javascript" src="{{ mix('js/app.js') }}"></script>
</body>

</html>
