@extends('layouts.login')
@section('title', 'Register')
@section('subtitle', 'Please fill to proceed.')
@section('content')

                    <form method="POST" action="{{ locale_route('register') }}">
                        @csrf


                        <div class="field">
                            <div class="control">
                                <b-field @error('name') label="Error"
                                         type="is-danger"
                                         message="{{ $message }}" @enderror>
                                    <b-input placeholder="{{ __('Your Name') }}"
                                             type="text"
                                             name="name"
                                             autocomplete="name"
                                             value="{{ old('name') }}"
                                             icon="account">
                                    </b-input>
                                </b-field>
                            </div>
                        </div>

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
                                    <b-input placeholder="{{ __('Password') }}"
                                             type="password"
                                             name="password"
                                             icon="key">
                                    </b-input>
                                </b-field>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <b-field @error('password_confirmation') label="Error"
                                         type="is-danger"
                                         message="{{ $message }}" @enderror>
                                    <b-input placeholder="{{ __('Confirm Password') }}"
                                             type="password"
                                             name="password_confirmation"
                                             autocomplete="new-password"
                                             icon="key">
                                    </b-input>
                                </b-field>
                            </div>
                        </div>
                        <button class="button is-block is-primary is-large is-fullwidth" type="submit">{{ __('Register') }}</button>

                    </form>

@endsection
