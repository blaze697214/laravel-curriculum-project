@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Class Award Rules - {{ $scheme->name }}
    </h1>

    <div class="bg-white p-6 rounded-xl shadow ">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Define Class Award Configuration
            </h2>

        <form method="POST" action="{{ route('cdc.schemes.award.store', $scheme->id) }}" class="space-y-5">
            @csrf



            <div class="flex justify-between gap-x-5">
                <div class="basis-1/2">
                <label class="block text-sm text-gray-600 mb-1">
                    Total Subjects Required
                </label>

                <input type="number" name="total_subjects"
                    value="{{ old('total_subjects', $rule->total_subjects_required ?? '') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>


            <div class="basis-1/2">
                <label class="block text-sm text-gray-600 mb-1">
                    Total Marks Required
                </label>

                <input type="number" name="total_marks" value="{{ old('total_marks', $rule->total_marks_required ?? '') }}"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            </div>


            <div class="flex gap-4 pt-4">

                <a href="{{ route('cdc.schemes.categories.create', $scheme->id) }}">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Back
                    </button>
                </a>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                    Finish
                </button>

            </div>

        </form>

    </div>
@endsection
