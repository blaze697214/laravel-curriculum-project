@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        CDC Dashboard
    </h1>


    <!-- Welcome Section -->
    <div class="mb-8 bg-white p-6 rounded-xl shadow">

        <h2 class="text-xl font-semibold text-gray-800">
            Welcome, {{ auth()->user()->name }}
        </h2>

        <p class="text-gray-600 mt-1">
            Role: {{ strtoupper(auth()->user()->roles->first()->name) }}
        </p>

    </div>
@endsection
