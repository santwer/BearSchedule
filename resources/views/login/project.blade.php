@extends('layouts.main')

@section('content')

    <b-tabs position="is-right" class="block">
        <b-tab-item label="Timeline">

                <students-timeline project="{{$project}}"></students-timeline>

        </b-tab-item>
        <b-tab-item label="Items">
            <b-table :data="{{ $items }}" :columns="itemColumns"></b-table>

        </b-tab-item>
        <b-tab-item label="Groups">
            <b-table :data="{{ $groups }}" :columns="groupsColumns"></b-table>

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
