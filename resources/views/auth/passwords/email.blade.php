@extends('layouts.login')
@section('title', __('auth.password_forgot'))
@section('subtitle', __('auth.login_start_message'))
@section('content')
                <div class="card-body">
                    @if (session('status'))
                        <b-message title="{{ __('general.success') }}" type="is-success" aria-close-label="Close message">
                            {{ session('status') }}
                        </b-message>
                    @else
                    <form method="POST" action="{{ locale_route('password.email') }}">
                        @csrf
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
                        <button class="button is-block is-primary is-large is-fullwidth" type="submit">{{ __('auth.send_password_reset_link') }}</button>

                    </form>
                    @endif
                </div>
@endsection
