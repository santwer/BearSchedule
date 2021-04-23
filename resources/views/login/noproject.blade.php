@extends('layouts.main')

@section('content')


    <b-message title="@lang('general.no_project.no_authorization')" aria-close-label="Close message">
        @lang('general.no_project.no_authorization_text')
    </b-message>

@endsection
