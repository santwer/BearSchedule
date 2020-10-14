@extends('layouts.login')
@section('title', 'Disclaimer')
@section('subtitle', 'Disclaimer')
@section('heroboxclass', 'is-10 is-offset-1')
@section('content')
    <div style="text-align: left">
        @if(file_exists(storage_path('app/disclaimer.txt')))
            {!! file_get_contents(storage_path('app/disclaimer.txt')) !!}
        @endif
    </div>
@endsection
