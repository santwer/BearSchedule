@extends('layouts.main')
@section('content')

    <div style="margin: 30px;">
        <div class="columns">

            <div class="column">
                <h2 class="subtitle">Account</h2>
                @if($isDisabled)
                    <b-message title="Microsoft Account" type="is-info" aria-close-label="Close message">
                        Some of your Informations are not ediable, because they got synchronized with the Microsoft Cloud Service.
                    </b-message>
                @endif
                <b-field label="Name">
                    <b-input value="{{ $user->name }}" @if($isDisabled) disabled @endif></b-input>
                </b-field>
                <b-field label="E-Mail">
                    <b-input type="email"
                             maxlength="30"
                             value="{{ $user->email }}"  @if($isDisabled) disabled @endif></b-input>
                </b-field>

            </div>
            <div class="column">

            </div>
        </div>
    </div>


@endsection
