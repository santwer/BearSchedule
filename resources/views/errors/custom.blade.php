@extends('design.login')
@section('title', $code . ' - ' . $message)
@section('content')

    <div class="row" style="min-height: 400px">
        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
        <div class="col-lg-6">
            <nav class="navbar navbar-expand-sm p-1">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('images/logo.svg') }}" width="30" height="30" class="d-inline-block align-top" alt="">
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


                </div>
            </nav>
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-primary mb-2">
                        {{ $code }} | {{ $message }}
                    </h1>
                </div>
                <!-- 5xx Error -->
                @if($code >= 500 && $code < 600)
                <div class="mt-5">
                    <p>
                        The server encountered an internal error or misconfiguration and was unable to complete your request.
                    </p>
                    <p>
                        Please contact the server administrator or report on <a href="https://github.com/santwer/BearSchedule"
                        target="_blank" >Github</a> to inform them of the time this error occurred, and the actions you performed just before this error.
                        Otherwise, please try again later.
                    </p>
                </div>
                @endif
                <!-- 4xx Error -->
                @if($code >= 400 && $code < 500)
                <div class="mt-5">
                    <p>
                        The server cannot process the request due to something that is perceived to be a client error (e.g., malformed request syntax, invalid request message framing, or deceptive request routing).
                    </p>
                    <p>
                        Please check the URL and try again. If the problem persists, please contact the server administrator or report on <a href="https://github.com/santwer/BearSchedule"
                                                                                                                                             target="_blank" >Github</a>.
                    </p>
                 @endif
            </div>
        </div>
    </div>
@endsection
