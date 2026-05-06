@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Scheme At Glance
    </h1>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

        {{-- TOP INFO --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Department --}}
            <div class="bg-green-50 rounded-xl p-5 border border-green-100">
                <p class="text-sm text-gray-500 mb-1">
                    Department
                </p>

                <h3 class="text-lg font-semibold text-green-800">
                    {{ $department->name }}
                </h3>
            </div>

            {{-- Scheme --}}
            <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                <p class="text-sm text-gray-500 mb-1">
                    Scheme
                </p>

                <h3 class="text-lg font-semibold text-blue-800">
                    {{ $scheme->name }}
                </h3>
            </div>

        </div>


        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8">

            {{-- STATUS --}}
            <div class="rounded-xl border border-gray-200 p-5">

                <p class="text-sm text-gray-500 mb-2">
                    Status
                </p>

                @if ($configured)
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                        Configured
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                        Not Configured
                    </span>
                @endif

            </div>


            {{-- CATEGORIES --}}
            <div class="rounded-xl border border-gray-200 p-5">

                <p class="text-sm text-gray-500 mb-2">
                    Categories Filled
                </p>

                <h2 class="text-3xl font-bold text-red-600">
                    {{ $categories->count() }}
                </h2>

            </div>


            {{-- EXIT COURSES --}}
            <div class="rounded-xl border border-gray-200 p-5">

                <p class="text-sm text-gray-500 mb-2">
                    Exit Courses
                </p>

                <h2 class="text-3xl font-bold text-purple-600">
                    {{ $exitCourses->count() }}
                </h2>

            </div>

        </div>


        {{-- ACTIONS --}}
        <div class="flex justify-between gap-4 mt-10">

            {{-- CREATE / EDIT --}}
            <a href="{{ route('hod.scheme.edit') }}"
                class="bg-blue-600 hover:bg-blue-700 transition text-white font-medium px-5 py-2.5 rounded-lg shadow-sm">

                {{ $configured ? 'Edit Scheme Structure' : 'Create Scheme Structure' }}

            </a>


            {{-- PREVIEW --}}
            @if ($configured)
                <div class="flex gap-5 flex-wrap">
                    <a href="{{ route('hod.scheme.preview') }}"
                    class="bg-green-600 hover:bg-green-700 transition text-white font-medium px-5 py-2.5 rounded-lg shadow-sm">

                    Preview

                </a>

                <a href="{{ route('hod.scheme.print') }}" target="_blank">
                    <button class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-2.5 rounded-lg shadow-sm font-medium">
                        Print
                    </button>
                </a>
                </div>
            @endif

        </div>

    </div>
@endsection
