@extends('layouts.main')
@section('content')
    <form method="POST" action="{{ route('user.settings.save') }}">
        @csrf
        <div style="margin: 30px;">
            <div class="columns">

                <div class="column">
                    <h2 class="subtitle">Account</h2>
                    @if ($errors->any())
                        <b-message title="Error" type="is-danger" aria-close-label="Close message">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </b-message>
                    @endif
                    @if($isDisabled)
                        <b-message title="Microsoft Account" type="is-info" aria-close-label="Close message">
                            Some of your Informations are not ediable, because they got synchronized with the Microsoft
                            Cloud Service.
                        </b-message>
                    @endif
                    <b-field label="Name">
                        <b-input value="{{ $user->name }}"  name="name"
                                 @error('name')
                                 type="is-danger"
                                 message="{{ $message }}" @enderror
                                 @if($isDisabled) disabled @endif></b-input>
                    </b-field>
                    <b-field label="E-Mail">
                        <b-input type="email"
                                 name="email"
                                 maxlength="30"
                                 @error('email')
                                 type="is-danger"
                                 message="{{ $message }}" @enderror
                                 value="{{ $user->email }}" @if($isDisabled) disabled @endif></b-input>
                    </b-field>

                    @if(!$isDisabled)
                        <b-field label="New Password" @error('password')
                        type="is-danger"
                                 message="{{ $message }}" @enderror>
                            <b-input placeholder="{{ __('New Password') }}"
                                     type="password"
                                     name="password"
                                     icon="key">
                            </b-input>
                        </b-field>
                        <b-field @error('password_confirmation')
                                 type="is-danger"
                                 message="{{ $message }}" @enderror>
                            <b-input placeholder="{{ __('New Password') }}"
                                     type="password"
                                     name="password_confirmation"
                                     icon="key">
                            </b-input>
                        </b-field>
                    @endif
                </div>

                <div class="column">
                    <b-field label="Account Create Date">
                        <b-input value="{{ $user->created_at }}" disabled></b-input>
                    </b-field>


                    <b-button type="is-danger" @click="deleteAccount" outlined>Delete Account</b-button>

                </div>
            </div>
        </div>
        <b-button native-type="submit" type="is-primary"  @if($isDisabled) disabled @endif>Save</b-button>
    </form>
    <form method="post" id="delete_account" action="{{ route('user.settings.delete') }}">
        @csrf
    </form>
@endsection
