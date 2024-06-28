@extends('design.login')
@section('title', __('auth.password_forgot'))
@section('subtitle', __('auth.login_start_message'))
@section('content')
    <!-- Nested Row within Card Body -->
    <div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-2">{{ __('auth.password_forgot_question') }}</h1>
                    <p class="mb-4">{{ __('auth.sentence explain') }}!</p>
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        <b>{{ __('general.success') }}</b><br/>
                        {{ session('status') }}
                    </div>
                @else
                    <form method="POST" class="user" action="{{ locale_route('password.email') }}">
                        @csrf
                        <div class="form-group mt-3">
                            <input type="email" name="email"
                                   class="form-control form-control-user  @error('email') is-invalid @enderror"
                                   aria-describedby="emailHelp" value="{{ old('email') }}"
                                   placeholder="{{ __('general.email_address') }}">
                            @error('email')
                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-user">
                                {{ __('auth.send_password_reset_link') }}
                            </button>
                        </div>
                    </form>
                @endif
                <hr>
                <div class="text-center">
                    <a class="small" href="{{ locale_route('login') }}">{{ __('general.login') }}</a>
                </div>
                <div class="text-center">
                    <a class="small" href="{{ locale_route('register') }}">@lang('auth.sign_up')</a>
                </div>
                @include('auth.footer')
            </div>
        </div>
    </div>
@endsection
