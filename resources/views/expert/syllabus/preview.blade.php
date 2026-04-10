@extends('layouts.syllabus')

@section('content')
    {{-- ================= BACK BUTTON ================= --}}
    <div class="mb-6">
        <a href="{{ route('expert.dashboard') }}">
            <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                ← Back to Dashboard
            </button>
        </a>
    </div>


    {{-- ================= HEADER ================= --}}
    <div class="mb-8">

        <div class="flex justify-between items-start">

            <div class="space-y-2 text-gray-700">

                <p>
                    <span class="font-semibold">PROGRAMME :</span>
                    Diploma Programme in {{ $programmes }}
                </p>

                <p>
                    <span class="font-semibold">COURSE :</span>
                    {{ $course->title }}
                </p>

            </div>

            <div class="text-sm text-gray-600">
                <span class="font-semibold">COURSE CODE :</span>
                {{ $course->code ?? '---' }}
            </div>

        </div>

    </div>



    {{-- ================= TABLE ================= --}}
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        LEARNING AND ASSESSMENT SCHEME
    </h3>

    <div class="overflow-x-auto mb-6">

        <table class="w-full text-sm table-fixed text-center border border-gray-200">
            <colgroup>
                <col style="width:3.5%"> {{-- CL --}}
                <col style="width:3.5%"> {{-- TL --}}
                <col style="width:3.5%"> {{-- LL --}}
                <col style="width:4%"> {{-- SLH --}}
                <col style="width:4%"> {{-- NLH --}}
                <col style="width:6%"> {{-- Credits --}}
                <col style="width:6%"> {{-- Paper Duration --}}
                <col style="width:6%"> {{-- FA-TH --}}
                <col style="width:6%"> {{-- SA-TH --}}
                <col style="width:5%"> {{-- Total MAX --}}
                <col style="width:5%"> {{-- Total MIN --}}
                <col style="width:5%"> {{-- FA-PR MAX --}}
                <col style="width:5%"> {{-- FA-PR MIN --}}
                <col style="width:5%"> {{-- SA-PR MAX --}}
                <col style="width:5%"> {{-- SA-PR MIN --}}
                <col style="width:5%"> {{-- SLA MAX --}}
                <col style="width:5%"> {{-- SLA MIN --}}
                <col style="width:5%"> {{-- Total Marks --}}
            </colgroup>

            <thead class="bg-gray-100 text-gray-700">

                <tr>
                    <th colspan="5" class="border px-3 py-1">Learning Scheme</th>
                    <th rowspan="4" class="border px-3 py-2">Credits</th>
                    <th colspan="11" class="border px-3 py-1">Assessment Scheme</th>
                    <th rowspan="4" class="border px-3 py-2">Total Marks</th>
                </tr>

                <tr>
                    <th colspan="3" rowspan="2" class="border px-3 py-2">Actual Contact Hrs./Week</th>
                    <th rowspan="3" class="border px-3 py-2">SLH</th>
                    <th rowspan="3" class="border px-3 py-2">NLH</th>
                    <th rowspan="3" class="border px-3 py-2">Paper Duration</th>
                    <th colspan="4" rowspan="2" class="border px-3 py-2">Theory</th>
                    <th colspan="4" class="border px-3 py-1">Based on LL and TSL</th>
                    <th rowspan="2" colspan="2" class="border px-3 py-2">Based on self Learning</th>
                </tr>
                <tr>
                    <th colspan="4" class="border px-3 py-2">Practical</th>
                </tr>

                <tr>
                    <th class="border px-3 py-2">CL</th>
                    <th class="border px-3 py-2">TL</th>
                    <th class="border px-3 py-2">LL</th>

                    <th class="border px-3 py-2">FA-TH</th>
                    <th class="border px-3 py-2">SA-TH</th>
                    <th colspan="2" class="border px-3 py-2">Total</th>

                    <th colspan="2" class="border px-3 py-2">FA-PR</th>
                    <th colspan="2" class="border px-3 py-2">SA-PR</th>
                    <th colspan="2" class="border px-3 py-2">SLA</th>
                </tr>

            </thead>


            <tbody class="divide-y">

                <tr class="hover:bg-gray-50 text-md">

                    {{-- Learning --}}
                    <td rowspan="2" class="border px-3 py-1">{{ $course->cl_hours }}</td>
                    <td rowspan="2" class="border px-3 py-1">{{ $course->tl_hours }}</td>
                    <td rowspan="2" class="border px-3 py-1">{{ $course->ll_hours }}</td>
                    <td rowspan="2" class="border px-3 py-1">{{ $course->sla_hours }}</td>
                    <td rowspan="2" class="border px-3 py-1">
                        {{ $course->cl_hours + $course->tl_hours + $course->ll_hours + $course->sla_hours }}</td>

                    {{-- Credits --}}
                    <td rowspan="2" class="border px-3 py-1">{{ $course->credits }}</td>

                    {{-- Duration --}}
                    <td rowspan="2" class="border px-3 py-2">{{ $course->paper_duration }}</td>

                    <td class="border  text-xs">
                        MAX </td>

                    <td class="border  text-xs">
                        MAX </td>

                    <td class="border px-1 text-xs">
                        MAX
                    </td>
                    <td class="border px-1 text-xs">
                        MIN
                    </td>

                    {{-- Practical --}}
                    <td class="border px-1 text-xs">
                        MAX
                    </td>
                    <td class="border px-1 text-xs">
                        MIN
                    </td>

                    <td class="border px-1 text-xs">
                        MAX
                    </td>
                    <td class="border px-1 text-xs">
                        MIN
                    </td>

                    {{-- SLA --}}
                    <td class="border  text-xs">
                        MAX
                    </td>
                    <td class="border  text-xs">
                        MIN
                    </td>

                    {{-- Total --}}
                    <td rowspan="2" class="border px-3 py-1 font-semibold">
                        {{ $course->total_marks }}
                    </td>
                </tr>
                <tr>

                    <td class="border  text-xs">

                        {{ $course->fa_th }}
                    </td>

                    <td class="border  text-xs">

                        {{ $course->sa_th }}
                    </td>

                    <td class="border px-1 text-xs">

                        {{ $course->fa_th + $course->sa_th }}
                    </td>
                    <td class="border px-1 text-xs">

                        {{ (($course->fa_th + $course->sa_th) / 25) * 10 }}
                    </td>

                    {{-- Practical --}}
                    <td class="border px-1 text-xs">

                        {{ $course->fa_pr }}
                    </td>
                    <td class="border px-1 text-xs">

                        {{ ($course->fa_pr / 25) * 10 }}
                    </td>

                    <td class="border px-1 text-xs">

                        {{ $course->fa_pr }}
                    </td>
                    <td class="border px-1 text-xs">

                        {{ ($course->fa_pr / 25) * 10 }}
                    </td>

                    {{-- SLA --}}
                    <td class="border  text-xs">

                        {{ $course->sla_marks }}
                    </td>
                    <td class="border  text-xs">

                        {{ ($course->sla_marks / 25) * 10 }}
                    </td>

                </tr>

            </tbody>

        </table>

    </div>



    {{-- ================= EXTRA ================= --}}
    <div class="text-gray-700 text-sm mb-6">

        <p>
            <span class="font-semibold">IKS Content:</span>
            Total Learning Hours for Term:
            {{ $course->iks_hours ?? 0 }} Hrs
        </p>

    </div>

    {{-- ================= 1.0 RATIONALE ================= --}}
    <div class="mb-6">

        <h4 class="font-semibold text-gray-800 mb-2">
            1.0 RATIONALE:
        </h4>

        <p class="text-gray-700 text-sm leading-relaxed text-justify">
            {{ $rationale }}
        </p>

    </div>



    {{-- ================= 2.0 INDUSTRIAL OUTCOME ================= --}}
    <div class="mb-6">

        <h4 class="font-semibold text-gray-800 mb-2">
            2.0 INDUSTRY / EMPLOYER EXPECTED OUTCOME:
        </h4>

        <p class="text-gray-700 text-sm mb-3">
            The Aim of this course is to help the students to attain these industry-identified outcomes
            through various teaching learning experiences:
        </p>

        <ol class="list-decimal pl-5 text-sm text-gray-700 space-y-1">

            @foreach ($industrialOutcomes as $item)
                <li>{{ $item->content }}</li>
            @endforeach

        </ol>

    </div>



    {{-- ================= 3.0 COURSE OUTCOMES ================= --}}
    <div class="mb-6">

        <h4 class="font-semibold text-gray-800 mb-2">
            3.0 COURSE OUTCOMES:
        </h4>

        <p class="text-gray-700 text-sm mb-3">
            The course content should be taught and learning imparted in such a manner that students are able
            to acquire required learning outcome in cognitive, psychomotor and affective domain to demonstrate
            following course outcomes:
        </p>

        <ol class="list-decimal pl-5 text-sm text-gray-700 space-y-1">

            @foreach ($courseOutcomes as $co)
                <li>{{ $co->description }}</li>
            @endforeach

        </ol>

    </div>
@endsection
