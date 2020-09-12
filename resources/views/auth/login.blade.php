@extends('layouts.login')
@section('title', 'Login')
@section('subtitle', 'Please login to proceed.')
@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="field">
        <div class="control">
            <b-field @error('email') label="Error"
                     type="is-danger"
                     message="{{ $message }}" @enderror>
                <b-input placeholder="{{ __('E-Mail Address') }}"
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
                <b-input placeholder="{{ __('Password') }}"
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
                {{ __('Remember Me') }}
            </b-checkbox>

        </label>
    </div>
    <button class="button is-block is-primary is-large is-fullwidth" type="submit">Login</button>
</form>
@endsection
