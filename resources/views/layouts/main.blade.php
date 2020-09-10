<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($pageTitle) ? $pageTitle .' - ' : '' }}{{ env('APP_NAME', 'Education') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
</head>

<body>
<section class="hero mainBgColor" id="header">
    <div class="heading-bar"></div>
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                {{ env('APP_NAME', 'Education') }}
            </h1>
            <h2 class="subtitle">
                {{ __('Project schedule') }}
            </h2>
        </div>

    </div>
</section>
<section class="hero" id="content">
    @section('pre-content')
    @show
    <div class="correct-hero">
        <div class="columns">
            <x-main-menu></x-main-menu>

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
