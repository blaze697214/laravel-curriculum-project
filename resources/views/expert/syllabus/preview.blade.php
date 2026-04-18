@extends('layouts.syllabus')

@section('content')

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


    @if (isset($sections['rationale']))
        <div class="mb-6">
            <h4>{{ $sections['rationale'] }}.0 RATIONALE</h4>
            <p>{{ $rationale }}</p>
        </div>
    @endif



    {{-- ================= 2.0 INDUSTRIAL OUTCOME ================= --}}


    @if (isset($sections['industrial_outcomes']))
        <div class="mb-6">
            <h4>{{ $sections['industrial_outcomes'] }}.0 INDUSTRY OUTCOME</h4>

            <p class="text-gray-700 text-sm mb-3">
                The Aim of this course is to help the students to attain these industry-identified outcomes
                through various teaching learning experiences:
            </p>

            <ol>
                @foreach ($industrialOutcomes as $io)
                    <li>{{ $io->content }}</li>
                @endforeach
            </ol>
        </div>
    @endif


    {{-- ================= 3.0 COURSE OUTCOMES ================= --}}

    @if (isset($sections['course_outcomes']))
        <div class="mb-6">
            <h4>{{ $sections['course_outcomes'] }}.0 COURSE OUTCOMES</h4>
            <p class="text-gray-700 text-sm mb-3">
                The course content should be taught and learning imparted in such a manner that students are able
                to acquire required learning outcome in cognitive, psychomotor and affective domain to demonstrate
                following course outcomes:
            </p>
            <ol>
                @foreach ($courseOutcomes as $co)
                    <li>{{ $co->description }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    @if (isset($sections['course_details']))
        <h4>{{ $sections['course_details'] }}.0 COURSE DETAILS</h4>

        <table border="1">
            <tr>
                <th>Unit</th>
                <th>Topics</th>
                <th>Hours</th>
            </tr>

            @foreach ($units as $unit)
                <tr>
                    <td>{{ $unit->unit_no }}</td>

                    <td>
                        @foreach ($unit->topics as $topic)
                            {{ $topic->content }} <br>
                        @endforeach
                    </td>

                    <td>{{ $unit->hours }}</td>
                </tr>
            @endforeach

        </table>
    @endif

    @if (isset($sections['specification_table']))
        <h4>{{ $sections['specification_table'] }}.0 SPECIFICATION TABLE</h4>

        <table border="1">
            <tr>
                <th>Unit</th>
                <th>R</th>
                <th>U</th>
                <th>A</th>
                <th>Total</th>
            </tr>

            @foreach ($specRows as $row)
                <tr>
                    <td>{{ $row->unit_no }}</td>
                    <td>{{ $row->remember_marks }}</td>
                    <td>{{ $row->understand_marks }}</td>
                    <td>{{ $row->apply_marks }}</td>
                    <td>{{ $row->total_marks }}</td>
                </tr>
            @endforeach

        </table>
    @endif
    @if (isset($sections['practicals']))
        <h4>{{ $sections['practicals'] }}.0 PRACTICALS</h4>

        <table border="1">

            <tr>
                <th>Sr No</th>
                <th>Units</th>
                <th>Learning Outcome</th>
                <th>Experiment</th>
                <th>Hours</th>
            </tr>

            @foreach ($practicals as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>

                    <td>
                        @foreach ($p->units as $u)
                            {{ $u->unit_no }},
                        @endforeach
                    </td>

                    <td>{{ $p->outcome }}</td>
                    <td>{{ $p->title }}</td>
                    <td>{{ $p->hours }}</td>

                </tr>
            @endforeach

        </table>
    @endif
    @if (isset($sections['self_learning']))
        <h4>{{ $sections['self_learning'] }}.0 SELF LEARNING</h4>
        <ul>
            @foreach ($selfLearning as $i)
                <li>{{ $i->content }}</li>
            @endforeach
        </ul>
    @endif

    @if (isset($sections['books']))
        <h4>{{ $sections['books'] }}.0 BOOKS</h4>

        <table border="1">
            @foreach ($books as $b)
                <tr>
                    <td>{{ $b->author }}</td>
                    <td>{{ $b->title }}</td>
                    <td>{{ $b->publication }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if (isset($sections['websites']))
        <h4>{{ $sections['websites'] }}.0 WEBSITES</h4>

        <ol>
            @foreach ($websites as $w)
                <li>{{ $w->url }}</li>
            @endforeach
        </ol>
    @endif

    @if (isset($sections['equipments']))
        <h4>{{ $sections['equipments'] }}.0 EQUIPMENTS</h4>

        @foreach ($equipments as $e)
            <p>
                {{ $e->equipment_name }} <br>
                {!! nl2br(e($e->specification)) !!}
            </p>
        @endforeach
    @endif
    @if (isset($sections['mapping']))
        <h4>{{ $sections['mapping'] }}.0 CO-PO-PSO MAPPING</h4>
        @php
            function levelText($l)
            {
                return $l == 3 ? 'H' : ($l == 2 ? 'M' : ($l == 1 ? 'L' : ''));
            }
        @endphp


        <table border="1">

            <tr>
                <th>CO</th>

                @foreach ($pos as $po)
                    <th>{{ $po->po_code }}</th>
                @endforeach

                @foreach ($psos as $pso)
                    <th>{{ $pso->po_code }}</th>
                @endforeach

            </tr>

            @foreach ($cos as $co)
                <tr>
                    <td>{{ $co->co_code }}</td>

                    @foreach ($pos as $po)
                        <td>
                            {{ optional($mapping[$co->id . '_' . $po->id])->level }}
                        </td>
                    @endforeach

                    @foreach ($psos as $pso)
                        <td>
                            {{ optional($mapping[$co->id . '_' . $pso->id])->level }}
                        </td>
                    @endforeach

                </tr>
            @endforeach

        </table>
    @endif

    @if (isset($sections['qpp']))
        <h4>{{ $sections['qpp'] }}.0 SUGGESTED QUESTION PAPER PROFILE</h4>

        <table border="1">

            <tr>
                <th>Unit</th>
                <th>CO</th>
                <th>Marks</th>
                <th>1.35×</th>
                <th colspan="6">Questions</th>
                <th>Total</th>
            </tr>

            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                @for ($i = 1; $i <= 6; $i++)
                    <th>{{ $i }}</th>
                @endfor
                <th></th>
            </tr>

            @foreach ($qpp as $row)
                @php
                    $total =
                        $row->q1_marks +
                        $row->q2_marks +
                        $row->q3_marks +
                        $row->q4_marks +
                        $row->q5_marks +
                        $row->q6_marks;

                    $co = $cos->firstWhere('id', $row->course_outcome_id);
                @endphp

                <tr>
                    <td>{{ $row->unit_no }}</td>
                    <td>{{ $co->co_code ?? '' }}</td>
                    <td>{{ $row->marks_per_unit }}</td>
                    <td>{{ $row->adjusted_marks }}</td>

                    <td>{{ $row->q1_marks }}</td>
                    <td>{{ $row->q2_marks }}</td>
                    <td>{{ $row->q3_marks }}</td>
                    <td>{{ $row->q4_marks }}</td>
                    <td>{{ $row->q5_marks }}</td>
                    <td>{{ $row->q6_marks }}</td>

                    <td>{{ $total }}</td>
                </tr>
            @endforeach

        </table>
    @endif

    @if (isset($sections['qb']))
        <h4>{{ $sections['qb'] }}.0 QUESTION BITS</h4>

        @php
            $grouped = $qb->groupBy('unit_no');
        @endphp

        @foreach ($grouped as $unit => $rows)
            <h5>Unit {{ $unit }}</h5>

            <table border="1">
                <tr>
                    <th>Q</th>
                    <th>a</th>
                    <th>b</th>
                    <th>c</th>
                    <th>d</th>
                    <th>e</th>
                    <th>f</th>
                    <th>Total</th>
                </tr>

                @for ($q = 1; $q <= 6; $q++)
                    @php
                        $bitsRow = $rows->where('question_no', $q)->keyBy('bit_label');

                        $sum = 0;
                    @endphp

                    <tr>
                        <td>Q{{ $q }}</td>

                        @foreach (['a', 'b', 'c', 'd', 'e', 'f'] as $b)
                            @php
                                $val = $bitsRow[$b]->marks ?? '';
                                $sum += $val ?: 0;
                            @endphp

                            <td>{{ $val }}</td>
                        @endforeach

                        <td>{{ $sum }}</td>

                    </tr>
                @endfor

            </table>
        @endforeach
    @endif
@endsection
