@extends('layouts.cdc')

@section('content')

<div class="mb-5">
        <a href="{{ route('cdc.schemes.verify.semesters', [$scheme,$department]) }}">
            <button class="px-6 py-2 rounded-lg bg-gray-300 text-gray-800 hover:bg-gray-400 cursor-pointer">
                Back
            </button>
        </a>
    </div>

<h1 class="text-xl font-semibold mb-6">
    Semester {{ $semesterNo }} - {{ $department->name }}
</h1>

<div class="bg-white rounded-xl shadow overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm border border-gray-200">

            {{-- HEADER --}}
            <thead class="bg-gray-100 text-gray-700">

                <tr>
                    <th rowspan="2" class="border px-3 py-2">Sr</th>
                    <th rowspan="2" class="border px-3 py-2">Course Code</th>
                    <th rowspan="2" class="border px-3 py-2 text-left">Course Title</th>

                    <th colspan="3" class="border px-3 py-2">Teaching Scheme</th>
                    <th rowspan="2" class="border px-3 py-2">Credits</th>

                    <th colspan="4" class="border px-3 py-2">Examination Scheme</th>
                </tr>

                <tr>
                    <th class="border px-2 py-1">L</th>
                    <th class="border px-2 py-1">T</th>
                    <th class="border px-2 py-1">P</th>

                    <th class="border px-2 py-1">FA TH</th>
                    <th class="border px-2 py-1">SA TH</th>
                    <th class="border px-2 py-1">FA PR</th>
                    <th class="border px-2 py-1">SA PR</th>
                </tr>

            </thead>


            {{-- BODY --}}
            <tbody class="divide-y">

                @foreach($courses as $index => $c)

                @php $cm = $c->courseMaster; @endphp

                <tr class="text-center hover:bg-gray-50">

                    <td class="border px-3 py-2">{{ $index + 1 }}</td>

                    <td class="border px-3 py-2">{{ $cm->course_code }}</td>

                    <td class="border px-3 py-2 text-left">
                        {{ $cm->title }}
                    </td>

                    <td class="border px-3 py-2">{{ $cm->lecture_hours }}</td>
                    <td class="border px-3 py-2">{{ $cm->tutorial_hours }}</td>
                    <td class="border px-3 py-2">{{ $cm->practical_hours }}</td>

                    <td class="border px-3 py-2 font-medium">{{ $cm->credits }}</td>

                    <td class="border px-3 py-2">{{ $cm->fa_th }}</td>
                    <td class="border px-3 py-2">{{ $cm->sa_th }}</td>
                    <td class="border px-3 py-2">{{ $cm->fa_pr }}</td>
                    <td class="border px-3 py-2">{{ $cm->sa_pr }}</td>

                </tr>

                @endforeach


                {{-- TOTAL ROW --}}
                <tr class="bg-gray-50 font-semibold text-center">

                    <td colspan="3" class="border px-3 py-2 text-left">
                        Total
                    </td>

                    <td class="border px-3 py-2">{{ $totals['th_hours'] }}</td>
                    <td class="border px-3 py-2"></td>
                    <td class="border px-3 py-2">{{ $totals['pr_hours'] }}</td>

                    <td class="border px-3 py-2">{{ $totals['credits'] }}</td>

                    <td colspan="4" class="border px-3 py-2">
                        {{ $totals['marks'] }}
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>


{{-- ================= NOTES ================= --}}
<div class="mt-6 bg-white p-5 rounded-xl shadow">

    <h2 class="text-lg font-semibold mb-2">
        Notes / Legends
    </h2>

    <p class="text-gray-600 text-sm">
        (To be configured)
    </p>

</div>

@endsection