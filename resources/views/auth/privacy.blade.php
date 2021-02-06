@extends('layouts.login')
@section('title', 'Privacy Policy')
@section('subtitle', 'Privacy Policy')
@section('heroboxclass', 'is-10 is-offset-1')
@section('content')
    <div style="text-align: left">
        @if(file_exists(storage_path('app/privacy.txt')))
            {!! file_get_contents(storage_path('app/privacy.txt')) !!}
        @endif
    </div>
@endsection
