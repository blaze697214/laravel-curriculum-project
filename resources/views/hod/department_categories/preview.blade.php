@extends('layouts.hod')

@section('content')
    <div>
        <a href="{{ route('hod.scheme.index') }}">
            <button class="px-6 py-2 rounded-lg bg-gray-300 text-gray-800 hover:bg-gray-400 cursor-pointer">
                ← Back
            </button>
        </a>
    </div>

    @include('partials.scheme_at_glance_preview')
@endsection
