@extends('layouts.syllabus')

@section('content')
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        Specification Table
    </h3>

    <div class="bg-white p-6 rounded-xl shadow">
        <form method="POST" action="{{ route('expert.syllabus.specification.save', $course->id) }}">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-center border border-gray-200">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th rowspan="2" class="border px-3 py-2">Unit No.</th>
                            <th rowspan="2" class="border px-3 py-2 text-left">Unit Title</th>
                            <th colspan="3" class="border px-3 py-2">Distribution of Theory Marks</th>
                            <th rowspan="2" class="border px-3 py-2">Total</th>
                        </tr>
                        <tr>
                            <th class="border px-3 py-2">R</th>
                            <th class="border px-3 py-2">U</th>
                            <th class="border px-3 py-2">A</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @foreach ($units as $unit)
                            @php
                                $row = $rows[$unit->id] ?? null;
                                $r = $row->remember_marks ?? 0;
                                $u = $row->understand_marks ?? 0;
                                $a = $row->apply_marks ?? 0;
                            @endphp

                            <tr class="hover:bg-gray-50" data-unit-row>
                                <td class="border px-3 py-2">{{ $unit->unit_no }}</td>
                                <td class="border px-3 py-2 text-left">{{ $unit->title }}</td>

                                <td class="border px-3 py-2">
                                    <input type="number" min="0" name="rows[{{ $unit->id }}][r]"
                                        value="{{ $r }}"
                                        class="unit-marks w-16 border border-gray-300 rounded px-2 py-1 text-center focus:ring-2 focus:ring-blue-500">
                                </td>

                                <td class="border px-3 py-2">
                                    <input type="number" min="0" name="rows[{{ $unit->id }}][u]"
                                        value="{{ $u }}"
                                        class="unit-marks w-16 border border-gray-300 rounded px-2 py-1 text-center focus:ring-2 focus:ring-blue-500">
                                </td>

                                <td class="border px-3 py-2">
                                    <input type="number" min="0" name="rows[{{ $unit->id }}][a]"
                                        value="{{ $a }}"
                                        class="unit-marks w-16 border border-gray-300 rounded px-2 py-1 text-center focus:ring-2 focus:ring-blue-500">
                                </td>

                                <td class="border px-3 py-2 font-medium row-total">0</td>
                            </tr>
                        @endforeach

                        {{-- TOTAL ROW --}}
                        <tr class="bg-gray-100 font-semibold">
                            <td colspan="2" class="border px-3 py-2 text-left">TOTAL</td>
                            <td class="border px-3 py-2" id="grand-total-r">0</td>
                            <td class="border px-3 py-2" id="grand-total-u">0</td>
                            <td class="border px-3 py-2" id="grand-total-a">0</td>
                            <td class="border px-3 py-2" id="grand-total">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                    Save
                </button>
            </div>
        </form>
    </div>

    <script>
        function recalculateAll() {
            let grandR = 0,
                grandU = 0,
                grandA = 0;

            document.querySelectorAll('[data-unit-row]').forEach(function(row) {
                const inputs = row.querySelectorAll('.unit-marks');
                const r = parseInt(inputs[0].value) || 0;
                const u = parseInt(inputs[1].value) || 0;
                const a = parseInt(inputs[2].value) || 0;
                const rowTotal = r + u + a;

                row.querySelector('.row-total').textContent = rowTotal;

                grandR += r;
                grandU += u;
                grandA += a;
            });

            document.getElementById('grand-total-r').textContent = grandR;
            document.getElementById('grand-total-u').textContent = grandU;
            document.getElementById('grand-total-a').textContent = grandA;
            document.getElementById('grand-total').textContent = grandR + grandU + grandA;
        }

        // Run on page load to populate totals from existing DB values
        recalculateAll();

        // Re-run on every input change
        document.querySelectorAll('.unit-marks').forEach(function(input) {
            input.addEventListener('input', recalculateAll);
        });
    </script>
@endsection
