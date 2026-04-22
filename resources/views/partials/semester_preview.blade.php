{{-- ═══ HEADER ═══ --}}
    <div class="bg-white {{--rounded-xl shadow p-5 --}} mb-4 text-sm">
        <h2 class="text-center font-bold text-base mb-3 uppercase">
            Government Polytechnic Nashik
        </h2>
        <p class="text-center text-sm mb-3">Learning and Assessment Scheme for Post S.S.C Diploma Courses</p>

        <div class="grid grid-cols-2 gap-x-10 gap-y-1 text-sm">
            <div>
                <span class="font-semibold">Programme Name :</span>
                {{ $department->name }}
            </div>
            <div>
                <span class="font-semibold">With Effect From Academic Year :</span>
                {{ $scheme->year_start }}-{{ substr($scheme->year_end, -2) }}
            </div>
            <div>
                <span class="font-semibold">Duration Of Programme :</span>
                6 Semester
            </div>
            <div>
                <span class="font-semibold">Duration :</span>
                15 WEEKS
            </div>
            <div>
                <span class="font-semibold">Semester :</span>
                {{ ['', 'First', 'Second', 'Third', 'Fourth', 'Fifth', 'Sixth'][$semesterNo] ?? $semesterNo }}
            </div>
            <div>
                <span class="font-semibold">Scheme :</span>
                {{ $scheme->name }}
            </div>
        </div>
    </div>

    {{-- ═══ MAIN TABLE ═══ --}}
    <div class="bg-white shadow overflow-x-auto mb-4">
        <table class="w-full text-xs border-collapse" style="min-width: 1100px;">

            {{-- ── HEADER ROW 1 ── --}}
            <thead>
                <tr class="bg-gray-100 text-center font-semibold text-gray-700">
                    <th rowspan="5" class="border border-gray-400 px-2 py-1 w-8">Sr No</th>
                    <th rowspan="5" class="border border-gray-400 px-2 py-1" style="min-width:180px; text-align:left;">
                        Course Title</th>
                    <th rowspan="5" class="border border-gray-400 px-2 py-1 w-14">Abbrev</th>
                    <th rowspan="5" class="border border-gray-400 px-2 py-1 w-12">Course Type</th>
                    <th rowspan="5" class="border border-gray-400 px-2 py-1 w-16">Course Code</th>
                    <th rowspan="5" class="border border-gray-400 px-1 py-1 w-12">Total IKS Hrs for Sem.</th>
                    <th colspan="5" class="border border-gray-400 px-2 py-1">Learning Scheme</th>
                    <th rowspan="5" class="border border-gray-400 px-2 py-1 w-12">Credits</th>
                    <th colspan="11" class="border border-gray-400 px-2 py-1">Assessment Scheme</th>
                    <th rowspan="5" class="border border-gray-400 px-2 py-1 w-14">Total Marks</th>
                </tr>

                {{-- ── HEADER ROW 2 ── --}}
                <tr class="bg-gray-100 text-center font-semibold text-gray-700">
                    <th colspan="3" class="border border-gray-400 px-1 py-1">Actual Contact Hrs./Week</th>
                    <th rowspan="4" class="border border-gray-400 px-1 py-1 w-14">Self Learning (Term Work + Assignment)
                    </th>
                    <th rowspan="4" class="border border-gray-400 px-1 py-1 w-14">Notional Learning Hrs /Week</th>
                    {{-- Assessment --}}
                    <th rowspan="4" class="border border-gray-400 px-1 py-1 w-12">Paper Duration (hrs.)</th>
                    <th colspan="4" rowspan="2" class="border border-gray-400 px-1 py-1">Theory</th>
                    <th colspan="4" class="border border-gray-400 px-1 py-1">Based on LL &amp; TL </th>
                    <th colspan="2" rowspan="2" class="border border-gray-400 px-1 py-1">Based on Self Learning (SLA)
                    </th>
                </tr>

                <tr class="bg-gray-100 text-center font-semibold text-gray-700">
                    <th rowspan="3" class="border border-gray-400 px-1 py-1 w-10">CL</th>
                    <th rowspan="3" class="border border-gray-400 px-1 py-1 w-10">TL</th>
                    <th rowspan="3" class="border border-gray-400 px-1 py-1 w-10">LL</th>
                    <th colspan="4" class="border border-gray-400 px-1 py-1">Practical</th>
                </tr>

                {{-- ── HEADER ROW 3 ── --}}
                <tr class="bg-gray-100 text-center font-semibold text-gray-700">

                    {{-- Theory --}}
                    <th class="border border-gray-400 px-1 py-1">FA-TH</th>
                    <th class="border border-gray-400 px-1 py-1">SA-TH</th>
                    <th colspan="2" class="border border-gray-400 px-1 py-1">Total</th>
                    {{-- Practical --}}
                    <th colspan="2" class="border border-gray-400 px-1 py-1">FA-PR</th>
                    <th colspan="2" class="border border-gray-400 px-1 py-1">SA-PR</th>
                    {{-- SLA --}}
                    <th colspan="2" class="border border-gray-400 px-1 py-1">SLA</th>

                </tr>

                {{-- ── HEADER ROW 4 (Max/Min) ── --}}
                <tr class="bg-gray-100 text-center text-gray-600" style="font-size:0.65rem;">

                    {{-- FA-TH --}}
                    <th class="border border-gray-400 px-1 py-1">Max</th>
                    <th class="border border-gray-400 px-1 py-1">Max</th>
                    {{-- SA-TH --}}
                    <th class="border border-gray-400 px-1 py-1">Max</th>
                    <th class="border border-gray-400 px-1 py-1">Min</th>
                    {{-- FA-PR --}}
                    <th class="border border-gray-400 px-1 py-1">Max</th>
                    <th class="border border-gray-400 px-1 py-1">Min</th>
                    {{-- SA-PR --}}
                    <th class="border border-gray-400 px-1 py-1">Max</th>
                    <th class="border border-gray-400 px-1 py-1">Min</th>
                    {{-- SLA --}}
                    <th class="border border-gray-400 px-1 py-1">Max</th>
                    <th class="border border-gray-400 px-1 py-1">Min</th>
                </tr>
            </thead>

            <tbody class="text-center">

                @php
                    $srNo = 1;
                    $totIks = 0;
                    $totCL = 0;
                    $totTL = 0;
                    $totLL = 0;
                    $totSL = 0;
                    $totNLH = 0;
                    $totCredits = 0;
                    $totFaTh = 0;
                    $totSaTh = 0;
                    $totThTotal = 0;
                    $totThMin = 0;
                    $totFaPr = 0;
                    $totFaPrMin = 0;
                    $totSaPr = 0;
                    $totSaPrMin = 0;
                    $totSla = 0;
                    $totSlaMin = 0;
                    $totMarks = 0;
                @endphp

                @foreach ($grouped as $groupLabel => $groupCourses)
                    @if (str_starts_with($groupLabel, 'elective'))
                        {{-- Elective group heading row --}}
                        <tr class="bg-yellow-50">
                            <td class="border border-gray-400 px-2 py-1 text-left font-semibold text-gray-700"
                                colspan="24">
                                {{ $groupLabel }} (Any one)
                            </td>
                        </tr>
                    @endif

                    @foreach ($groupCourses as $c)
                        @php
                            $cm = $c->courseMaster;
                            $isElectiveRow = str_starts_with($groupLabel, 'elective');

                            $iks = $cm->iks_hours ?? 0;
                            $cl = $cm->cl_hours ?? 0;
                            $tl = $cm->tl_hours ?? 0;
                            $ll = $cm->ll_hours ?? 0;
                            $sl = $cm->sla_hours ?? 0;
                            $nlh = $cl + $tl + $ll + $sl;
                            $cred = $cm->credits ?? 0;
                            $pd = $cm->paper_duration ?? 0;

                            $faTh = $cm->fa_th ?? 0;
                            $saTh = $cm->sa_th ?? 0;
                            $thTotal = $faTh + $saTh;
                            $thMin = $thTotal > 0 ? intval($thTotal * 0.4) : 0;

                            $faPr = $cm->fa_pr ?? 0;
                            $faPrMin = $faPr > 0 ? intval($faPr * 0.4) : 0;
                            $saPr = $cm->sa_pr ?? 0;
                            $saPrMin = $saPr > 0 ? intval($saPr * 0.4) : 0;

                            $sla = $cm->sla_marks ?? 0;
                            $slaMin = $sla > 0 ? intval($sla * 0.4) : 0;

                            $total = $cm->total_marks ?? $faTh + $saTh + $faPr + $saPr + $sla;

                            // Totals (only for compulsory / first elective of group)
                            if (!$isElectiveRow || $loop->first) {
                                $totIks += $iks;
                                $totCL += $cl;
                                $totTL += $tl;
                                $totLL += $ll;
                                $totSL += $sl;
                                $totNLH += $nlh;
                                $totCredits += $cred;
                                $totFaTh += $faTh;
                                $totSaTh += $saTh;
                                $totThTotal += $thTotal;
                                $totThMin += $thMin;
                                $totFaPr += $faPr;
                                $totFaPrMin += $faPrMin;
                                $totSaPr += $saPr;
                                $totSaPrMin += $saPrMin;
                                $totSla += $sla;
                                $totSlaMin += $slaMin;
                                $totMarks += $total;
                            }

                            $displaySr = !$isElectiveRow ? $srNo++ : '';
                        @endphp

                        <tr class="hover:bg-gray-50 {{ $isElectiveRow ? 'bg-yellow-50/40' : '' }}">
                            <td class="border border-gray-400 px-1 py-1">{{ $displaySr }}</td>
                            <td class="border border-gray-400 px-2 py-1 text-left">{{ $cm->title }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $cm->abbreviation }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $cm->category?->abbreviation ?? '—' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $cm->course_code ?? '—' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $iks ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $cl ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $tl ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $ll ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $sl ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $nlh ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1 font-semibold">{{ $cred }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $pd ?: '-' }}</td>
                            {{-- FA-TH --}}
                            <td class="border border-gray-400 px-1 py-1">{{ $faTh ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $saTh ?: '-' }}</td>
                            {{-- SA-TH --}}
                            <td class="border border-gray-400 px-1 py-1 ">{{ $thTotal ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $thMin ?: '-' }}</td>
                            {{-- FA-PR --}}
                            <td class="border border-gray-400 px-1 py-1">{{ $faPr ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $faPr ? $faPrMin : '-' }}</td>
                            {{-- SA-PR --}}
                            <td class="border border-gray-400 px-1 py-1">{{ $saPr ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $saPr ? $saPrMin : '-' }}</td>
                            {{-- SLA --}}
                            <td class="border border-gray-400 px-1 py-1">{{ $sla ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $sla ? $slaMin : '-' }}</td>
                            {{-- Total --}}
                            <td class="border border-gray-400 px-1 py-1 font-bold">{{ $total }}</td>
                        </tr>
                    @endforeach
                @endforeach

                {{-- ── TOTAL ROW ── --}}
                <tr class="bg-gray-200 font-bold text-center">
                    <td colspan="5" class="border border-gray-400 px-2 py-1 text-right">Total</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totIks ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totCL }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totTL ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totLL ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totSL ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totNLH }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totCredits }}</td>
                    <td class="border border-gray-400 px-1 py-1"></td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totFaTh }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totSaTh }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totThTotal }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totThMin }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totFaPr }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totFaPrMin }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totSaPr }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totSaPrMin }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totSla }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totSlaMin }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totMarks }}</td>
                </tr>

            </tbody>
        </table>
    </div>

    {{-- ═══ ABBREVIATIONS ═══ --}}
    <div class="bg-white {{--rounded-xl shadow p-5 --}} mb-4 text-xs text-gray-700">
        <p class="mb-1">
            <span class="font-semibold">Abbreviations :</span>
            CL- Classroom Learning, TL- Tutorial Learning, LL-Laboratory Learning,
            FA - Formative Assessment, SA - Summative Assessment,
            IKS - Indian Knowledge System, SLA - Self Learning Assessment
        </p>
        <p>
            <span class="font-semibold">Legends :</span>
            @ Internal Assessment,
            # External Assessment,
            *# On Line Examination,
            @$ Internal Online Examination
        </p>
    </div>

    {{-- ═══ NOTES ═══ --}}
    <div class="bg-white {{--rounded-xl shadow p-5 --}} mb-4 text-xs text-gray-700">
        <p class="font-semibold mb-2">Note :</p>
        <ol class="list-decimal list-inside space-y-1">
            <li>FA-TH represents Best of two class tests of 30 marks each conducted during the term.</li>
            <li>If candidate is not securing minimum passing marks in FA-PR of any course then the candidate shall be
                declared as "Detained" in that semester.</li>
            <li>If candidate is not securing minimum passing marks in SLA of any course then the candidate shall be declared
                as fail and will have to repeat and resubmit SLA work.</li>
            <li>Notional Learning hours for the semester are (CL+LL+TL+SL)hrs.* 15 Weeks</li>
            <li>1 credit is equivalent to 30 Notional hrs.</li>
            <li>* Self learning hours shall not be reflected in the Time Table.</li>
        </ol>
    </div>

    {{-- ═══ COURSE CATEGORY SUMMARY ═══ --}}
    <div class="bg-white {{--rounded-xl shadow p-5 --}} text-xs text-gray-700">
        <p class="font-semibold mb-2">Course Category :</p>
        <div class="flex flex-wrap gap-x-6 gap-y-1">
            @foreach ($categoryCounts as $catName => $count)
                <span><span class="font-semibold">{{ $catName }}</span> : {{ $count }}</span>
            @endforeach
        </div>
    </div>