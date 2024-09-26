@extends('design.login')
@section('title', __('general.login'))
@section('subtitle', __('auth.login_start_message'))
@section('head')
@endsection
@section('content')

    <div class="row">
        <div class="col-lg-6 d-none d-lg-block hero-video">
            <video autoplay muted loop>
                <source src="{{ asset('video/planner_normal.mp4') }}" type="video/mp4">
            </video>
        </div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="btn-group btn-group-sm position-absolute" style="right: 0.5rem; top: 0.5rem">
                    <button type="button" class="btn" title="{{ __('general.dark_mode_toogle') }}"
                            :class="{'btn-dark': this.theme !== 'dark','btn-light': this.theme === 'dark'}"
                            @click="toggleTheme">
                        <mdicon name="theme-light-dark" size="16"/>
                    </button>
                </div>
                <div  class="logostyle"><img src="{{ asset('images/logo.svg') }}"></div>

                <div class="text-center">
                    <h1 class="h4 text-primary mb-4">@lang('general.login')</h1>
                </div>

                <form method="POST" action="{{ locale_route('login') }}" class="user">
                    @csrf
                    <div class="form-group">
                        <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                               aria-describedby="emailHelp" value="{{ old('email') }}" name="email"
                               placeholder="{{ __('general.email_address') }}">
                        @error('email')
                        <div id="validationServerUsernameFeedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <input type="password" name="password"
                               class="form-control form-control-user @error('password') is-invalid @enderror"
                               value="{{ old('password') }}"
                               placeholder="{{ __('general.password') }}">
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group m-3">
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" name="remember" class="custom-control-input"
                                   id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="custom-control-label px-2" for="remember">@lang('auth.remember_me')</label>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-user">
                            @lang('general.login')
                        </button>
                    </div>
                    @if(config('auth.microsoft.client_id'))
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="{{ locale_route('auth.microsoft') }}" class="btn btn-google btn-user">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-windows" viewBox="0 0 16 16">
                                    <path
                                        d="M6.555 1.375 0 2.237v5.45h6.555zM0 13.795l6.555.933V8.313H0zm7.278-5.4.026 6.378L16 16V8.395zM16 0 7.33 1.244v6.414H16z"/>
                                </svg> @lang('auth.login_with_microsoft')
                            </a>
                        </div>
                    @endif
                </form>
                <hr>
                <div class="text-center">
                    <a class="small" href="{{ locale_route('password.request') }}">@lang('auth.password_forgot')</a>
                </div>
                <div class="text-center">
                    <a class="small" href="{{ locale_route('register') }}">@lang('auth.sign_up')</a>
                </div>
                @include('auth.footer')
            </div>
        </div>
    </div>

@endsection
