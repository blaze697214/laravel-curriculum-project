@extends('layouts.syllabus')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-lg font-semibold mb-4">Question Bits</h2>

    <form method="POST" action="{{ route('expert.syllabus.qb.save', $course->id) }}">
        @csrf
        <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 text-sm text-center">

                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-2 py-2">Unit</th>
                        <th class="border px-2 py-2">CO</th>
                        <th class="border px-2 py-2 text-wrap w-22">Marks per Unit</th>
                        <th class="border px-2 py-2 text-wrap w-22">{{ $syllabus->question_multiplier }} Times marks</th>    
                        <th class="border px-2 py-2">Question</th>
                        <th class="border px-2 py-2">a</th>
                        <th class="border px-2 py-2">b</th>
                        <th class="border px-2 py-2">c</th>
                        <th class="border px-2 py-2">d</th>
                        <th class="border px-2 py-2">e</th>
                        <th class="border px-2 py-2">f</th>
                        <th class="border px-2 py-2">Total</th>
                        <th class="border px-2 py-2">Expected</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($qppRows as $row)
                    @php
                        $unitId   = $row->syllabus_unit_id;
                        $unitNo   = $row->syllabusUnit->unit_no ?? $unitId;
                        $coCode   = $row->courseOutcome->co_code ?? '—';
                        $mpu      = $row->marks_per_unit;
                        $adj      = $row->adjusted_marks;

                        // actual distribution = sum of q1..q6 (not stored, calculated)
                        $actualDist = (int)($row->q1_marks ?? 0)
                                    + (int)($row->q2_marks ?? 0)
                                    + (int)($row->q3_marks ?? 0)
                                    + (int)($row->q4_marks ?? 0)
                                    + (int)($row->q5_marks ?? 0)
                                    + (int)($row->q6_marks ?? 0);
                    @endphp

                    @for ($q = 1; $q <= 6; $q++)
                        @php
                            $expected = (int) ($row->{'q'.$q.'_marks'} ?? 0);
                        @endphp

                        <tr class="hover:bg-gray-50" data-unit="{{ $unitId }}">

                            @if ($q == 1)
                                <td rowspan="7" class="border px-2 py-2 font-semibold align-middle bg-gray-50">
                                    {{ $unitNo }}
                                </td>
                                <td rowspan="7" class="border px-2 py-2 align-middle text-gray-600 bg-gray-50">
                                    {{ $coCode }}
                                </td>
                                <td rowspan="7" class="border px-2 py-2 align-middle text-gray-600 bg-gray-50">
                                    {{ $mpu }}
                                </td>
                                <td rowspan="7" class="border px-2 py-2 align-middle text-gray-600 bg-gray-50">
                                    {{ $adj }}
                                </td>
                            @endif

                            <td class="border px-2 py-2 font-medium text-gray-700">
                                Q{{ $q }}
                                @if($expected > 0)
                                    <span class="text-xs text-gray-400">({{ $expected }})</span>
                                @endif
                            </td>

                            @foreach (['a','b','c','d','e','f'] as $bit)
                                @php $val = $bits[$unitId][$q][$bit][0]->marks ?? ''; @endphp
                                <td class="border px-1 py-1">
                                    <input type="number" min="0"
                                        name="bits[{{ $unitId }}][{{ $q }}][{{ $bit }}]"
                                        value="{{ $val }}"
                                        placeholder="0"
                                        class="bit-input w-14 border border-gray-300 rounded px-1 py-1 text-center text-sm"
                                        data-unit="{{ $unitId }}">
                                </td>
                            @endforeach

                            <td class="border px-2 py-2 font-semibold row-total"
                                data-expected="{{ $expected }}">
                                0
                            </td>

                            <td class="border px-2 py-2 text-gray-500">
                                {{ $expected ?: '—' }}
                            </td>

                        </tr>
                    @endfor

                    {{-- Sub-total row: spans Unit + CO cols too since they're rowspan=7 above --}}
                    <tr class="bg-blue-50 font-semibold" data-unit="{{ $unitId }}">
                        <td colspan="7" class="border px-2 py-2 text-left text-gray-600">
                            Sub Total
                        </td>
                        <td class="border px-2 py-2 unit-subtotal"
                            data-unit="{{ $unitId }}"
                            data-expected="{{ $actualDist }}">
                            0
                        </td>
                        <td class="border px-2 py-2 text-gray-500 font-normal">
                            {{ $actualDist }}
                        </td>
                    </tr>

                @endforeach

                <tr class="bg-gray-200 font-bold">
                    <td colspan="11" class="border px-2 py-2 text-right">Grand Total</td>
                    <td id="grandTotal" class="border px-2 py-2 text-center">0</td>
                    <td class="border px-2 py-2 text-center">
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
</div>

<script>
function updateTotals() {

    let grandTotal = 0;

    document.querySelectorAll('tr[data-unit]').forEach(row => {

        let inputs = row.querySelectorAll('.bit-input');
        if (inputs.length === 0) return;

        let rowSum = 0;
        inputs.forEach(i => rowSum += parseInt(i.value) || 0);

        let totalCell = row.querySelector('.row-total');
        if (!totalCell) return;

        totalCell.innerText = rowSum;

        let expected = parseInt(totalCell.dataset.expected) || 0;

        if (expected === 0) {
            totalCell.className = 'border px-2 py-2 font-semibold row-total text-gray-500';
        } else if (rowSum === expected) {
            totalCell.className = 'border px-2 py-2 font-semibold row-total text-green-600';
        } else {
            totalCell.className = 'border px-2 py-2 font-semibold row-total text-red-500';
        }
    });

    document.querySelectorAll('.unit-subtotal').forEach(cell => {

        let unit     = cell.dataset.unit;
        let expected = parseInt(cell.dataset.expected) || 0;
        let sum      = 0;

        document.querySelectorAll('.row-total').forEach(tc => {
            let tr = tc.closest('tr');
            if (tr && tr.dataset.unit === unit) {
                sum += parseInt(tc.innerText) || 0;
            }
        });

        cell.innerText = sum;
        grandTotal += sum;

        cell.className = (sum === expected)
            ? 'border px-2 py-2 unit-subtotal font-semibold text-green-600'
            : 'border px-2 py-2 unit-subtotal font-semibold text-red-500';
    });

    document.getElementById('grandTotal').innerText = grandTotal;
}

document.querySelectorAll('.bit-input').forEach(i => i.addEventListener('input', updateTotals));

updateTotals();
</script>

@endsection