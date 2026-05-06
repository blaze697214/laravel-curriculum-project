@extends('layouts.hod')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">
    Scheme At Glance
</h1>

<form method="POST" action="{{ route('hod.scheme.update') }}">

    @csrf
    <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

    {{-- ── COURSE CATEGORIES ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">Course Category Configuration</h2>
            <p class="text-sm text-gray-500 mt-1">Configure course distribution, credits and marks structure.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold border-b border-gray-200">Type</th>
                        <th class="px-4 py-3 text-left font-semibold border-b border-gray-200">Name</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">Offered</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">Required</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">TH Hrs</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">TU Hrs</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">PR Hrs</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">Credits</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">Marks</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100" id="cat-tbody">

                    @foreach($courseCategories as $category)

                        @php
                            $existing = $departmentCategories[$category->id] ?? null;

                            $row = old("categories.{$category->id}", [
                                'courses_offered'     => $existing->courses_offered     ?? '',
                                'courses_to_complete' => $existing->courses_to_complete ?? '',
                                'th_hrs'              => $existing->th_hrs              ?? '',
                                'tu_hrs'              => $existing->tu_hrs              ?? '',
                                'pr_hrs'              => $existing->pr_hrs              ?? '',
                                'credits'             => $existing->credits             ?? '',
                                'marks'               => $existing->marks               ?? '',
                            ]);
                        @endphp

                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-4 py-3 font-semibold text-gray-700 whitespace-nowrap">
                                {{ $category->abbreviation }}
                            </td>

                            <td class="px-4 py-3 min-w-48 text-gray-700">
                                {{ $category->name }}
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="categories[{{ $category->id }}][courses_offered]"
                                    value="{{ $row['courses_offered'] }}"
                                    data-col="cat-offered"
                                    class="cat-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="categories[{{ $category->id }}][courses_to_complete]"
                                    value="{{ $row['courses_to_complete'] }}"
                                    data-col="cat-required"
                                    class="cat-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="categories[{{ $category->id }}][th_hrs]"
                                    value="{{ $row['th_hrs'] }}"
                                    data-col="cat-th"
                                    class="cat-input w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="categories[{{ $category->id }}][tu_hrs]"
                                    value="{{ $row['tu_hrs'] }}"
                                    data-col="cat-tu"
                                    class="cat-input w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="categories[{{ $category->id }}][pr_hrs]"
                                    value="{{ $row['pr_hrs'] }}"
                                    data-col="cat-pr"
                                    class="cat-input w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="categories[{{ $category->id }}][credits]"
                                    value="{{ $row['credits'] }}"
                                    data-col="cat-credits"
                                    class="cat-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="categories[{{ $category->id }}][marks]"
                                    value="{{ $row['marks'] }}"
                                    data-col="cat-marks"
                                    class="cat-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                        </tr>

                    @endforeach

                </tbody>

                <tfoot class="bg-gray-50 border-t-2 border-gray-300 font-semibold text-gray-700">
                    <tr>
                        <td colspan="2" class="px-4 py-3 text-right font-bold">Total</td>
                        <td class="px-3 py-3 text-center" id="cat-total-offered">0</td>
                        <td class="px-3 py-3 text-center" id="cat-total-required">0</td>
                        <td class="px-3 py-3 text-center" id="cat-total-th">0</td>
                        <td class="px-3 py-3 text-center" id="cat-total-tu">0</td>
                        <td class="px-3 py-3 text-center" id="cat-total-pr">0</td>
                        <td class="px-3 py-3 text-center font-bold text-blue-700" id="cat-total-credits">0</td>
                        <td class="px-3 py-3 text-center font-bold text-green-700" id="cat-total-marks">0</td>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>


    {{-- ── EXIT COURSES ───────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mt-8 overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Exit Courses</h2>
                <p class="text-sm text-gray-500 mt-1">Configure certification exits and completion requirements.</p>
            </div>
            <button type="button" onclick="addExitRow()"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                + Add Row
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold border-b border-gray-200">Title</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">Offered</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">Required</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">TH Hrs</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">TU Hrs</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">PR Hrs</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">Credits</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200">Marks</th>
                        <th class="px-4 py-3 text-center font-semibold border-b border-gray-200 w-16">Remove</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100" id="exit-tbody">

                    @forelse($exitCourses as $i => $exit)
                        <tr class="hover:bg-gray-50 transition exit-row">

                            <td class="px-4 py-3">
                                <input type="text"
                                    name="exit_courses[{{ $i }}][title]"
                                    value="{{ old("exit_courses.$i.title", $exit->title) }}"
                                    placeholder="e.g. Exit after Semester 2"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="exit_courses[{{ $i }}][courses_offered]"
                                    value="{{ old("exit_courses.$i.courses_offered", $exit->courses_offered) }}"
                                    data-col="exit-offered"
                                    class="exit-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="exit_courses[{{ $i }}][courses_to_complete]"
                                    value="{{ old("exit_courses.$i.courses_to_complete", $exit->courses_to_complete) }}"
                                    data-col="exit-required"
                                    class="exit-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="exit_courses[{{ $i }}][th_hrs]"
                                    value="{{ old("exit_courses.$i.th_hrs", $exit->th_hrs) }}"
                                    data-col="exit-th"
                                    class="exit-input w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="exit_courses[{{ $i }}][tu_hrs]"
                                    value="{{ old("exit_courses.$i.tu_hrs", $exit->tu_hrs) }}"
                                    data-col="exit-tu"
                                    class="exit-input w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="exit_courses[{{ $i }}][pr_hrs]"
                                    value="{{ old("exit_courses.$i.pr_hrs", $exit->pr_hrs) }}"
                                    data-col="exit-pr"
                                    class="exit-input w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="exit_courses[{{ $i }}][credits]"
                                    value="{{ old("exit_courses.$i.credits", $exit->credits) }}"
                                    data-col="exit-credits"
                                    class="exit-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" min="0"
                                    name="exit_courses[{{ $i }}][marks]"
                                    value="{{ old("exit_courses.$i.marks", $exit->marks) }}"
                                    data-col="exit-marks"
                                    class="exit-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>

                            <td class="px-3 py-3 text-center">
                                <button type="button" onclick="removeExitRow(this)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                    Remove
                                </button>
                            </td>

                        </tr>
                    @empty
                        {{-- If no saved rows, start with one blank row --}}
                        <tr class="hover:bg-gray-50 transition exit-row">
                            <td class="px-4 py-3">
                                <input type="text" name="exit_courses[0][title]"
                                    placeholder="e.g. Exit after Semester 2"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>
                            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[0][courses_offered]"    data-col="exit-offered"  class="exit-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
                            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[0][courses_to_complete]" data-col="exit-required" class="exit-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
                            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[0][th_hrs]"              data-col="exit-th"       class="exit-input w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
                            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[0][tu_hrs]"              data-col="exit-tu"       class="exit-input w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
                            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[0][pr_hrs]"              data-col="exit-pr"       class="exit-input w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
                            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[0][credits]"             data-col="exit-credits"  class="exit-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
                            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[0][marks]"               data-col="exit-marks"    class="exit-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
                            <td class="px-3 py-3 text-center">
                                <button type="button" onclick="removeExitRow(this)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforelse

                </tbody>

                <tfoot class="bg-gray-50 border-t-2 border-gray-300 font-semibold text-gray-700">
                    <tr>
                        <td class="px-4 py-3 text-right font-bold">Total</td>
                        <td class="px-3 py-3 text-center" id="exit-total-offered">0</td>
                        <td class="px-3 py-3 text-center" id="exit-total-required">0</td>
                        <td class="px-3 py-3 text-center" id="exit-total-th">0</td>
                        <td class="px-3 py-3 text-center" id="exit-total-tu">0</td>
                        <td class="px-3 py-3 text-center" id="exit-total-pr">0</td>
                        <td class="px-3 py-3 text-center font-bold text-blue-700" id="exit-total-credits">0</td>
                        <td class="px-3 py-3 text-center font-bold text-green-700" id="exit-total-marks">0</td>
                        <td></td>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>


    {{-- VALIDATION ERRORS --}}
    @error('credits')
        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-600 text-sm">
            {{ $message }}
        </div>
    @enderror

    @error('marks')
        <div class="mt-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-600 text-sm">
            {{ $message }}
        </div>
    @enderror


    {{-- ACTION --}}
    <div class="mt-8 flex justify-between items-center">

        <a href="{{ route('hod.scheme.index') }}">
            <button type="button"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-5 py-2.5 rounded-lg transition">
                ← Back
            </button>
        </a>

        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 transition text-white font-medium px-6 py-2.5 rounded-lg shadow-sm">
            Save Scheme Configuration
        </button>

    </div>

</form>


<script>
    // ── CATEGORY TOTALS ───────────────────────────────────────────────────────

    const CAT_COLS = {
        'cat-offered':  'cat-total-offered',
        'cat-required': 'cat-total-required',
        'cat-th':       'cat-total-th',
        'cat-tu':       'cat-total-tu',
        'cat-pr':       'cat-total-pr',
        'cat-credits':  'cat-total-credits',
        'cat-marks':    'cat-total-marks',
    };

    function recalcCatTotals() {
        Object.entries(CAT_COLS).forEach(([col, footerId]) => {
            let sum = 0;
            document.querySelectorAll(`[data-col="${col}"]`).forEach(el => {
                sum += parseInt(el.value) || 0;
            });
            document.getElementById(footerId).textContent = sum;
        });
    }

    document.getElementById('cat-tbody').addEventListener('input', recalcCatTotals);


    // ── EXIT COURSES TOTALS ───────────────────────────────────────────────────

    const EXIT_COLS = {
        'exit-offered':  'exit-total-offered',
        'exit-required': 'exit-total-required',
        'exit-th':       'exit-total-th',
        'exit-tu':       'exit-total-tu',
        'exit-pr':       'exit-total-pr',
        'exit-credits':  'exit-total-credits',
        'exit-marks':    'exit-total-marks',
    };

    function recalcExitTotals() {
        Object.entries(EXIT_COLS).forEach(([col, footerId]) => {
            let sum = 0;
            document.querySelectorAll(`[data-col="${col}"]`).forEach(el => {
                sum += parseInt(el.value) || 0;
            });
            document.getElementById(footerId).textContent = sum;
        });
    }

    document.getElementById('exit-tbody').addEventListener('input', recalcExitTotals);


    // ── DYNAMIC EXIT ROWS ─────────────────────────────────────────────────────

    function reIndexExitRows() {
        document.querySelectorAll('#exit-tbody .exit-row').forEach((row, idx) => {
            row.querySelectorAll('input').forEach(input => {
                // e.g. exit_courses[2][th_hrs]  →  exit_courses[idx][th_hrs]
                input.name = input.name.replace(/exit_courses\[\d+\]/, `exit_courses[${idx}]`);
            });
        });
    }

    function addExitRow() {
        const tbody = document.getElementById('exit-tbody');
        const idx   = tbody.querySelectorAll('.exit-row').length;

        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50 transition exit-row';
        tr.innerHTML = `
            <td class="px-4 py-3">
                <input type="text" name="exit_courses[${idx}][title]"
                    placeholder="e.g. Exit after Semester 2"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </td>
            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[${idx}][courses_offered]"    data-col="exit-offered"  class="exit-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[${idx}][courses_to_complete]" data-col="exit-required" class="exit-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[${idx}][th_hrs]"              data-col="exit-th"       class="exit-input w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[${idx}][tu_hrs]"              data-col="exit-tu"       class="exit-input w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[${idx}][pr_hrs]"              data-col="exit-pr"       class="exit-input w-16 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[${idx}][credits]"             data-col="exit-credits"  class="exit-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
            <td class="px-3 py-3"><input type="number" min="0" name="exit_courses[${idx}][marks]"               data-col="exit-marks"    class="exit-input w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 outline-none"></td>
            <td class="px-3 py-3 text-center">
                <button type="button" onclick="removeExitRow(this)"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                    Remove
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        recalcExitTotals();
    }

    function removeExitRow(btn) {
        btn.closest('.exit-row').remove();
        reIndexExitRows();
        recalcExitTotals();
    }


    // ── INIT ──────────────────────────────────────────────────────────────────
    recalcCatTotals();
    recalcExitTotals();
</script>

@endsection