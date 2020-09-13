<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo64.png') }}">
    <title>{{ isset($pageTitle) ? $pageTitle .' - ' : '' }}{{ env('APP_NAME', 'Education') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ '/share/'.$unique.'/share.css' }}">
</head>

<body>
<div id="project-timeline"></div>
<script async type="text/javascript" src="{{ '/share/'.$unique.'/share.js' }}"></script>
</body>
</html>
