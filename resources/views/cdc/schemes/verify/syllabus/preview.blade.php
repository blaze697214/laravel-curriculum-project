@extends('layouts.cdc')

@section('content')
    <div class="mb-5">
        <a href="{{ route('cdc.schemes.syllabus') }}">
            <button class="px-6 py-2 rounded-lg bg-gray-300 text-gray-800 hover:bg-gray-400 cursor-pointer">
                Back
            </button>
        </a>
    </div>

    @include('partials.syllabus_preview')
@endsection
