@extends('layouts.syllabus')

@section('content')

    @php
        // ── Assessment Methodology derived from course_master ──
        $assessmentPoints = [];
        if ($course->fa_th > 0) {
            $assessmentPoints[] = [
                'label' => 'Formative Assessment: TH',
                'detail' => 'Periodic Test: ' . $course->fa_th . ' marks',
            ];
        }
        if ($course->sa_th > 0) {
            $assessmentPoints[] = [
                'label' => 'Summative Assessment: TH',
                'detail' => $course->sa_th . ' marks Final theory paper',
            ];
        }
        if ($course->fa_pr > 0) {
            $assessmentPoints[] = ['label' => 'Formative Assessment: PR', 'detail' => 'Lab performance, Viva/voce'];
        }
        if ($course->sa_pr > 0) {
            $assessmentPoints[] = ['label' => 'Summative Assessment: PR', 'detail' => 'Lab performance, Viva/voce'];
        }
        if ($course->sla_marks > 0) {
            $assessmentPoints[] = [
                'label' => 'Self Learning Assessment',
                'detail' => 'Assignments / mini projects: ' . $course->sla_marks . ' marks',
            ];
        }

        $n = 0;

        // $cos is same as $courseOutcomes – alias so all sections can use either name
        $cos = $courseOutcomes;
    @endphp

    {{-- ═══ HEADER ═══ --}}
    <div class="border border-gray-400 p-3 mb-4 text-sm font-serif">
        <div class="flex justify-between items-start">
            <div class="space-y-1">
                <p><span class="font-bold">PROGRAMME :</span> Diploma Programme in {{ $programmes }}</p>
                <p><span class="font-bold">COURSE :</span> {{ $course->title }}</p>
            </div>
            <div class="text-right shrink-0 ml-4">
                <p><span class="font-bold">COURSE CODE :</span> {{ $course->course_code ?? '---' }}</p>
            </div>
        </div>
    </div>

    {{-- ═══ LEARNING & ASSESSMENT SCHEME ═══ --}}
    <p class="text-sm font-bold uppercase tracking-wide mb-2">LEARNING AND ASSESSMENT SCHEME</p>
    <div class="overflow-x-auto mb-3">
        <table class="w-full border-collapse text-xs text-center font-serif">
            <colgroup>
                <col style="width:4%">
                <col style="width:4%">
                <col style="width:4%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:6%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:6%">
            </colgroup>
            <thead class="bg-gray-100 font-bold">
                <tr>
                    <th colspan="5" class="border border-gray-400 px-1 py-1">Learning Scheme</th>
                    <th rowspan="4" class="border border-gray-400 px-1 py-1">Credits</th>
                    <th rowspan="4" class="border border-gray-400 px-1 py-1">Paper Duration</th>
                    <th colspan="10" class="border border-gray-400 px-1 py-1">Assessment Scheme</th>
                    <th rowspan="4" class="border border-gray-400 px-1 py-1">Total Marks</th>
                </tr>
                <tr>
                    <th colspan="3" rowspan="2" class="border border-gray-400 px-1 py-1">Actual Contact Hrs./Week</th>
                    <th rowspan="3" class="border border-gray-400 px-1 py-1">SLH</th>
                    <th rowspan="3" class="border border-gray-400 px-1 py-1">NLH</th>
                    <th colspan="4" rowspan="2" class="border border-gray-400 px-1 py-1">Theory</th>
                    <th colspan="4" class="border border-gray-400 px-1 py-1">Based on LL and TSL</th>
                    <th colspan="2" rowspan="2" class="border border-gray-400 px-1 py-1">Based on Self Learning</th>
                </tr>
                <tr>
                    <th colspan="4" class="border border-gray-400 px-1 py-1">Practical</th>
                </tr>
                <tr>
                    <th class="border border-gray-400 px-1 py-1">CL</th>
                    <th class="border border-gray-400 px-1 py-1">TL</th>
                    <th class="border border-gray-400 px-1 py-1">LL</th>
                    <th class="border border-gray-400 px-1 py-1">FA-TH</th>
                    <th class="border border-gray-400 px-1 py-1">SA-TH</th>
                    <th colspan="2" class="border border-gray-400 px-1 py-1">Total</th>
                    <th colspan="2" class="border border-gray-400 px-1 py-1">FA-PR</th>
                    <th colspan="2" class="border border-gray-400 px-1 py-1">SA-PR</th>
                    <th colspan="2" class="border border-gray-400 px-1 py-1">SLA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="2" class="border border-gray-400 px-1 py-1">{{ $course->cl_hours }}</td>
                    <td rowspan="2" class="border border-gray-400 px-1 py-1">{{ $course->tl_hours }}</td>
                    <td rowspan="2" class="border border-gray-400 px-1 py-1">{{ $course->ll_hours }}</td>
                    <td rowspan="2" class="border border-gray-400 px-1 py-1">{{ $course->sla_hours }}</td>
                    <td rowspan="2" class="border border-gray-400 px-1 py-1">
                        {{ $course->cl_hours + $course->tl_hours + $course->ll_hours + $course->sla_hours }}</td>
                    <td rowspan="2" class="border border-gray-400 px-1 py-1">{{ $course->credits }}</td>
                    <td rowspan="2" class="border border-gray-400 px-1 py-1">{{ $course->paper_duration }}</td>
                    <td class="border border-gray-400 px-1 py-0.5 text-gray-500 text-xs">Max</td>
                    <td class="border border-gray-400 px-1 py-0.5 text-gray-500 text-xs">Max</td>
                    <td class="border border-gray-400 px-1 py-0.5 text-gray-500 text-xs">Max</td>
                    <td class="border border-gray-400 px-1 py-0.5 text-gray-500 text-xs">Min</td>
                    <td class="border border-gray-400 px-1 py-0.5 text-gray-500 text-xs">Max</td>
                    <td class="border border-gray-400 px-1 py-0.5 text-gray-500 text-xs">Min</td>
                    <td class="border border-gray-400 px-1 py-0.5 text-gray-500 text-xs">Max</td>
                    <td class="border border-gray-400 px-1 py-0.5 text-gray-500 text-xs">Min</td>
                    <td class="border border-gray-400 px-1 py-0.5 text-gray-500 text-xs">Max</td>
                    <td class="border border-gray-400 px-1 py-0.5 text-gray-500 text-xs">Min</td>
                    <td rowspan="2" class="border border-gray-400 px-1 py-1 font-bold">{{ $course->total_marks }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-400 px-1 py-1">{{ $course->fa_th ?: '--' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $course->sa_th ?: '--' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $course->fa_th + $course->sa_th ?: '--' }}</td>
                    <td class="border border-gray-400 px-1 py-1">
                        {{ $course->fa_th + $course->sa_th > 0 ? intval(($course->fa_th + $course->sa_th) * 0.4) : '--' }}
                    </td>
                    <td class="border border-gray-400 px-1 py-1">{{ $course->fa_pr ?: '--' }}</td>
                    <td class="border border-gray-400 px-1 py-1">
                        {{ $course->fa_pr > 0 ? intval($course->fa_pr * 0.4) : '--' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $course->sa_pr ?: '--' }}</td>
                    <td class="border border-gray-400 px-1 py-1">
                        {{ $course->sa_pr > 0 ? intval($course->sa_pr * 0.4) : '--' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $course->sla_marks ?: '--' }}</td>
                    <td class="border border-gray-400 px-1 py-1">
                        {{ $course->sla_marks > 0 ? intval($course->sla_marks * 0.4) : '--' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <p class="text-xs text-gray-700 mb-5 font-serif">
        <span class="font-bold">IKS Content:</span> Total Learning Hours for Term: {{ $course->iks_hours ?? 0 }} Hrs
    </p>

    {{-- ═══ 1. RATIONALE ═══ --}}
    @if (isset($sections['rationale']))
        @php $n++; @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">{{ $n }}.0 RATIONALE</p>
            <p class="text-sm font-serif leading-relaxed">{{ $rationale }}</p>
        </div>
    @endif

    {{-- ═══ 2. INDUSTRY / EMPLOYER EXPECTED OUTCOME ═══ --}}
    @if (isset($sections['industrial_outcomes']))
        @php $n++; @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">{{ $n }}.0 INDUSTRY /
                EMPLOYER EXPECTED OUTCOME</p>
            <p class="text-sm font-serif mb-2">The Aim of this course is to help the students to attain these
                industry-identified outcomes through various teaching learning experiences:</p>
            <ol class="list-decimal list-inside text-sm font-serif space-y-1 pl-2">
                @foreach ($industrialOutcomes as $io)
                    <li>{{ $io->content }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    {{-- ═══ 3. COURSE OUTCOMES ═══ --}}
    @if (isset($sections['course_outcomes']))
        @php $n++; @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">{{ $n }}.0 COURSE
                OUTCOMES</p>
            <p class="text-sm font-serif mb-2">The course content should be taught and learning imparted in such a manner
                that students are able to acquire required learning outcome in cognitive, psychomotor and affective domain
                to demonstrate following course outcomes:</p>
            <ol class="list-decimal list-inside text-sm font-serif space-y-1 pl-2">
                @foreach ($courseOutcomes as $co)
                    <li>{{ $co->description }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    {{-- ═══ 4. COURSE DETAILS ═══ --}}
    @if (isset($sections['course_details']))
        @php $n++; @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">{{ $n }}.0 COURSE DETAILS
            </p>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-xs font-serif">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-400 px-2 py-1 text-center w-24">Unit</th>
                            <th class="border border-gray-400 px-2 py-1 text-center w-44">Major Learning Outcomes<br><span
                                    class="font-normal">(in cognitive domain)</span></th>
                            <th class="border border-gray-400 px-2 py-1 text-left">Topics and Sub-topics</th>
                            <th class="border border-gray-400 px-2 py-1 text-center w-14">Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($units as $unit)
                            @php
                                $outcomes = $unit->topics->where('type', 'learning_outcome')->values();
                                $topics = $unit->topics->where('type', 'topic')->values();
                            @endphp
                            <tr>
                                <td class="border border-gray-400 px-2 py-1 text-center align-top font-semibold">
                                    Unit-{{ $unit->unit_no }}<br>
                                    <span class="font-normal text-gray-700">{{ $unit->title }}</span>
                                </td>
                                <td class="border border-gray-400 px-2 py-1 align-top">
                                    @if ($outcomes->count())
                                        <ol class="list-none space-y-1">
                                            @foreach ($outcomes as $idx => $out)
                                                <li>{{ $unit->unit_no }}{{ chr(96 + $idx + 1) }}. {{ $out->content }}
                                                </li>
                                            @endforeach
                                        </ol>
                                    @endif
                                </td>
                                <td class="border border-gray-400 px-2 py-1 align-top">
                                    @foreach ($topics as $ti => $topic)
                                        <p class="font-semibold mb-0.5">{{ $unit->unit_no }}.{{ $ti + 1 }}
                                            {{ $topic->content }}</p>
                                        @if ($topic->subtopics->count())
                                            <ul class="list-disc pl-4 mb-1 space-y-0.5">
                                                @foreach ($topic->subtopics as $sub)
                                                    <li>{{ $sub->subtopic }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="border border-gray-400 px-2 py-1 text-center align-top">{{ $unit->hours }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-bold">
                            <td colspan="3" class="border border-gray-400 px-2 py-1 text-right">TOTAL</td>
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $units->sum('hours') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

    {{-- ═══ 5. SPECIFICATION TABLE ═══ --}}
    @if (isset($sections['specification_table']))
        @php
            $n++;
            $specByUnit = $specRows->keyBy('syllabus_unit_id');
            $grandR = $grandU = $grandA = $grandT = 0;
        @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">{{ $n }}.0 SUGGESTED
                SPECIFICATION TABLE WITH MARKS (THEORY)</p>
            <table class="w-full border-collapse text-xs font-serif">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-400 px-2 py-1 text-center">Sr.No</th>
                        <th class="border border-gray-400 px-2 py-1 text-center">Unit</th>
                        <th class="border border-gray-400 px-2 py-1 text-left">Unit Title</th>
                        <th class="border border-gray-400 px-2 py-1 text-center">Aligned COs</th>
                        <th class="border border-gray-400 px-2 py-1 text-center">Learning Hours</th>
                        <th class="border border-gray-400 px-2 py-1 text-center">R Level</th>
                        <th class="border border-gray-400 px-2 py-1 text-center">U Level</th>
                        <th class="border border-gray-400 px-2 py-1 text-center">A and above Levels</th>
                        <th class="border border-gray-400 px-2 py-1 text-center">Total Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $idx => $unit)
                        @php
                            $row = $specByUnit[$unit->id] ?? null;
                            $r = $row->remember_marks ?? 0;
                            $u = $row->understand_marks ?? 0;
                            $a = $row->apply_marks ?? 0;
                            $t = $r + $u + $a;
                            $grandR += $r;
                            $grandU += $u;
                            $grandA += $a;
                            $grandT += $t;
                            $unitCos = $qpp
                                ->filter(fn($q) => $q->syllabus_unit_id == $unit->id)
                                ->map(fn($q) => optional($cos->firstWhere('id', $q->course_outcome_id))->co_code)
                                ->filter()
                                ->unique()
                                ->implode(', ');
                        @endphp
                        <tr class="{{ $idx % 2 == 0 ? '' : 'bg-gray-50' }}">
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $idx + 1 }}</td>
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $unit->unit_no }}</td>
                            <td class="border border-gray-400 px-2 py-1">{{ $unit->title }}</td>
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $unitCos ?: '—' }}</td>
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $unit->hours }}</td>
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $r }}</td>
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $u }}</td>
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $a }}</td>
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $t }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="5" class="border border-gray-400 px-2 py-1 text-right">TOTAL</td>
                        <td class="border border-gray-400 px-2 py-1 text-center">{{ $grandR }}</td>
                        <td class="border border-gray-400 px-2 py-1 text-center">{{ $grandU }}</td>
                        <td class="border border-gray-400 px-2 py-1 text-center">{{ $grandA }}</td>
                        <td class="border border-gray-400 px-2 py-1 text-center">{{ $grandT }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    {{-- ═══ 6. PRACTICALS ═══ --}}
    @if (isset($sections['practicals']))
        @php $n++; @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">{{ $n }}.0 LABORATORY
                LEARNING OUTCOME AND ALLIED PRACTICAL / TUTORIAL EXPERIENCES</p>
            <p class="text-xs font-serif mb-2">The tutorial/practical/assignment/task should be properly designed and
                implemented with an attempt to develop different types of cognitive and practical skills (Outcomes in
                cognitive, psychomotor and affective domain) so that students are able to acquire the desired programme
                outcome/course outcome.</p>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-xs font-serif">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-400 px-2 py-1 text-center w-10">Sr. No.</th>
                            <th class="border border-gray-400 px-2 py-1 text-center w-16">Unit No.</th>
                            <th class="border border-gray-400 px-2 py-1 text-left" style="width:28%">Laboratory Learning
                                Outcome<br><span class="font-normal">(Outcomes in Psychomotor Domain)</span></th>
                            <th class="border border-gray-400 px-2 py-1 text-left">Practical Exercises</th>
                            <th class="border border-gray-400 px-2 py-1 text-center w-14">Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($practicals as $i => $p)
                            <tr class="{{ $i % 2 == 0 ? '' : 'bg-gray-50' }}">
                                <td class="border border-gray-400 px-2 py-1 text-center">{{ $i + 1 }}</td>
                                <td class="border border-gray-400 px-2 py-1 text-center">
                                    @foreach ($p->units as $u)
                                        {{ $u->unit_no }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </td>
                                <td class="border border-gray-400 px-2 py-1">{{ $p->lab_learning_outcome }}</td>
                                <td class="border border-gray-400 px-2 py-1">{{ $p->exercise }}</td>
                                <td class="border border-gray-400 px-2 py-1 text-center">{{ $p->hours }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-bold">
                            <td colspan="4" class="border border-gray-400 px-2 py-1 text-right">Total</td>
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $practicals->sum('hours') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

    {{-- ═══ 7. TUTORIAL ═══ --}}
    @if (isset($sections['tutorial']))
        @php $n++; @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">{{ $n }}.0 TUTORIAL LIST
            </p>
            <ol class="list-decimal list-inside text-sm font-serif space-y-1 pl-2">
                @foreach ($tutorial as $item)
                    <li>{{ $item->content }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    {{-- ═══ 8. SELF LEARNING ═══ --}}
    @if (isset($sections['self_learning']))
        @php $n++; @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">
                {{ $n }}.0 SELF LEARNING
                <span class="text-xs font-normal normal-case">(Assignment / Activities for Specific Learning / Skill
                    Development / Online Courses / Micro projects)</span>
            </p>
            <ol class="list-decimal list-inside text-sm font-serif space-y-1 pl-2">
                @foreach ($selfLearning as $item)
                    <li>{{ $item->content }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    {{-- ═══ 9. SPECIAL INSTRUCTIONAL STRATEGIES ═══ --}}
    @if (isset($sections['instruction']))
        @php $n++; @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">{{ $n }}.0 SPECIAL
                INSTRUCTIONAL STRATEGIES (If any)</p>
            <p class="text-sm font-serif mb-1">These are sample strategies, which the teacher can use to accelerate the
                attainment of the various outcomes in this course:</p>
            <ol class="list-decimal list-inside text-sm font-serif space-y-1 pl-2">
                @foreach ($instruction as $item)
                    <li>{{ $item->content }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    {{-- ═══ 10. ASSESSMENT METHODOLOGY ═══ --}}
    @if (count($assessmentPoints) > 0)
        @php $n++; @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">{{ $n }}.0 ASSESSMENT
                METHODOLOGY</p>
            <ol class="list-decimal list-inside text-sm font-serif space-y-2 pl-2">
                @foreach ($assessmentPoints as $ap)
                    <li>
                        <span class="font-semibold">{{ $ap['label'] }}</span><br>
                        <span class="pl-5 block">{{ $ap['detail'] }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    @endif

    {{-- ═══ 11. LEARNING RESOURCES ═══ --}}
    @php $hasResources = (isset($sections['books']) && $books->count()) || (isset($sections['websites']) && $websites->count()) || (isset($sections['equipments']) && $equipments->count()); @endphp
    @if ($hasResources)
        @php $n++; @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-3">{{ $n }}.0 LEARNING
                RESOURCES</p>

            @if (isset($sections['books']) && $books->count())
                <p class="text-md font-semibold mb-2">A) Books:</p>
                <table class="w-full border-collapse text-xs font-serif mb-4">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-400 px-2 py-1 text-center w-10">Sr. No.</th>
                            <th class="border border-gray-400 px-2 py-1 text-left" style="width:25%">Author</th>
                            <th class="border border-gray-400 px-2 py-1 text-left" style="width:45%">Title of Book</th>
                            <th class="border border-gray-400 px-2 py-1 text-left">Publication</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $idx => $b)
                            <tr class="{{ $idx % 2 == 0 ? '' : 'bg-gray-50' }}">
                                <td class="border border-gray-400 px-2 py-1 text-center">{{ $idx + 1 }}</td>
                                <td class="border border-gray-400 px-2 py-1">{{ $b->author }}</td>
                                <td class="border border-gray-400 px-2 py-1">{{ $b->title }}</td>
                                <td class="border border-gray-400 px-2 py-1">{{ $b->publication }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if (isset($sections['websites']) && $websites->count())
                <p class="text-md font-semibold mb-1">B) Software / Learning Websites:</p>
                <ol class="list-decimal list-inside text-sm font-serif space-y-1 pl-2 mb-4">
                    @foreach ($websites as $w)
                        <li>{{ $w->url }}@if ($w->description)
                                — <em>{{ $w->description }}</em>
                            @endif
                        </li>
                    @endforeach
                </ol>
            @endif

            @if (isset($sections['equipments']) && $equipments->count())
                <p class="text-md font-semibold mb-1">C) Major Equipment / Instrument with Broad Specifications:</p>
                <table class="w-full border-collapse text-xs font-serif mb-2">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-400 px-2 py-1 text-center w-10">Sr. No.</th>
                            <th class="border border-gray-400 px-2 py-1 text-left" style="width:30%">Equipment</th>
                            <th class="border border-gray-400 px-2 py-1 text-left">Specification</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($equipments as $idx => $e)
                            <tr class="{{ $idx % 2 == 0 ? '' : 'bg-gray-50' }}">
                                <td class="border border-gray-400 px-2 py-1 text-center">{{ $idx + 1 }}</td>
                                <td class="border border-gray-400 px-2 py-1">{{ $e->equipment_name }}</td>
                                <td class="border border-gray-400 px-2 py-1">{!! nl2br(e($e->specification)) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif

    {{-- ═══ 12. CO-PO-PSO MAPPING ═══ --}}
    @if (isset($sections['mapping']))
        @php
            $n++;
            if (!function_exists('levelText')) {
                function levelText($l)
                {
                    return $l == 3 ? 'H' : ($l == 2 ? 'M' : ($l == 1 ? 'L' : ''));
                }
            }
        @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">{{ $n }}.0 MAPPING MATRIX
                OF PO's, CO's and PSO's</p>
            <div class="overflow-x-auto">
                <table class="border-collapse text-xs font-serif w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-400 px-2 py-1" rowspan="2">Course Outcomes</th>
                            <th class="border border-gray-400 px-2 py-1 text-center" colspan="{{ $pos->count() }}">
                                Programme Outcomes (PO's)</th>
                            @if ($psos->count())
                                <th class="border border-gray-400 px-2 py-1 text-center" colspan="{{ $psos->count() }}">
                                    Programme Specific Outcomes (PSO's)</th>
                            @endif
                        </tr>
                        <tr>
                            @foreach ($pos as $po)
                                <th class="border border-gray-400 px-3 py-1">{{ $po->po_code }}</th>
                            @endforeach
                            @foreach ($psos as $pso)
                                <th class="border border-gray-400 px-3 py-1">{{ $pso->po_code }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cos as $co)
                            <tr>
                                <td class="border border-gray-400 px-2 py-1 font-semibold text-center">{{ $co->co_code }}</td>
                                @foreach ($pos as $po)
                                    @php $key = $co->id.'_'.$po->id; @endphp
                                    <td class="border border-gray-400 px-2 py-1 text-center">
                                        {{ isset($mapping[$key]) ? levelText($mapping[$key]->level) : '' }}</td>
                                @endforeach
                                @foreach ($psos as $pso)
                                    @php $key = $co->id.'_'.$pso->id; @endphp
                                    <td class="border border-gray-400 px-2 py-1 text-center">
                                        {{ isset($mapping[$key]) ? levelText($mapping[$key]->level) : '' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-xs mt-1 font-serif"><strong>H:</strong> High Relationship &nbsp;&nbsp; <strong>M:</strong>
                Moderate Relationship &nbsp;&nbsp; <strong>L:</strong> Low Relationship.</p>
        </div>
    @endif

    {{-- ═══ 13. QPP ═══ --}}
    @if (isset($sections['qpp']) && $qpp->count())
        @php
            $n++;
            $multiplier = $syllabus->question_multiplier ?? 1.35;
            $qppSorted = $qpp->sortBy('order_no');
            $totalMPU = 0;
            $totalAdj = 0;
            $colTotals = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0];
            $grandActual = 0;
        @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">{{ $n }}.0 SUGGESTED
                QUESTION PAPER PROFILE</p>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-xs font-serif">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-400 px-2 py-1" rowspan="2">Unit No.</th>
                            <th class="border border-gray-400 px-2 py-1" rowspan="2">CO</th>
                            <th class="border border-gray-400 px-2 py-1" rowspan="2">Marks per Unit</th>
                            <th class="border border-gray-400 px-2 py-1" rowspan="2">{{ $multiplier }}&times; Marks
                            </th>
                            <th class="border border-gray-400 px-2 py-1 text-center" colspan="6">Question Number Wise
                                Marks</th>
                            <th class="border border-gray-400 px-2 py-1" rowspan="2">Actual Distribution of Marks</th>
                        </tr>
                        <tr>
                            @for ($q = 1; $q <= 6; $q++)
                                <th class="border border-gray-400 px-2 py-1">Q{{ $q }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($qppSorted as $row)
                            @php
                                $cObj = $cos->firstWhere('id', $row->course_outcome_id);
                                $uObj = $units->firstWhere('id', $row->syllabus_unit_id);
                                $actual =
                                    $row->q1_marks +
                                    $row->q2_marks +
                                    $row->q3_marks +
                                    $row->q4_marks +
                                    $row->q5_marks +
                                    $row->q6_marks;
                                $totalMPU += $row->marks_per_unit;
                                $totalAdj += $row->adjusted_marks;
                                for ($q = 1; $q <= 6; $q++) {
                                    $colTotals[$q] += $row->{'q' . $q . '_marks'};
                                }
                                $grandActual += $actual;
                            @endphp
                            <tr>
                                <td class="border border-gray-400 px-2 py-1 text-center">{{ $uObj->unit_no ?? '—' }}</td>
                                <td class="border border-gray-400 px-2 py-1 text-center">{{ $cObj->co_code ?? '—' }}</td>
                                <td class="border border-gray-400 px-2 py-1 text-center">{{ $row->marks_per_unit }}</td>
                                <td class="border border-gray-400 px-2 py-1 text-center">{{ $row->adjusted_marks }}</td>
                                @for ($q = 1; $q <= 6; $q++)
                                    <td class="border border-gray-400 px-2 py-1 text-center">
                                        {{ $row->{'q' . $q . '_marks'} ?: '' }}</td>
                                @endfor
                                <td class="border border-gray-400 px-2 py-1 text-center">{{ $actual }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-bold">
                            <td class="border border-gray-400 px-2 py-1" colspan="2"></td>
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $totalMPU }}</td>
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $totalAdj }}</td>
                            @for ($q = 1; $q <= 6; $q++)
                                <td class="border border-gray-400 px-2 py-1 text-center">{{ $colTotals[$q] }}</td>
                            @endfor
                            <td class="border border-gray-400 px-2 py-1 text-center">{{ $grandActual }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

    {{-- ═══ 14. QUESTION BITS — single cross-unit table ═══ --}}
    @if (isset($sections['qb']) && $qb->count() && isset($sections['qpp']))
        @php
            $n++;
            $bits_list = ['a', 'b', 'c', 'd', 'e', 'f', 'g'];
            $qppSorted2 = $qpp->sortBy('order_no');
            $qppByUnit = $qppSorted2->keyBy('syllabus_unit_id');
            // Units that appear in QPP, in order
            $qppUnits = $qppSorted2->map(fn($r) => $units->firstWhere('id', $r->syllabus_unit_id))->filter()->values();
            $multiplier2 = $syllabus->question_multiplier ?? 1.35;
        @endphp
        <div class="mb-5">
            <p class="font-bold text-md uppercase border-b border-gray-400 pb-1 mb-2">{{ $n }}.0 QUESTION BITS
            </p>

            @if ($qppUnits->count())
                <p class="text-xs font-semibold mb-2">Table For {{ $qppUnits->count() }} Units:</p>
            @endif

            <div class="overflow-x-auto">
                <table class="border-collapse text-xs font-serif" style="width:max-content; min-width:100%;">

                    {{-- ROW 1: Unit No. --}}
                    <tr class="bg-gray-100 font-bold">
                        <td class="border border-gray-600 px-2 py-1 font-bold whitespace-nowrap">Unit No.</td>
                        @foreach ($qppUnits as $unit)
                            <td class="border border-gray-600 px-1 py-1 text-center font-bold"
                                colspan="{{ count($bits_list) }}">{{ $unit->unit_no }}</td>
                        @endforeach
                        <td rowspan='2' class="border border-gray-600 px-2 py-1 text-center font-bold">Total</td>
                    </tr>

                    {{-- ROW 2: CO --}}
                    <tr class="bg-gray-100 font-bold">
                        <td class="border border-gray-600 px-2 py-1 font-bold">CO</td>
                        @foreach ($qppUnits as $unit)
                            @php
                                $qRow = $qppByUnit[$unit->id] ?? null;
                                $cObj = $qRow ? $cos->firstWhere('id', $qRow->course_outcome_id) : null;
                                $coNum = $cObj ? (preg_replace('/[^0-9]/', '', $cObj->co_code) ?: $cObj->co_code) : '—';
                            @endphp
                            <td class="border border-gray-600 px-1 py-1 text-center font-bold"
                                colspan="{{ count($bits_list) }}">{{ $coNum }}</td>
                        @endforeach
                    </tr>

                    {{-- ROW 3: Marks per Unit (adjusted) --}}
                    <tr class="bg-gray-100 font-bold">
                        <td class="border border-gray-600 px-2 py-1 font-bold whitespace-nowrap">Marks per<br>Unit</td>
                        @foreach ($qppUnits as $unit)
                            @php $qRow = $qppByUnit[$unit->id] ?? null; @endphp
                            <td class="border border-gray-600 px-1 py-1 text-center font-bold"
                                colspan="{{ count($bits_list) }}">{{ $qRow->adjusted_marks ?? '—' }}</td>
                        @endforeach
                        <td class="border border-gray-600 px-2 py-1 text-center font-bold">
                            {{ $qppSorted2->sum('adjusted_marks') }}</td>
                    </tr>

                    {{-- ROW 4: Multiplier × marks --}}
                    <tr class="bg-gray-100 font-bold">
                        <td class="border border-gray-600 px-2 py-1 font-bold whitespace-nowrap">
                            {{ $multiplier2 }}<br>Times<br>marks</td>
                        @foreach ($qppUnits as $unit)
                            @php $qRow = $qppByUnit[$unit->id] ?? null; @endphp
                            <td class="border border-gray-600 px-1 py-1 text-center font-bold"
                                colspan="{{ count($bits_list) }}">
                                {{ $qRow ? round($qRow->adjusted_marks * $multiplier2) : '—' }}
                            </td>
                        @endforeach
                        <td class="border border-gray-600 px-2 py-1 text-center font-bold">
                            {{ $qppSorted2->sum(fn($r) => round($r->adjusted_marks * $multiplier2)) }}
                        </td>
                    </tr>

                    {{-- ROW 5: Bits label header --}}
                    <tr class="bg-gray-50">
                        <td class="border border-gray-600 px-2 py-1 font-bold">Bits</td>
                        @foreach ($qppUnits as $unit)
                            @foreach ($bits_list as $bit)
                                <td class="border border-gray-600 px-1 py-1 text-center font-bold"
                                    style="min-width:20px;">{{ $bit }}</td>
                            @endforeach
                        @endforeach
                        <td rowspan="2" class="border border-gray-600 px-2 py-1 text-center font-bold">Total</td>
                    </tr>

                    {{-- ROW 6: CO number repeated per bit --}}
                    <tr class="bg-gray-50">
                        <td class="border border-gray-600 px-2 py-1 font-bold">CO</td>
                        @foreach ($qppUnits as $unit)
                            @php
                                $qRow = $qppByUnit[$unit->id] ?? null;
                                $cObj = $qRow ? $cos->firstWhere('id', $qRow->course_outcome_id) : null;
                                $coNum = $cObj ? (preg_replace('/[^0-9]/', '', $cObj->co_code) ?: $cObj->co_code) : '';
                            @endphp
                            @foreach ($bits_list as $bit)
                                <td class="border border-gray-600 px-1 py-0.5 text-center text-gray-600"
                                    style="min-width:20px;">{{ $coNum }}</td>
                            @endforeach
                        @endforeach
                    </tr>

                    {{-- Q1–Q6 ROWS --}}
                    @for ($q = 1; $q <= 6; $q++)
                        @php $rowGrand = 0; @endphp
                        <tr>
                            <td class="border border-gray-600 px-2 py-1 font-semibold">Q{{ $q }}</td>
                            @foreach ($qppUnits as $unit)
                                @foreach ($bits_list as $bit)
                                    @php
                                        $bval =
                                            $qb
                                                ->where('syllabus_unit_id', $unit->id)
                                                ->where('question_no', $q)
                                                ->where('bit_label', $bit)
                                                ->first()?->marks ?? '';
                                        $rowGrand += (int) $bval;
                                    @endphp
                                    <td class="border border-gray-600 px-1 py-1 text-center" style="min-width:20px;">
                                        {{ $bval }}</td>
                                @endforeach
                            @endforeach
                            <td class="border border-gray-600 px-2 py-1 text-center font-semibold">{{ $rowGrand ?: '' }}
                            </td>
                        </tr>
                    @endfor

                    {{-- Sub Total row (per-unit collapsed) --}}
                    @php $grandQBTotal = 0; @endphp
                    <tr class="bg-gray-100 font-bold">
                        <td class="border border-gray-600 px-2 py-1 font-bold">Sub Total</td>
                        @foreach ($qppUnits as $unit)
                            @php
                                $unitSum = $qb->where('syllabus_unit_id', $unit->id)->sum('marks');
                                $grandQBTotal += $unitSum;
                            @endphp
                            <td class="border border-gray-600 px-1 py-1 text-center font-bold"
                                colspan="{{ count($bits_list) }}">{{ $unitSum }}</td>
                        @endforeach
                        <td class="border border-gray-600 px-2 py-1 text-center font-bold">{{ $grandQBTotal }}</td>
                    </tr>

                    {{-- TOTAL footer --}}
                    <tr class="bg-gray-200 font-bold">
                        <td class="border border-gray-600 px-2 py-1 text-center font-bold"
                            colspan="{{ $qppUnits->count() * count($bits_list) + 2 }}">TOTAL</td>
                    </tr>

                </table>
            </div>
        </div>
    @endif

@endsection
