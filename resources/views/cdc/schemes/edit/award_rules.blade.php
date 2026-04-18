@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Class Award Rules - {{ $scheme->name }}
    </h1>



    <div class="bg-white p-6 rounded-xl shadow ">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Define Class Award Configuration
        </h2>

        <form method="POST" action="{{ route('cdc.schemes.edit.award.update', $scheme->id) }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div class="flex justify-between gap-x-5">
                <div class="basis-1/2">
                    <label class="block text-sm text-gray-600 mb-1">
                        Total Subjects Required
                    </label>

                    <input type="number" min="0" name="total_subjects"
                        value="{{ old('total_subjects', $rule->total_subjects_required ?? '') }}"
                        {{ $scheme->is_locked ? 'disabled' : '' }}
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none disabled:bg-gray-100 disabled:cursor-not-allowed">
                </div>


                <div class="basis-1/2">
                    <label class="block text-sm text-gray-600 mb-1">
                        Total Marks Required
                    </label>

                    <input type="number" min="0" name="total_marks"
                        value="{{ old('total_marks', $rule->total_marks_required ?? '') }}"
                        {{ $scheme->is_locked ? 'disabled' : '' }}
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none disabled:bg-gray-100 disabled:cursor-not-allowed">
                </div>
            </div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Update Scheme
            </button>
        </form>

        <div class="flex justify-between gap-4 pt-4">

            {{-- BACK --}}
            <a href="{{ route('cdc.schemes.edit.categories', $scheme->id) }}">
                <button class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg transition w-40">
                ← Back
            </button>
            </a>
            <form method="POST" action="{{ route('cdc.schemes.edit.award.next',$scheme->id) }}">
                @csrf
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg transition w-40">
                    Next →
                </button>
            </form>

        </div>


    </div>
@endsection
