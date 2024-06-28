<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo64.png') }}?v2">
    <title>{{ isset($pageTitle) ? $pageTitle .' - ' : '' }}{{ env('APP_NAME', 'Education') }}</title>
    @vite('resources/css/app.scss')
    <script>
        window.user_locale = "{{ user_locale() }}";
        window.fallback_locale = "{{ config('app.fallback_locale') }}";
    </script>
</head>

<body>
<div id="app"></div>


@vite('resources/js/app.js')

</body>

</html>
