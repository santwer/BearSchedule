<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home - {{ env('APP_NAME', 'Education') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
</head>

<body>
<section class="hero" id="header">
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
    <div class="correct-hero">
        <div class="columns">
            <div class="is-380">
                <b-menu-list label="Menu">
                    <b-menu-item icon="information-outline" label="Info"></b-menu-item>
                </b-menu-list>
            </div>
            <div class="column" style="background: lavender;">
                <students-timeline></students-timeline>
            </div>
        </div>
    </div>
</section>
<script async type="text/javascript" src="{{ mix('js/app.js') }}"></script>
</body>

</html>
