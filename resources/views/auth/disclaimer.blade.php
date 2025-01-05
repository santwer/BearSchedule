@extends('design.login')
@section('title', $title)
@section('content')
    <nav class="navbar navbar-expand-sm p-1">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/logo.svg') }}" width="30" height="30" class="d-inline-block align-top" alt="">
            {{ env('APP_NAME', 'BearSchedule') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav  mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ locale_route('login') }}">{{ __('general.login') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ locale_route('register') }}">{{ __('auth.sign_up') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ locale_route('disclaimer') }}">{{ __('menu.imprint') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ locale_route('privacy') }}">{{ __('menu.privacy') }}</a>
                </li>
            </ul>

            @if(env('SHOW_GITHUB', false))
                <form class="form-inline">
                    <a href="https://github.com/santwer/BearSchedule"
                       target="_blank" class="btn btn-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-github" viewBox="0 0 16 16">
                            <path
                                d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8"/>
                        </svg> @lang('general.view_on_github')

                    </a>
                </form>
            @endif


        </div>
    </nav>
    <div style="text-align: left" class="p-2">
        @if(file_exists($file))
            {!! file_get_contents($file) !!}
        @endif
    </div>
@endsection
