@extends('design.login')
@section('title', __('Reset Password'))
@section('subtitle', 'Please fill to proceed.')
@section('content')

    <div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-primary mb-2">{{ __('Reset Password') }}</h1>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">
                        <b>{{ __('general.success') }}</b><br/>
                        {{ session('status') }}
                    </div>
                @else
                    <form method="POST" class="user" action="{{ locale_route('password.update') }}">
                        @csrf
                        <div class="form-group mt-3">
                            <input type="email" name="email"
                                   class="form-control form-control-user  @error('email') is-invalid @enderror"
                                   aria-describedby="emailHelp" value="{{ $email ?? old('email') }}"
                                   placeholder="{{ __('general.email_address') }}">
                            @error('email')
                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <input type="password" name="password"
                                   class="form-control form-control-user  @error('password') is-invalid @enderror"
                                   aria-describedby="emailHelp" value=""
                                   placeholder="{{ __('general.password') }}">
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <input type="password" name="password_confirmation"
                                   class="form-control form-control-user  @error('password_confirmation') is-invalid @enderror"
                                   aria-describedby="emailHelp" value=""
                                   placeholder="{{ __('general.password_confirmation') }}">
                            @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="d-grid gap-2  mt-3">
                            <button type="submit" class="btn btn-primary btn-user">
                                {{ __('auth.password_confirm') }}
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
