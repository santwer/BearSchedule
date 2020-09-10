@extends('layouts.main')

@section('content')

    <b-tabs position="is-right" class="block">
        <b-tab-item label="Timeline">

                <students-timeline project="{{$project}}"></students-timeline>

        </b-tab-item>

        <b-tab-item label="Settings">
            Lorem <br>
            ipsum <br>
            dolor <br>
            sit <br>
            amet.
        </b-tab-item>
    </b-tabs>


@endsection
