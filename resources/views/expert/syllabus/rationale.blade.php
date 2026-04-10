@extends('layouts.syllabus')

@section('content')
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        Rationale
    </h3>


    <div class="bg-white p-6 rounded-xl shadow">

        <form method="POST" action="{{ route('expert.syllabus.rationale.save', $course->id) }}">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">


            <div class="mb-4">

                <label class="block text-sm text-gray-600 mb-2">
                    Enter Rationale
                </label>

                <textarea name="rationale" rows="10"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('rationale', $syllabus->rationale ?? '') }}</textarea>

            </div>


            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                Save
            </button>

        </form>

    </div>
@endsection
