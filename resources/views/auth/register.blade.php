@extends('design.login')
@section('title', 'Register')
@section('subtitle', 'Please fill to proceed.')
@section('head')
    @if(config('services.turnstile.key'))
        @turnstileScripts()
    @endif
@endsection
@section('content')

                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-primary mb-2">{{ __('Create an Account!') }}</h1>
                                </div>
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        <b>{{ __('general.success') }}</b><br/>
                                        {{ session('status') }}
                                    </div>
                                @else
                                    <form class="user" method="POST" action="{{ locale_route('register') }}">
                                        @csrf

                                        <div class="form-group mt-3">

                                                <input type="text" class="form-control form-control-user @error('name') is-invalid @enderror" name="name"
                                                       placeholder="{{ __('Your Name') }}" value="{{ old('name') }}">
                                            @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group mt-3">
                                            <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                                                   name="email"
                                                   placeholder="{{ __('general.email_address') }}"
                                                   value="{{ old('email') }}">
                                            @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="password" class="form-control form-control-user  @error('password') is-invalid @enderror"
                                                       name="password"
                                                       placeholder="{{ __('Password') }}">
                                                @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control form-control-user @error('password_confirmation') is-invalid @enderror"
                                                       name="password_confirmation" autocomplete="new-password"
                                                       placeholder="{{ __('Confirm Password') }}">
                                                @error('password_confirmation')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                        @if(config('services.turnstile.key'))
                                        <div class="form-group mt-3 text-center">
                                            <x-turnstile
                                                data-theme="light"
                                            />
                                            @error('cf-turnstile-response')
                                            <div class=" text-danger">
                                                {{ $message }}
                                            </div>
                                            @enderror

                                        </div>
                                        @endif

                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-primary btn-user">
                                                {{ __('Register') }}
                                            </button>
                                        </div>
                                        @if(config('auth.microsoft.client_id'))
                                        <hr>
                                        <div class="d-grid gap-2">
                                            <a href="{{ locale_route('auth.microsoft') }}" class="btn btn-google btn-user">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-windows" viewBox="0 0 16 16">
                                                    <path d="M6.555 1.375 0 2.237v5.45h6.555zM0 13.795l6.555.933V8.313H0zm7.278-5.4.026 6.378L16 16V8.395zM16 0 7.33 1.244v6.414H16z"/>
                                                </svg> @lang('auth.login_with_microsoft')
                                            </a>
                                        </div>
                                        @endif
                                    </form>
                                @endif
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ locale_route('login') }}">{{ __('general.login') }}</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="{{ locale_route('password.request') }}">@lang('auth.password_forgot')</a>
                                </div>
                                @include('auth.footer')
                            </div>
                        </div>
                    </div>

@endsection
