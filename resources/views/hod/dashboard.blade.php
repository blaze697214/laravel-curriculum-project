@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Department: {{ auth()->user()->department->name }}
    </h1>
@endsection
