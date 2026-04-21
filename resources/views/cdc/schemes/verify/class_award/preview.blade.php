@extends('layouts.cdc')

@section('content')

<div class="mb-5">
        <a href="{{ route('cdc.schemes.verify.department.detail', [$scheme,$department]) }}">
            <button class="px-6 py-2 rounded-lg bg-gray-300 text-gray-800 hover:bg-gray-400 cursor-pointer">
                Back
            </button>
        </a>
    </div>

<h2 class="text-center text-lg font-semibold tracking-wide">
    PROGRAMME - {{ strtoupper($department->name) }}
</h2>

<h3 class="text-center text-base font-medium mb-6">
    Courses for Award of Class
</h3>


<div class="bg-white rounded-xl shadow overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-xs border border-gray-300">

            {{-- ================= HEADER ================= --}}
            <thead class="bg-gray-100 text-gray-700 text-center">

            <tr>
                <th rowspan="4" class="border px-2 py-1">Sr No</th>
                <th rowspan="4" class="border px-2 py-1 text-left">Course Title</th>
                <th rowspan="4" class="border px-2 py-1">Abbrev</th>
                <th rowspan="4" class="border px-2 py-1">Course Type</th>
                <th rowspan="4" class="border px-2 py-1">Course Code</th>
                <th rowspan="4" class="border px-2 py-1">Total IKS Hrs</th>

                <th colspan="5" class="border px-2 py-1">Learning Scheme</th>

                <th rowspan="4" class="border px-2 py-1">Credits</th>
                <th rowspan="4" class="border px-2 py-1">Paper Duration</th>

                <th colspan="10" class="border px-2 py-1">Assessment Scheme</th>

                <th rowspan="4" class="border px-2 py-1">Total Marks</th>
            </tr>

            <tr>
                <th colspan="3" class="border px-2 py-1">Actual Contact Hrs</th>
                <th rowspan="3" class="border px-2 py-1">Self Learning</th>
                <th rowspan="3" class="border px-2 py-1">Total Hrs</th>

                <th colspan="3" class="border px-2 py-1">Theory</th>
                <th colspan="4" class="border px-2 py-1">Practical</th>
                <th colspan="2" class="border px-2 py-1">SLA</th>
            </tr>

            <tr>
                <th class="border px-1 py-1">CL</th>
                <th class="border px-1 py-1">TL</th>
                <th class="border px-1 py-1">LL</th>

                <th class="border px-1 py-1">FA-TH</th>
                <th class="border px-1 py-1">SA-TH</th>
                <th class="border px-1 py-1">Total</th>

                <th class="border px-1 py-1">FA-PR</th>
                <th class="border px-1 py-1">SA-PR</th>
                <th colspan="2" class="border px-1 py-1"></th>

                <th class="border px-1 py-1">Max</th>
                <th class="border px-1 py-1">Min</th>
            </tr>

            <tr>
                <th class="border px-1 py-1"></th>
                <th class="border px-1 py-1"></th>
                <th class="border px-1 py-1"></th>

                <th class="border px-1 py-1">Max</th>
                <th class="border px-1 py-1">Max</th>
                <th class="border px-1 py-1">Max</th>

                <th class="border px-1 py-1">Max</th>
                <th class="border px-1 py-1">Min</th>
                <th class="border px-1 py-1">Max</th>
                <th class="border px-1 py-1">Min</th>

                <th class="border px-1 py-1">Max</th>
                <th class="border px-1 py-1">Min</th>
            </tr>

            </thead>


            {{-- ================= BODY ================= --}}
            <tbody class="text-center divide-y">

            @php $i = 1; @endphp

            @foreach($compulsory as $c)

            @php $cm = $c->courseMaster; @endphp

            <tr class="hover:bg-gray-50">

                <td class="border px-2 py-1">{{ $i++ }}</td>

                <td class="border px-2 py-1 text-left">{{ $cm->title }}</td>
                <td class="border px-2 py-1">{{ $cm->abbreviation }}</td>
                <td class="border px-2 py-1">{{ $cm->course_type }}</td>
                <td class="border px-2 py-1">{{ $cm->course_code }}</td>
                <td class="border px-2 py-1">0</td>

                <td class="border px-2 py-1">{{ $cm->cl }}</td>
                <td class="border px-2 py-1">{{ $cm->tl }}</td>
                <td class="border px-2 py-1">{{ $cm->ll }}</td>

                <td class="border px-2 py-1">{{ $cm->sla_hours ?? '-' }}</td>
                <td class="border px-2 py-1">{{ ($cm->cl + $cm->tl + $cm->ll) }}</td>

                <td class="border px-2 py-1 font-medium">{{ $cm->credits }}</td>
                <td class="border px-2 py-1">{{ $cm->paper_duration }}</td>

                <td class="border px-2 py-1">{{ $cm->fa_th }}</td>
                <td class="border px-2 py-1">{{ $cm->sa_th }}</td>
                <td class="border px-2 py-1">{{ $cm->th_total }}</td>

                <td class="border px-2 py-1">{{ $cm->fa_pr }}</td>
                <td class="border px-2 py-1">{{ $cm->fa_pr_min }}</td>
                <td class="border px-2 py-1">{{ $cm->sa_pr }}</td>
                <td class="border px-2 py-1">{{ $cm->sa_pr_min }}</td>

                <td class="border px-2 py-1">{{ $cm->sla_marks }}</td>
                <td class="border px-2 py-1">{{ $cm->sla_min }}</td>

                <td class="border px-2 py-1 font-semibold">{{ $cm->total_marks }}</td>

            </tr>

            @endforeach


            {{-- ================= ELECTIVES ================= --}}
            @if($electives->count())

            <tr class="bg-gray-50 font-semibold">
                <td colspan="25" class="border px-3 py-2 text-center">
                    Elective (Any One)
                </td>
            </tr>

            @foreach($electives as $c)

            @php $cm = $c->courseMaster; @endphp

            <tr class="hover:bg-gray-50">

                <td class="border px-2 py-1"></td>

                <td class="border px-2 py-1 text-left">{{ $cm->title }}</td>
                <td class="border px-2 py-1">{{ $cm->abbreviation }}</td>
                <td class="border px-2 py-1">{{ $cm->course_type }}</td>
                <td class="border px-2 py-1">{{ $cm->course_code }}</td>
                <td class="border px-2 py-1">0</td>

                <td class="border px-2 py-1">{{ $cm->cl }}</td>
                <td class="border px-2 py-1">{{ $cm->tl }}</td>
                <td class="border px-2 py-1">{{ $cm->ll }}</td>

                <td class="border px-2 py-1">-</td>
                <td class="border px-2 py-1">{{ ($cm->cl + $cm->tl + $cm->ll) }}</td>

                <td class="border px-2 py-1 font-medium">{{ $cm->credits }}</td>
                <td class="border px-2 py-1">{{ $cm->paper_duration }}</td>

                <td class="border px-2 py-1">{{ $cm->fa_th }}</td>
                <td class="border px-2 py-1">{{ $cm->sa_th }}</td>
                <td class="border px-2 py-1">{{ $cm->th_total }}</td>

                <td class="border px-2 py-1">{{ $cm->fa_pr }}</td>
                <td class="border px-2 py-1">{{ $cm->fa_pr_min }}</td>
                <td class="border px-2 py-1">{{ $cm->sa_pr }}</td>
                <td class="border px-2 py-1">{{ $cm->sa_pr_min }}</td>

                <td class="border px-2 py-1">{{ $cm->sla_marks }}</td>
                <td class="border px-2 py-1">{{ $cm->sla_min }}</td>

                <td class="border px-2 py-1 font-semibold">{{ $cm->total_marks }}</td>

            </tr>

            @endforeach

            @endif

            </tbody>

        </table>

    </div>

</div>

@endsection