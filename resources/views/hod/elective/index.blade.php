@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Elective Groups
    </h1>


    {{-- ================= SUMMARY ================= --}}
    <div class="grid grid-cols-3 gap-6 mb-8">

        <div class="bg-white p-5 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Total Elective Courses</p>
            <h2 class="text-2xl font-bold text-blue-600 mt-1">{{ $totalElectives }}</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Grouped Courses</p>
            <h2 class="text-2xl font-bold text-green-600 mt-1">{{ $groupedElectives }}</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Remaining Courses</p>
            <h2 class="text-2xl font-bold text-red-600 mt-1">{{ $remainingElectives }}</h2>
        </div>

    </div>



    {{-- ================= CREATE GROUP ================= --}}
    <div class="bg-white p-6 rounded-xl shadow mb-8">

        <h2 class="text-lg font-semibold mb-4">Create Elective Group</h2>

        <form method="POST" action="{{ route('hod.elective.store') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">


            {{-- BASIC --}}
            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Group Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Semester</label>
                    <select name="semester_no" id="semesterSelect" required
                        class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Select Semester</option>
                        @for ($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('semester_no') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Minimum Selection</label>
                    <input type="number" min="0" name="min_select_count" value="{{ old('min_select_count') }}"
                        required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Maximum Selection</label>
                    <input type="number" min="0" name="max_select_count" id="maxSelect"
                        value="{{ old('max_select_count') }}" required
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

            </div>



            {{-- ================= COURSE SELECTION ================= --}}
            <div>
                <h4 class="font-semibold text-gray-700 mt-4 mb-2">Select Courses</h4>

                <div id="courseList"
                    class="grid grid-cols-3 gap-3 max-h-64 overflow-y-auto border-gray-300 border rounded p-3">

                    @forelse ($availableCourses as $course)
                        <label class="course-item flex items-center gap-2 text-sm" data-sem="{{ $course->semester_no }}">

                            <input type="checkbox" name="courses[]" value="{{ $course->course_master_id }}">

                            <span>
                                {{ $course->courseMaster->abbreviation }}
                                <span class="text-gray-400">(Sem {{ $course->semester_no }})</span>
                            </span>

                        </label>

                    @empty
                        <div class="text-gray-500">No elective courses available</div>
                    @endforelse

                </div>
            </div>


            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                Create Group
            </button>

        </form>

    </div>





    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold mb-4">Elective Groups</h2>

        <div class="overflow-x-auto rounded-xl shadow bg-white">
            <table class="w-full text-sm border border-gray-200 text-center">

                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Semester</th>
                        <th class="px-4 py-2">Min to select</th>
                        <th class="px-4 py-2">Size</th>
                        <th class="px-4 py-2 ">Courses</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @foreach ($groups as $group)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-2 font-medium">
                                {{ $group->name }}
                            </td>

                            <td class="px-4 py-2">
                                {{ $group->semester_no }}
                            </td>

                            <td class="px-4 py-2">
                                {{ $group->min_select_count }}
                            </td>

                            <td class="px-4 py-2">
                                {{ $group->max_select_count }}
                            </td>

                            <td class="px-4 py-2 ">
                                @foreach ($group->courses as $c)
                                    <span class="inline-block bg-gray-100 px-2 py-1 rounded text-xs mr-1 mb-1">
                                        {{ $c->abbreviation }}
                                    </span>
                                @endforeach
                            </td>

                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('hod.elective.destroy', $group->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Delete
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>



    {{-- ================= JS ================= --}}
    <script>
        const semesterSelect = document.getElementById('semesterSelect');
        const maxInput = document.getElementById('maxSelect');
        const minInput = document.querySelector('input[name="min_select_count"]');

        // ================= SEMESTER FILTER =================
        semesterSelect.addEventListener('change', function() {
            let selected = this.value;

            document.querySelectorAll('.course-item').forEach(el => {
                if (!selected || el.dataset.sem === selected) {
                    el.style.display = 'flex';
                } else {
                    el.style.display = 'none';
                    el.querySelector('input').checked = false;
                }
            });

            updateCheckboxLimit();
        });


        // ================= CHECKBOX LIMIT =================
        function getVisibleCheckboxes() {
            return [...document.querySelectorAll('.course-item')]
                .filter(el => el.style.display !== 'none')
                .map(el => el.querySelector('input'));
        }

        function updateCheckboxLimit() {
            let max = parseInt(maxInput.value) || 0;
            let visibleBoxes = getVisibleCheckboxes();
            let checked = visibleBoxes.filter(cb => cb.checked).length;

            visibleBoxes.forEach(cb => {
                cb.disabled = (!cb.checked && checked >= max);
            });
        }

        document.querySelectorAll('input[name="courses[]"]').forEach(cb => {
            cb.addEventListener('change', updateCheckboxLimit);
        });

        maxInput.addEventListener('input', updateCheckboxLimit);


        // ================= FORM VALIDATION =================
        document.querySelector('form').addEventListener('submit', function(e) {

            let min = parseInt(minInput.value) || 0;
            let max = parseInt(maxInput.value) || 0;

            let visibleBoxes = getVisibleCheckboxes();
            let availableCount = visibleBoxes.length;

            if (max > availableCount) {
                e.preventDefault();
                alert(`Max to select (${max}) cannot exceed available courses (${availableCount}).`);
                return;
            }

            if (max < min) {
                e.preventDefault();
                alert(`Max to select (${max}) must be greater than or equal to Min (${min}).`);
                return;
            }
        });
        const oldSemester = "{{ old('semester_no') }}";

        if (oldSemester) {
            semesterSelect.value = oldSemester;
            semesterSelect.dispatchEvent(new Event('change'));
        }
    </script>
@endsection
