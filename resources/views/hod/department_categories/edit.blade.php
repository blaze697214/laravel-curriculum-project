@extends('layouts.hod')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">
    Scheme At Glance
</h1>

<form method="POST" action="{{ route('hod.scheme.update') }}">

    @csrf
                <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

    {{-- MAIN CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">

            <h2 class="text-lg font-semibold text-gray-800">
                Course Category Configuration
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Configure course distribution, credits and marks structure.
            </p>

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-700">

                    <tr>

                        <th class="px-4 py-3 text-left font-semibold border-b border-gray-200">
                            Course Type
                        </th>

                        <th class="px-4 py-3 text-left font-semibold border-b border-gray-200">
                            Name
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            Offered
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            Required
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            TH
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            TU
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            PR
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            Total Hours
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            Credits
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            Marks
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @php
                        $totalCredits = 0;
                        $totalMarks = 0;
                    @endphp

                    @foreach($courseCategories as $category)

                        @php

                            $existing = $departmentCategories[$category->id] ?? null;

                            $row = old(
                                "categories.$category->id",
                                [
                                    'total_offered' => $existing->total_offered ?? '',
                                    'to_be_completed' => $existing->to_be_completed ?? '',
                                    'th' => $existing->th ?? '',
                                    'tu' => $existing->tu ?? '',
                                    'pr' => $existing->pr ?? '',
                                    'total_hours' => $existing->total_hours ?? '',
                                    'total_credits' => $existing->total_credits ?? '',
                                    'total_marks' => $existing->total_marks ?? '',
                                ]
                            );

                            $totalCredits += (int) ($row['total_credits'] ?? 0);
                            $totalMarks += (int) ($row['total_marks'] ?? 0);

                        @endphp

                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-4 py-3 font-semibold text-gray-700 whitespace-nowrap">
                                {{ $category->abbreviation }}
                            </td>

                            <td class="px-4 py-3 min-w-55">

                                <p class="w-full  px-3 py-2  outline-none">{{ $category->name }}</p>

                            </td>

                            <td class="px-3 py-3">

                                <input type="number"
                                       name="categories[{{ $category->id }}][total_offered]"
                                       value="{{ $row['total_offered'] }}"
                                       class="w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">

                            </td>

                            <td class="px-3 py-3">

                                <input type="number"
                                       name="categories[{{ $category->id }}][to_be_completed]"
                                       value="{{ $row['to_be_completed'] }}"
                                       class="w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">

                            </td>

                            <td class="px-3 py-3">

                                <input type="number"
                                       name="categories[{{ $category->id }}][th]"
                                       value="{{ $row['th'] }}"
                                       class="w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">

                            </td>

                            <td class="px-3 py-3">

                                <input type="number"
                                       name="categories[{{ $category->id }}][tu]"
                                       value="{{ $row['tu'] }}"
                                       class="w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">

                            </td>

                            <td class="px-3 py-3">

                                <input type="number"
                                       name="categories[{{ $category->id }}][pr]"
                                       value="{{ $row['pr'] }}"
                                       class="w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">

                            </td>

                            <td class="px-3 py-3">

                                <input type="number"
                                       name="categories[{{ $category->id }}][total_hours]"
                                       value="{{ $row['total_hours'] }}"
                                       class="w-24 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">

                            </td>

                            <td class="px-3 py-3">

                                <input type="number"
                                       name="categories[{{ $category->id }}][total_credits]"
                                       value="{{ $row['total_credits'] }}"
                                       class="w-24 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">

                            </td>

                            <td class="px-3 py-3">

                                <input type="number"
                                       name="categories[{{ $category->id }}][total_marks]"
                                       value="{{ $row['total_marks'] }}"
                                       class="w-24 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">

                            </td>

                        </tr>

                    @endforeach

                </tbody>


                {{-- TOTAL --}}
                <tfoot class="bg-gray-50 border-t border-gray-200">

                    <tr>

                        <th colspan="8"
                            class="px-4 py-4 text-right text-base font-bold text-gray-700">

                            Total

                        </th>

                        <th class="px-4 py-4 text-center text-base font-bold text-blue-700">
                            {{ $totalCredits }}
                        </th>

                        <th class="px-4 py-4 text-center text-base font-bold text-green-700">
                            {{ $totalMarks }}
                        </th>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>


    {{-- EXIT COURSES --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mt-8 overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">

            <h2 class="text-lg font-semibold text-gray-800">
                Exit Courses
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Configure certification exits and completion requirements.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-700">

                    <tr>

                        <th class="px-4 py-3 text-left font-semibold border-b border-gray-200">
                            Exit Name
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            Offered
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            Required
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            TH
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            TU
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            PR
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            Total Hours
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            Credits
                        </th>

                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">
                            Marks
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @for($i = 0; $i < 3; $i++)

                        @php

                            $exit = old(
                                "exit_courses.$i",
                                [
                                    'exit_name' =>
                                        $exitCourses[$i]->exit_name ?? '',

                                    'total_credits' =>
                                        $exitCourses[$i]->total_credits ?? '',

                                    'total_marks' =>
                                        $exitCourses[$i]->total_marks ?? '',
                                ]
                            );

                        @endphp

                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-4 py-3">

                                <input type="text"
                                       name="exit_courses[{{ $i }}][exit_name]"
                                       value="{{ $exit['exit_name'] }}"
                                       class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">

                            </td>

                            <td class="px-4 py-3 text-center">

                                <input type="number"
                                       name="exit_courses[{{ $i }}][total_credits]"
                                       value="{{ $exit['total_credits'] }}"
                                       class="w-28 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">

                            </td>

                            <td class="px-4 py-3 text-center">

                                <input type="number"
                                       name="exit_courses[{{ $i }}][total_marks]"
                                       value="{{ $exit['total_marks'] }}"
                                       class="w-28 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">

                            </td>

                        </tr>

                    @endfor

                </tbody>

            </table>

        </div>

    </div>


    {{-- ERRORS --}}
    @error('credits')

        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-600 text-sm">
            {{ $message }}
        </div>

    @enderror

    @error('marks')

        <div class="mt-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-600 text-sm">
            {{ $message }}
        </div>

    @enderror


    {{-- ACTION --}}
    <div class="mt-8 flex justify-end">

        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 transition text-white font-medium px-6 py-3 rounded-lg shadow-sm">

            Save Scheme Configuration

        </button>

    </div>

</form>

@endsection