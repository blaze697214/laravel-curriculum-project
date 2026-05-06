@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Course Management
    </h1>


    <div class="bg-white p-6 rounded-xl shadow w-full">
        <div class="overflow-x-auto w-full">

    <table class="w-full border">

        <thead class="bg-gray-100">

            <tr>

                <th class="border p-2">
                    Category
                </th>

                <th class="border p-2">
                    Courses
                </th>

                <th class="border p-2">
                    Credits
                </th>

                <th class="border p-2">
                    Marks
                </th>

                <th class="border p-2">
                    Hours
                </th>

                <th class="border p-2">
                    Status
                </th>

            </tr>

        </thead>

        <tbody>

            @foreach($validation['categories'] as $row)

                <tr>

                    <td class="border p-2">

                        {{ $row['category']->courseCategory->code }}

                    </td>

                    <td class="border p-2">

                        {{ $row['actual']['courses'] }}
                        /
                        {{ $row['target']['courses'] }}

                    </td>

                    <td class="border p-2">

                        {{ $row['actual']['credits'] }}
                        /
                        {{ $row['target']['credits'] }}

                    </td>

                    <td class="border p-2">

                        {{ $row['actual']['marks'] }}
                        /
                        {{ $row['target']['marks'] }}

                    </td>

                    <td class="border p-2">

                        {{ $row['actual']['hours'] }}
                        /
                        {{ $row['target']['hours'] }}

                    </td>

                    <td class="border p-2 text-center">

                        @if($row['is_valid'])

                            <span class="text-green-600 font-bold">
                                ✔
                            </span>

                        @else

                            <span class="text-red-600 font-bold">
                                ✘
                            </span>

                        @endif

                    </td>

                </tr>

            @endforeach

            {{-- OVERALL --}}
            <tr class="bg-gray-100 font-bold">

                <td class="border p-2">
                    Overall
                </td>

                <td class="border p-2">

                    {{ $validation['overall']['actual']['courses'] }}
                    /
                    {{ $validation['overall']['target']['courses'] }}

                </td>

                <td class="border p-2">

                    {{ $validation['overall']['actual']['credits'] }}
                    /
                    {{ $validation['overall']['target']['credits'] }}

                </td>

                <td class="border p-2">

                    {{ $validation['overall']['actual']['marks'] }}
                    /
                    {{ $validation['overall']['target']['marks'] }}

                </td>

                <td class="border p-2">

                    {{ $validation['overall']['actual']['hours'] }}
                    /
                    {{ $validation['overall']['target']['hours'] }}

                </td>

                <td class="border p-2 text-center">

                    @if($validation['overall']['is_valid'])

                        <span class="text-green-600 font-bold">
                            ✔
                        </span>

                    @else

                        <span class="text-red-600 font-bold">
                            ✘
                        </span>

                    @endif

                </td>

            </tr>

        </tbody>

    </table>

</div>
    </div>
    <div class="mt-5 bg-white p-6 rounded-xl shadow w-full h-full">
        <div class="flex justify-between mb-5">
            <a href="{{ route('hod.courses.view') }}">
                <button class="rounded-lg px-4 py-2 bg-gray-200 cursor-pointer text-gray-800 font-semibold flex gap-1 items-center">
                    <svg class="fill-green-200 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>

                    View Courses
                </button>
            </a>
            <a href="{{ route('hod.courses.create') }}">
                <button class="rounded-lg px-4 py-2 bg-gray-200 cursor-pointer text-gray-800 font-semibold flex gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Create Course
                </button>
            </a>
        </div>
        <div class="bg-white p-6 rounded-xl shadow w-full">
            <div class="flex flex-col items-center gap-4">

    <div class="font-semibold text-gray-700 text-md">

        Current Status:

        @if($submission->status == 'submitted')

            <span class="text-green-600">
                Submitted to CDC
            </span>

        @else

            <span class="text-red-600">
                Not Submitted
            </span>

        @endif

    </div>

    {{-- SUBMIT --}}
    {{-- @if(
        $submission->status != 'submitted'
        && $allValid
    ) --}}

        <form method="POST"
              action="{{ route('hod.courses.submit') }}"
              onsubmit="return confirm(
                'Submit all department courses to CDC for course code allocation?'
              )">
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

            @csrf

            <button
                type="submit"
                class="bg-green-600 text-white px-5 py-3 rounded-lg">

                Submit to CDC

            </button>

        </form>

    {{-- @endif --}}

    {{-- UNSUBMIT --}}
    @if($submission->status == 'submitted')

        <form method="POST"
              action="{{ route('hod.courses.unsubmit') }}">
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

            @csrf

            <button
                type="submit"
                class="bg-red-600 text-white px-5 py-2 rounded-lg">

                Mark as Not Submitted

            </button>

        </form>

    @endif

    {{-- INVALID --}}
    {{-- @if(!$allValid) --}}

        <div class="text-red-500 text-sm font-medium">

            All validations must be correct before submission.

        </div>

    {{-- @endif --}}

</div>
        </div>
    </div>
@endsection
