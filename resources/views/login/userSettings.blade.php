@extends('layouts.main')
@section('content')
    <form method="POST" action="{{ locale_route('user.settings.save') }}">
        @csrf
        <div style="margin: 30px;">
            <div class="columns">

                <div class="column">
                    <h2 class="subtitle">@lang('settings.account')</h2>
                    @if ($errors->any())
                        <b-message title="@lang('settings.error')" type="is-danger" aria-close-label="Close message">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </b-message>
                    @endif
                    @if($isDisabled)
                        <b-message title="@lang('settings.microsoft_account')" type="is-info" aria-close-label="Close message">
                            @lang('settings.microsoft_account_service_info')
                        </b-message>
                    @endif
                    <b-field label="@lang('settings.name')">
                        <b-input value="{{ $user->name }}"  name="name"
                                 @error('name')
                                 type="is-danger"
                                 message="{{ $message }}" @enderror
                                 @if($isDisabled) disabled @endif></b-input>
                    </b-field>
                    <b-field label="@lang('general.email_address')">
                        <b-input type="email"
                                 name="email"
                                 maxlength="30"
                                 @error('email')
                                 type="is-danger"
                                 message="{{ $message }}" @enderror
                                 value="{{ $user->email }}" @if($isDisabled) disabled @endif></b-input>
                    </b-field>

                    @if(!$isDisabled)
                        <b-field label="@lang('auth.password_new')" @error('password')
                        type="is-danger"
                                 message="{{ $message }}" @enderror>
                            <b-input placeholder="{{ __('auth.password_new') }}"
                                     type="password"
                                     name="password"
                                     autocomplete="new-password"
                                     icon="key">
                            </b-input>
                        </b-field>
                        <b-field @error('password_confirmation')
                                 type="is-danger"
                                 message="{{ $message }}" @enderror>
                            <b-input placeholder="{{ __('auth.password_confirm') }}"
                                     type="password"
                                     name="password_confirmation"
                                     autocomplete="new-password"
                                     icon="key">
                            </b-input>
                        </b-field>
                    @endif
                </div>

                <div class="column">
                    <img src="{{ auth()->user()->avatarUrl }}" />
                    <b-field label="@lang('settings.account_create_date')">
                        <b-input value="{{ $user->created_at->format(localeDateFormat(true)) }}" disabled></b-input>
                    </b-field>


                    <b-button type="is-danger" @click="deleteAccount" outlined>@lang('settings.delete_account')</b-button>

                </div>
            </div>
        </div>
        <b-button native-type="submit" type="is-primary"  @if($isDisabled) disabled @endif>@lang('general.save')</b-button>
    </form>
    <form method="post" id="delete_account" action="{{ locale_route('user.settings.delete') }}">
        @csrf
    </form>
@endsection
