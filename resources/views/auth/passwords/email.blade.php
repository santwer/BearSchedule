@extends('layouts.login')
@section('title', __('Forgot Password'))
@section('subtitle', 'Please fill to proceed.')
@section('content')
                <div class="card-body">
                    @if (session('status'))
                        <b-message title="Success" type="is-success" aria-close-label="Close message">
                            {{ session('status') }}
                        </b-message>
                    @else
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
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
                        <button class="button is-block is-primary is-large is-fullwidth" type="submit">{{ __('Send Password Reset Link') }}</button>

                    </form>
                    @endif
                </div>
@endsection
