@extends('layouts.login')
@section('title', __('Reset Password'))
@section('subtitle', 'Please fill to proceed.')
@section('content')
                    <form method="POST" action="{{ locale_route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <b-field @error('email') label="Error"
                                 type="is-danger"
                                 message="{{ $message }}" @enderror>
                            <b-input placeholder="{{ __('general.email_address') }}"
                                     type="email"
                                     name="email"
                                     value="{{ $email ?? old('email') }}"
                                     icon="email" required>
                            </b-input>
                        </b-field>


                        <b-field @error('password') label="Error"
                                 type="is-danger"
                                 message="{{ $message }}" @enderror>
                            <b-input placeholder="{{ __('general.password') }}"
                                     type="password"
                                     name="password"
                                     icon="key" required autocomplete="new-password">
                            </b-input>
                        </b-field>

                        <b-field>
                            <b-input placeholder="{{ __('auth.password_confirm') }}"
                                     type="password"
                                     name="password_confirmation"
                                     icon="key" required autocomplete="new-password">
                            </b-input>
                        </b-field>

                        <button class="button is-block is-primary is-large is-fullwidth" type="submit">{{ __('auth.password_reset) }}</button>

                    </form>

@endsection
