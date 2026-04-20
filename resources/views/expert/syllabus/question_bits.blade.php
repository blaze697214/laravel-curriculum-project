@extends('layouts.syllabus')

@section('content')
    <h3 class="text-lg text-gray-800 font-semibold mb-4">Question Bits</h3>

    <div class="bg-white p-6 rounded-xl shadow">

        @if (count($qppRows)>0)
            <form method="POST" action="{{ route('expert.syllabus.qb.save', $course->id) }}">
                @csrf
                <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

                <div class="overflow-x-auto">
                    <table class="border border-gray-300 text-sm text-center w-full">

                        {{-- ==================== HEADER ==================== --}}
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-2 py-2 align-middle">Unit</th>
                                <th class="border px-2 py-2 align-middle">CO</th>
                                <th class="border px-2 py-2 align-middle text-wrap w-20">Marks<br>per Unit</th>
                                <th class="border px-2 py-2 align-middle text-wrap w-20">
                                    {{ $syllabus->question_multiplier }}&times;<br>marks</th>
                                <th class="border px-2 py-2 align-middle">Bit</th>

                                @foreach ($qppRows as $row)
                                    @for ($q = 1; $q <= 6; $q++)
                                        @php $qMark = (int)($row->{'q'.$q.'_marks'} ?? 0); @endphp
                                        @if ($loop->first)
                                            <th class="border px-2 py-1 align-middle bg-blue-50" colspan="1"
                                                data-qno="{{ $q }}">
                                                Q{{ $q }}
                                            </th>
                                        @endif
                                    @endfor
                                @break
                            @endforeach

                            <th class="border px-2 py-2 align-middle bg-yellow-50 w-20">Unit<br>Total</th>
                            <th class="border px-2 py-2 align-middle bg-yellow-50 w-20">Expected<br>(Actual
                                Dist)</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @foreach ($qppRows as $row)
                            @php
                                $unitId = $row->syllabus_unit_id;
                                $unitNo = $row->syllabusUnit->unit_no ?? $unitId;
                                $coCode = $row->courseOutcome->co_code ?? '—';
                                $mpu = $row->marks_per_unit;
                                $adj = $row->adjusted_marks;
                                $bits_list = ['a', 'b', 'c', 'd', 'e', 'f', 'g'];
                                $numBits = count($bits_list);

                                $actualDist =
                                    (int) ($row->q1_marks ?? 0) +
                                    (int) ($row->q2_marks ?? 0) +
                                    (int) ($row->q3_marks ?? 0) +
                                    (int) ($row->q4_marks ?? 0) +
                                    (int) ($row->q5_marks ?? 0) +
                                    (int) ($row->q6_marks ?? 0);

                                // Expected per Q for this unit
                                $qExpected = [];
                                for ($q = 1; $q <= 6; $q++) {
                                    $qExpected[$q] = (int) ($row->{'q' . $q . '_marks'} ?? 0);
                                }
                            @endphp

                            {{-- One row per bit label --}}
                            @foreach ($bits_list as $bIdx => $bit)
                                <tr class="hover:bg-gray-50" data-unit="{{ $unitId }}"
                                    data-bit="{{ $bit }}">

                                    {{-- Fixed left columns — rowspan over all bits --}}
                                    @if ($bIdx === 0)
                                        <td rowspan="{{ $numBits + 1 }}"
                                            class="border px-2 py-2 font-semibold align-middle bg-gray-50 text-center">
                                            {{ $unitNo }}
                                        </td>
                                        <td rowspan="{{ $numBits + 1 }}"
                                            class="border px-2 py-2 align-middle text-gray-600 bg-gray-50 text-center">
                                            {{ $coCode }}
                                        </td>
                                        <td rowspan="{{ $numBits + 1 }}"
                                            class="border px-2 py-2 align-middle text-gray-600 bg-gray-50 text-center">
                                            {{ $mpu }}
                                        </td>
                                        <td rowspan="{{ $numBits + 1 }}"
                                            class="border px-2 py-2 align-middle text-gray-600 bg-gray-50 text-center">
                                            {{ $adj }}
                                        </td>
                                    @endif

                                    {{-- Bit label --}}
                                    <td class="border px-2 py-2 font-medium text-gray-700 bg-gray-50">
                                        {{ $bit }}
                                    </td>

                                    {{-- Input cells: one per Q (Q1..Q6) --}}
                                    @for ($q = 1; $q <= 6; $q++)
                                        @php
                                            $val = $bits[$unitId][$q][$bit][0]->marks ?? '';
                                        @endphp
                                        <td class="border px-1 py-1">
                                            <input type="number" min="0"
                                                name="bits[{{ $unitId }}][{{ $q }}][{{ $bit }}]"
                                                value="{{ old('bits.' . $unitId . '.' . $q . '.' . $bit, $val) }}"
                                                placeholder="0"
                                                class="bit-input w-14 border border-gray-300 rounded px-1 py-1 text-center text-sm"
                                                data-unit="{{ $unitId }}" data-q="{{ $q }}"
                                                data-bit="{{ $bit }}">
                                        </td>
                                    @endfor

                                    {{-- Row total (sum across Q1..Q6 for this bit in this unit) --}}
                                    <td class="border px-2 py-2 font-semibold bit-row-total bg-yellow-50"
                                        data-unit="{{ $unitId }}" data-bit="{{ $bit }}">
                                        0
                                    </td>

                                    {{-- No expected per-bit row; leave blank --}}
                                    @if ($loop->first)
                                        <td rowspan="8"
                                            class="border px-2 py-2 text-gray-800 font-semibold bg-yellow-50">
                                            {{ $actualDist }}
                                        </td>
                                    @endif

                                </tr>
                            @endforeach

                            {{-- Sub-total row per unit: sum of all bits per Q (vertical per unit), and grand unit total --}}
                            <tr class="bg-blue-50 font-semibold" data-unit-subtotal="{{ $unitId }}">

                                {{-- "Sub Total" label (no Unit/CO/MPU/Adj cols — they are rowspan'd above) --}}
                                <td class="border px-2 py-2 text-left text-gray-700 bg-blue-100">
                                    Sub Total
                                </td>

                                {{-- Per-Q subtotal cells --}}
                                @for ($q = 1; $q <= 6; $q++)
                                    <td class="border px-2 py-2 q-subtotal font-semibold"
                                        data-unit="{{ $unitId }}" data-q="{{ $q }}"
                                        data-expected="{{ $qExpected[$q] }}">
                                        0
                                    </td>
                                @endfor

                                {{-- Unit horizontal total --}}
                                <td class="border px-2 py-2 unit-total font-bold bg-yellow-100"
                                    data-unit="{{ $unitId }}" data-expected="{{ $actualDist }}">
                                    0
                                </td>

                                {{-- Expected actual distribution --}}
                                {{-- <td class="border px-2 py-2 text-gray-600 bg-yellow-50">
                                {{ $actualDist }}
                            </td> --}}

                            </tr>
                        @endforeach

                        {{-- ==================== GRAND TOTAL ROW ==================== --}}
                        <tr class="bg-gray-200 font-bold">
                            <td colspan="5" class="border px-2 py-2 text-center">Grand Total</td>

                            @for ($q = 1; $q <= 6; $q++)
                                @php
                                    $qGrandExpected = $qppRows->sum(fn($r) => (int) ($r->{'q' . $q . '_marks'} ?? 0));
                                @endphp
                                <td class="border px-2 py-2 grand-q-total" id="grand-q-{{ $q }}"
                                    data-expected="{{ $qGrandExpected }}">
                                    0
                                </td>
                            @endfor

                            <td id="grandTotal" class="border px-2 py-2 bg-yellow-200">0</td>

                            <td class="border px-2 py-2 bg-yellow-100">
                                {{ $qppRows->sum(fn($r) => ($r->q1_marks ?? 0) + ($r->q2_marks ?? 0) + ($r->q3_marks ?? 0) + ($r->q4_marks ?? 0) + ($r->q5_marks ?? 0) + ($r->q6_marks ?? 0)) }}
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded text-sm">
                    Save
                </button>
            </div>
        </form>
        @else<div class="flex justify-center h-40 items-center text-xl font-semibold text-gray-400">
            Fill out the Question Paper Profile to access this page...
        </div>
        @endif
    </div>

<script>
    function updateTotals() {

        // ── 1. Bit-row totals (horizontal: sum across Q1..Q6 for each bit row) ──────
        document.querySelectorAll('tr[data-unit][data-bit]').forEach(row => {
            let unit = row.dataset.unit;
            let bit = row.dataset.bit;
            let sum = 0;

            row.querySelectorAll('.bit-input').forEach(inp => {
                sum += parseInt(inp.value) || 0;
            });

            let cell = row.querySelector('.bit-row-total');
            if (cell) cell.innerText = sum;
        });

        // ── 2. Q subtotals (vertical per unit: sum across all bits for each Q) ──────
        document.querySelectorAll('.q-subtotal').forEach(cell => {
            let unit = cell.dataset.unit;
            let q = cell.dataset.q;
            let expected = parseInt(cell.dataset.expected) || 0;
            let sum = 0;

            document.querySelectorAll(
                `.bit-input[data-unit="${unit}"][data-q="${q}"]`
            ).forEach(inp => {
                sum += parseInt(inp.value) || 0;
            });

            cell.innerText = sum;

            if (expected === 0) {
                cell.className = 'border px-2 py-2 q-subtotal font-semibold text-gray-500';
            } else if (sum === expected) {
                cell.className = 'border px-2 py-2 q-subtotal font-semibold text-green-600';
            } else {
                cell.className = 'border px-2 py-2 q-subtotal font-semibold text-red-500';
            }
        });

        // ── 3. Unit total (horizontal: sum across all bit-rows for this unit) ────────
        let grandTotal = 0;

        document.querySelectorAll('.unit-total').forEach(cell => {
            let unit = cell.dataset.unit;
            let expected = parseInt(cell.dataset.expected) || 0;
            let sum = 0;

            document.querySelectorAll(`.bit-input[data-unit="${unit}"]`).forEach(inp => {
                sum += parseInt(inp.value) || 0;
            });

            cell.innerText = sum;
            grandTotal += sum;

            if (expected === 0) {
                cell.className = 'border px-2 py-2 unit-total font-bold bg-yellow-100 text-gray-500';
            } else if (sum === expected) {
                cell.className = 'border px-2 py-2 unit-total font-bold bg-yellow-100 text-green-600';
            } else {
                cell.className = 'border px-2 py-2 unit-total font-bold bg-yellow-100 text-red-500';
            }
        });

        // ── 4. Grand Q totals (sum across ALL units for each Q) ─────────────────────
        for (let q = 1; q <= 6; q++) {
            let cell = document.getElementById('grand-q-' + q);
            if (!cell) continue;
            let expected = parseInt(cell.dataset.expected) || 0;
            let sum = 0;

            document.querySelectorAll(`.bit-input[data-q="${q}"]`).forEach(inp => {
                sum += parseInt(inp.value) || 0;
            });

            cell.innerText = sum;

            if (expected === 0) {
                cell.className = 'border px-2 py-2 grand-q-total text-gray-500';
            } else if (sum === expected) {
                cell.className = 'border px-2 py-2 grand-q-total text-green-600';
            } else {
                cell.className = 'border px-2 py-2 grand-q-total text-red-500';
            }
        }

        // ── 5. Grand total ────────────────────────────────────────────────────────────
        document.getElementById('grandTotal').innerText = grandTotal;
    }

    document.querySelectorAll('.bit-input').forEach(i => i.addEventListener('input', updateTotals));

    // Run on load to populate from saved values
    updateTotals();
</script>
@endsection
