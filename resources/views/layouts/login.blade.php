<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/logo64.png') }}?v2">
    <title>@yield('title') - {{ env('APP_NAME', 'Education') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ mix('css/bulma.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
</head>

<body>
<section class="hero is-success is-fullheight" id="sectionContent">
    <div class="hero-body">
        <div class="container has-text-centered">
            <div class="column @yield('heroboxclass', 'is-4 is-offset-4')">
                <h3 class="title has-text-black">@yield('title')</h3>
                <hr class="login-hr">
                <p class="subtitle has-text-black">@yield('subtitle')</p>
                <div class="box">
                    <figure class="avatar-borderless">
                        <img src="{{ asset('images/logo.svg') }}" style="height: 128px; ">
                    </figure>
                    @section('content')
                        Content not loaded
                    @show
                </div>
                <p class="has-text-grey">
                    <a href="{{ route('login') }}">Login</a> &nbsp;·&nbsp;
                    <a href="{{ route('register') }}">Sign Up</a> &nbsp;·&nbsp;
                    <a href="{{ route('password.request') }}">Forgot Password</a>
                    @if(file_exists(storage_path('app/disclaimer.txt')))
                        &nbsp;·&nbsp;<a href="{{ route('disclaimer') }}">Disclaimer</a>
                    @endif
                </p>
            </div>
        </div>
    </div>
    <div class="github-present">
        <b-button icon-left="github"
                  tag="a"
                  href="https://github.com/santwer/BearSchedule"
                  target="_blank" >
            View on GitHub
        </b-button>
    </div>
</section>

<script async type="text/javascript" src="{{ mix('js/login.js') }}"></script>
</body>

</html>
