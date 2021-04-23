@extends('layouts.login')
@section('title', __('general.login'))
@section('subtitle', __('auth.login_start_message'))
@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="field">
        <div class="control">
            <b-field @error('email') label="Error"
                     type="is-danger"
                     message="{{ $message }}" @enderror>
                <b-input placeholder="{{ __('general.email_address') }}"
                         type="email"
                         name="email"
                         value="{{ old('email') }}"
                         icon="email">
                </b-input>
            </b-field>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <b-field @error('password') label="Error"
                     type="is-danger"
                     message="{{ $message }}" @enderror>
                <b-input placeholder="{{ __('general.password') }}"
                         type="password"
                         name="password"
                         icon="key">
                </b-input>
            </b-field>
        </div>
    </div>
    <div class="field">
        <label class="checkbox">
            <b-checkbox name="remember"  :value="{{ old('remember') ? 'true' : 'false' }}">
                 @lang('auth.remember_me')
            </b-checkbox>

        </label>
    </div>
    <div class="field">
        <button class="button is-block is-primary is-large is-fullwidth" type="submit">Login</button>
    </div>
    <div class="field">
        <b-button tag="a" size="is-medium" icon-left="microsoft-windows" href="{{ route('auth.microsoft') }}">
            @lang('auth.login_with_microsoft')
        </b-button>
    </div>
</form>
@endsection
