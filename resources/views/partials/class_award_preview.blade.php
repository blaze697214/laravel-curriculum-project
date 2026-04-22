{{-- ═══ TITLE ═══ --}}
    <div class="text-center mb-4">
        <h2 class="text-base font-bold uppercase tracking-wide">
            PROGRAMME - {{ strtoupper($department->name) }}
        </h2>
        <h3 class="text-sm font-semibold mt-1">Courses for Award of Class</h3>
    </div>

    {{-- ═══ TABLE ═══ --}}
    <div class="bg-white shadow overflow-x-auto mb-4">
        <table class="text-xs border-collapse" style="min-width:1200px; width:100%;">

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
                    // Running totals
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

                {{-- ── COMPULSORY COURSES ── --}}
                @foreach ($compulsoryCourses as $offering)
                    @php
                        $cm = $offering->courseMaster;

                        $iks = $cm->iks_hours ?? 0;
                        $cl = $cm->cl_hours ?? 0;
                        $tl = $cm->tl_hours ?? 0;
                        $ll = $cm->ll_hours ?? 0;
                        $sl = $cm->sla_hours ?? 0;
                        $nlh = $cl + $tl + $ll + $sl;
                        $cred = $cm->credits ?? 0;
                        $pd = $cm->paper_duration ?? 0;

                        $faTh = $cm->fa_th ?? 0;
                        $faThMin = $faTh > 0 ? intval($faTh * 0.4) : 0;
                        $saTh = $cm->sa_th ?? 0;
                        $saThMin = $saTh > 0 ? intval($saTh * 0.4) : 0;
                        $thTotal = $faTh + $saTh;
                        $thMin = $thTotal > 0 ? intval($thTotal * 0.4) : 0;
                        $faPr = $cm->fa_pr ?? 0;
                        $faPrMin = $faPr ? intval($faPr * 0.4) : 0;
                        $saPr = $cm->sa_pr ?? 0;
                        $saPrMin = $saPr ? intval($saPr * 0.4) : 0;
                        $sla = $cm->sla_marks ?? 0;
                        $slaMin = $sla ? intval($sla * 0.4) : 0;
                        $total = $cm->total_marks ?? $faTh + $saTh + $faPr + $saPr + $sla;

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
                    @endphp

                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-400 px-1 py-1">{{ $srNo++ }}</td>
                        <td class="border border-gray-400 px-2 py-1 text-left">{{ $cm->title }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $cm->abbreviation }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $cm->category?->abbreviation ?? '—' }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $cm->course_code ?? '—' }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $iks ?: '0' }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $cl ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $tl ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $ll ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $sl ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $nlh ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1 font-semibold">{{ $cred }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $pd ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $faTh ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $saTh ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $thTotal ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $thMin ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $faPr ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $faPr ? $faPrMin : '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $saPr ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $saPr ? $saPrMin : '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1">{{ $sla ?: '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $sla ? $slaMin : '-' }}</td>
                        <td class="border border-gray-400 px-1 py-1 font-bold">{{ $total }}</td>
                    </tr>
                @endforeach

                {{-- ── ELECTIVE GROUPS ── --}}
                @foreach ($electiveGroups as $group)
                    @php
                        // Use first course of group for totals (one elective is selected)
                        $firstCm = $group['courses']->first();
                        if ($firstCm) {
                            $iks = $firstCm->iks_hours ?? 0;
                            $cl = $firstCm->cl_hours ?? 0;
                            $tl = $firstCm->tl_hours ?? 0;
                            $ll = $firstCm->ll_hours ?? 0;
                            $sl = $firstCm->sla_hours ?? 0;
                            $nlh = $cl + $tl + $ll + $sl;
                            $cred = $firstCm->credits ?? 0;
                            $faTh = $firstCm->fa_th ?? 0;
                            $saTh = $firstCm->sa_th ?? 0;
                            $thTotal = $faTh + $saTh;
                            $thMin = $thTotal > 0 ? intval($thTotal * 0.4) : 0;
                            $faPr = $firstCm->fa_pr ?? 0;
                            $faPrMin = $faPr ? intval($faPr * 0.4) : 0;
                            $saPr = $firstCm->sa_pr ?? 0;
                            $saPrMin = $saPr ? intval($saPr * 0.4) : 0;
                            $sla = $firstCm->sla_marks ?? 0;
                            $slaMin = $sla ? intval($sla * 0.4) : 0;
                            $total = $firstCm->total_marks ?? $faTh + $saTh + $faPr + $saPr + $sla;

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
                    @endphp

                    {{-- Elective group label row --}}
                    <tr class="bg-gray-50 font-semibold">
                        <td class="border border-gray-400 px-2 py-1">{{ $srNo++ }}</td>
                        <td class="border border-gray-400 px-2 py-1 text-left" colspan="23">
                            {{ $group['name'] }} (any one)
                        </td>
                    </tr>

                    {{-- Individual elective course rows (no Sr No) --}}
                    @foreach ($group['courses'] as $cm)
                        @php
                            $iks2 = $cm->iks_hours ?? 0;
                            $cl2 = $cm->cl_hours ?? 0;
                            $tl2 = $cm->tl_hours ?? 0;
                            $ll2 = $cm->ll_hours ?? 0;
                            $sl2 = $cm->sla_hours ?? 0;
                            $nlh2 = $cl2 + $tl2 + $ll2 + $sl2;
                            $cred2 = $cm->credits ?? 0;
                            $pd2 = $cm->paper_duration ?? 0;
                            $faTh2 = $firstCm->fa_th ?? 0;
                            $saTh2 = $firstCm->sa_th ?? 0;
                            $thTotal2 = $faTh2 + $saTh2;
                            $thMin2 = $thTotal2 > 0 ? intval($thTotal2 * 0.4) : 0;
                            $faPr2 = $cm->fa_pr ?? 0;
                            $faPrMin2 = $faPr2 ? intval($faPr2 * 0.4) : 0;
                            $saPr2 = $cm->sa_pr ?? 0;
                            $saPrMin2 = $saPr2 ? intval($saPr2 * 0.4) : 0;
                            $sla2 = $cm->sla_marks ?? 0;
                            $slaMin2 = $sla2 ? intval($sla2 * 0.4) : 0;
                            $total2 = $cm->total_marks ?? $faTh2 + $saTh2 + $faPr2 + $saPr2 + $sla2;
                        @endphp
                        <tr class="hover:bg-yellow-50 bg-yellow-50/40">
                            <td class="border border-gray-400 px-1 py-1"></td>
                            <td class="border border-gray-400 px-2 py-1 text-left pl-6">{{ $cm->title }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $cm->abbreviation }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $cm->category?->abbreviation ?? '—' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $cm->course_code ?? '—' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $iks2 ?: '0' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $cl2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $tl2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $ll2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $sl2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $nlh2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1 font-semibold">{{ $cred2 }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $pd2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $faTh2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $saTh2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $thTotal2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $thMin2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $faPr2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $faPr2 ? $faPrMin2 : '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $saPr2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $saPr2 ? $saPrMin2 : '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1">{{ $sla2 ?: '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1 text-gray-500">{{ $sla2 ? $slaMin2 : '-' }}</td>
                            <td class="border border-gray-400 px-1 py-1 font-bold">{{ $total2 }}</td>
                        </tr>
                    @endforeach
                @endforeach

                {{-- ── TOTAL ROW ── --}}
                <tr class="bg-gray-200 font-bold text-center">
                    <td colspan="5" class="border border-gray-400 px-2 py-1 text-right">Total</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totIks ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totCL ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totTL ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totLL ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totSL ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totNLH ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totCredits }}</td>
                    <td class="border border-gray-400 px-1 py-1"></td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totFaTh ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totSaTh ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totThTotal ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totThMin ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totFaPr ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totFaPrMin ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totSaPr ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totSaPrMin ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totSla ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totSlaMin ?: '-' }}</td>
                    <td class="border border-gray-400 px-1 py-1">{{ $totMarks }}</td>
                </tr>

            </tbody>
        </table>
    </div>