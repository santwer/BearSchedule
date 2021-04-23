@extends('layouts.login')
@section('title', __('general.privacy_policy'))
@section('subtitle', __('general.privacy_policy'))
@section('heroboxclass', 'is-10 is-offset-1')
@section('content')
    <div style="text-align: left">
        @if(file_exists(storage_path('app/privacy.txt')))
            {!! file_get_contents(storage_path('app/privacy.txt')) !!}
        @endif
    </div>
@endsection
