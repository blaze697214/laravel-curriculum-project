@extends('layouts.syllabus')

@section('content')

        <h3 class="text-lg text-gray-800 font-semibold mb-4">Suggested Question Paper Profile</h3>

    <div class="bg-white p-6 rounded-xl shadow">


        <form method="POST" action="{{ route('expert.syllabus.qpp.save', $course->id) }}">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Multiplier</label>
                <input type="number" min="0" step="0.01" id="multiplier" name="multiplier"
                    value="{{ $syllabus->question_multiplier }}"
                    class="border border-gray-300 rounded px-3 py-2 w-40 text-sm">
            </div>

            <div class="overflow-x-auto">

                <table class="w-full border border-gray-300 text-sm text-center">

                    <tr class="bg-gray-100">
                        <th rowspan="2" class="border px-2 py-2">Unit No</th>
                        <th rowspan="2" class="border px-2 py-2">CO</th>
                        <th rowspan="2" class="border px-2 py-2">Marks Per Unit</th>
                        <th rowspan="2" class="border px-2 py-2">{{ $syllabus->question_multiplier }} Times marks</th>
                        <th colspan="6" class="border px-2 py-2">Question Number Wise Marks</th>
                        <th rowspan="2" class="border px-2 py-2 text-wrap w-25">Actual Distribution of marks</th>
                    </tr>

                    <tr class="bg-gray-100">
                        <th class="border px-2 py-2">Q1</th>
                        <th class="border px-2 py-2">Q2</th>
                        <th class="border px-2 py-2">Q3</th>
                        <th class="border px-2 py-2">Q4</th>
                        <th class="border px-2 py-2">Q5</th>
                        <th class="border px-2 py-2">Q6</th>
                    </tr>

                    @php
                        $totalMPU = 0;
                        $totalAdj = 0;
                    @endphp

                    @foreach ($units as $unit)
                        @php
                            $spec = $specRows[$unit->id] ?? null;
                            $mpu = $spec->total_marks ?? 0;

                            // ── key by unit_id now ──
                            $row = $rows[$unit->id] ?? null;

                            $adj = $row->adjusted_marks ?? 0;
                            $q1 = $row->q1_marks ?? 0;
                            $q2 = $row->q2_marks ?? 0;
                            $q3 = $row->q3_marks ?? 0;
                            $q4 = $row->q4_marks ?? 0;
                            $q5 = $row->q5_marks ?? 0;
                            $q6 = $row->q6_marks ?? 0;

                            $rowTotal = $q1 + $q2 + $q3 + $q4 + $q5 + $q6;

                            $totalMPU += $mpu;
                            $totalAdj += $adj;
                        @endphp

                        <tr class="hover:bg-gray-50">

                            <td class="border px-2 py-2">
                                {{ $unit->unit_no }}
                                {{-- ── send unit_id as the key, not unit_no ── --}}
                                <input type="hidden" name="rows[{{ $unit->id }}][co_id]"
                                    value="">{{-- placeholder, select below overrides --}}
                            </td>

                            <td class="border px-2 py-2">
                                <select name="rows[{{ $unit->id }}][co_id]"
                                    class="border border-gray-300 rounded px-2 py-1 text-sm">
                                    @foreach ($courseOutcomes as $co)
                                        <option value="{{ $co->id }}"
                                            {{ old('rows.' . $unit->id . '.co_id', $row?->course_outcome_id) == $co->id ? 'selected' : '' }}>
                                            {{ $co->co_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="border px-2 py-2 font-medium">
                                {{ $mpu }}
                            </td>

                            <td class="border px-2 py-2">
                                <input type="number" min="0" name="rows[{{ $unit->id }}][adjusted_marks]"
                                    class="adjusted border border-gray-300 rounded px-2 py-1 w-20 text-center"
                                    data-mpu="{{ $mpu }}"
                                    value="{{ old('rows.' . $unit->id . '.adjusted_marks', $adj) }}">
                            </td>

                            @for ($i = 1; $i <= 6; $i++)
                                @php $qVal = old('rows.' . $unit->id . '.q' . $i, ${"q".$i}); @endphp
                                <td class="border px-2 py-2">
                                    <input type="number" min="0"
                                        name="rows[{{ $unit->id }}][q{{ $i }}]"
                                        class="q border border-gray-300 rounded px-2 py-1 w-16 text-center"
                                        value="{{ $qVal }}">
                                </td>
                            @endfor

                            <td class="border px-2 py-2 font-semibold rowTotal">
                                {{ $rowTotal }}
                            </td>

                        </tr>
                    @endforeach

                    <tr class="bg-gray-100 font-semibold">
                        <td colspan="2" class="border px-2 py-2">Total</td>

                        <td id="totalMPU" class="border px-2 py-2">{{ $totalMPU }}</td>
                        <td id="totalAdj" class="border px-2 py-2">{{ $totalAdj }}</td>

                        @for ($i = 1; $i <= 6; $i++)
                            <td class="border px-2 py-2 colTotal" data-col="{{ $i }}">0</td>
                        @endfor

                        <td id="grandTotal" class="border px-2 py-2">0</td>
                    </tr>

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
        // ================= MULTIPLIER LOGIC =================
        function applyMultiplier(multiplier, forceAll = false) {
            document.querySelectorAll('.adjusted').forEach(input => {
                let mpu = parseFloat(input.dataset.mpu) || 0;
                let newVal = Math.ceil(mpu * multiplier);

                // Auto-fill only if:
                // 1. forceAll is true (initial page load, value was zero/empty from DB)
                // 2. OR the field is currently in "auto" mode (not manually edited)
                if (forceAll || input.dataset.auto === "1") {
                    input.value = newVal;
                    input.dataset.auto = "1";
                }
            });
            updateTotals();
        }

        document.getElementById('multiplier').addEventListener('input', function() {
            let multiplier = parseFloat(this.value) || 1;
            applyMultiplier(multiplier, false);
        });

        // ================= ON PAGE LOAD =================
        // For each adjusted input: if value is 0 or empty, mark as auto and fill it
        document.querySelectorAll('.adjusted').forEach(input => {
            let savedVal = parseInt(input.value) || 0;
            if (savedVal === 0) {
                input.dataset.auto = "1"; // no saved value → auto mode
            } else {
                input.dataset.auto = "0"; // has a saved value → manual mode, don't overwrite
            }
        });

        // Now apply multiplier for those in auto mode
        let initialMultiplier = parseFloat(document.getElementById('multiplier').value) || 1;
        applyMultiplier(initialMultiplier, false);


        // ================= Q TOTAL =================
        document.querySelectorAll('.q').forEach(input => {
            input.addEventListener('input', updateTotals);
        });

        document.querySelectorAll('.adjusted').forEach(input => {
            input.addEventListener('input', function() {
                this.dataset.auto = "0"; // user is manually editing → lock it
                updateTotals();
            });
        });


        function updateTotals() {
            let totalAdj = 0;
            let grandTotal = 0;
            let colTotals = [0, 0, 0, 0, 0, 0];

            document.querySelectorAll('table tr').forEach((row) => {
                let qInputs = row.querySelectorAll('.q');

                if (qInputs.length === 6) {
                    let rowSum = 0;

                    qInputs.forEach((q, i) => {
                        let val = parseInt(q.value) || 0;
                        rowSum += val;
                        colTotals[i] += val;
                    });

                    row.querySelector('.rowTotal').innerText = rowSum;
                    grandTotal += rowSum;

                    let adjInput = row.querySelector('.adjusted');
                    totalAdj += parseInt(adjInput.value) || 0;
                }
            });

            document.querySelectorAll('.colTotal').forEach((td, i) => {
                td.innerText = colTotals[i];
            });

            document.getElementById('totalAdj').innerText = totalAdj;
            document.getElementById('grandTotal').innerText = grandTotal;
        }

        // initial totals calc
        updateTotals();
    </script>
@endsection
