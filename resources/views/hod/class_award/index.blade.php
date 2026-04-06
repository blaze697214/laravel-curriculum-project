@extends('layouts.hod')

@section('content')

    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Class Award Configuration
    </h1>


    {{-- ================= RULE CARDS ================= --}}
    <div class="grid grid-cols-2 gap-6 mb-8">

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500">Required Subjects</p>
            <h2 class="text-2xl font-bold text-green-600">
                {{ $rule->total_subjects_required ?? 0 }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500">Required Marks</p>
            <h2 class="text-2xl font-bold text-blue-600">
                {{ $rule->total_marks_required ?? 0 }}
            </h2>
        </div>

    </div>


    {{-- ================= FORM ================= --}}
    <form method="POST" action="{{ route('hod.class_award.store') }}" class="space-y-8">
        @csrf
        <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

        {{-- ================= COMPULSORY COURSES ================= --}}
        <div class="bg-white p-6 rounded-xl shadow">

            <h3 class="text-lg font-semibold mb-4">Compulsory Courses</h3>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

                @foreach ($compulsoryCourses as $course)
                    @php
                        $id = $course->course_master_id;
                        $checked = in_array($id, old('compulsory_courses', $selectedCourses));
                    @endphp
                    <label class="flex items-center gap-2 bg-gray-50 px-3 py-2 rounded hover:bg-gray-100">

                        <input type="checkbox" name="compulsory_courses[]" value="{{ $id }}" class="compulsory"
                            data-marks="{{ $course->courseMaster->total_marks }}"
                            data-credits="{{ $course->courseMaster->credits }}" {{ $checked ? 'checked' : '' }}>

                        <span class="text-sm">
                            {{ $course->courseMaster->title }}
                            <span class="text-gray-500 text-xs">
                                ({{ $course->courseMaster->total_marks }})
                            </span>
                        </span>

                    </label>
                @endforeach

            </div>

        </div>


        {{-- ================= ELECTIVE GROUPS ================= --}}
        <div class="bg-white p-6 rounded-xl shadow">

            <h3 class="text-lg font-semibold mb-4">Elective Groups</h3>

            <div class="space-y-4">

                @foreach ($groups as $group)
                    @php
                        $checked = in_array($group->id, old('elective_groups', $selectedGroups));
                    @endphp

                    <div class="border rounded-lg p-4">

                        <label class="flex items-center gap-2 font-medium">

                            <input type="checkbox" name="elective_groups[]" value="{{ $group->id }}" class="group"
                                data-count="{{ $group->min_select_count }}" data-size="{{ $group->courses->count() }}"
                                data-marks="{{ $group->courses->sum('total_marks') }}"
                                data-credits="{{ $group->courses->sum('credits') }}" {{ $checked ? 'checked' : '' }}>

                            {{ $group->name }}
                            <span class="text-sm text-gray-500">
                                (Select {{ $group->min_select_count }})
                            </span>

                        </label>

                        <div class="mt-2">
                            @foreach ($group->courses as $c)
                                <span class="inline-block bg-gray-100 px-2 py-1 rounded text-xs mr-1 mb-1">
                                    {{ $c->abbreviation }}
                                </span>
                            @endforeach
                        </div>

                    </div>
                @endforeach

            </div>

        </div>


        {{-- ================= SUMMARY ================= --}}
        <div class="bg-white p-6 rounded-xl shadow">

            <h3 class="text-lg font-semibold mb-4">Selection Summary</h3>

            <div class="grid grid-cols-3 gap-6 text-center">

                <div>
                    <p class="text-sm text-gray-500">Subjects Selected</p>
                    <p class="text-xl font-semibold text-gray-800" id="subjectCount">0</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Total Marks</p>
                    <p class="text-xl font-semibold text-gray-800" id="totalMarks">0</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Total Credits</p>
                    <p class="text-xl font-semibold text-gray-800" id="totalCredits">0</p>
                </div>

            </div>

        </div>


        {{-- ================= ACTION BUTTONS ================= --}}
        <div class="flex gap-4">

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                Save
            </button>
            {{-- {{ route('hod.class_award.preview') }} --}}
            <a href="">
                <button type="button" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded">
                    Preview
                </button>
            </a>

        </div>

    </form>



    {{-- ================= JS ================= --}}
    <script>
        function calculateTotals() {

            let subjectCount = 0;
            let totalMarks = 0;
            let totalCredits = 0;

            document.querySelectorAll('.compulsory:checked').forEach(cb => {
                subjectCount += 1;
                totalMarks += parseInt(cb.dataset.marks || 0);
                totalCredits += parseInt(cb.dataset.credits || 0);
            });

            document.querySelectorAll('.group:checked').forEach(cb => {
                let count = parseInt(cb.dataset.count || 0); // min_select_count
                let totalGroupMarks = parseInt(cb.dataset.marks || 0);
                let totalGroupCredits = parseInt(cb.dataset.credits || 0);
                let groupSize = parseInt(cb.dataset.size || 1); // total courses in group

                let avgMarks = totalGroupMarks / groupSize;
                let avgCredits = totalGroupCredits / groupSize;

                subjectCount += count;
                totalMarks += avgMarks * count;
                totalCredits += avgCredits * count;
            });

            document.getElementById('subjectCount').innerText = subjectCount;
            document.getElementById('totalMarks').innerText = totalMarks;
            document.getElementById('totalCredits').innerText = totalCredits;
        }

        document.querySelectorAll('input').forEach(el => {
            el.addEventListener('change', calculateTotals);
        });

        calculateTotals();

        document.querySelectorAll('.group:checked').forEach(cb => {
            let count = parseInt(cb.dataset.count || 0); // min_select_count
            let totalGroupMarks = parseInt(cb.dataset.marks || 0);
            let totalGroupCredits = parseInt(cb.dataset.credits || 0);
            let groupSize = parseInt(cb.dataset.size || 1); // total courses in group

            let avgMarks = totalGroupMarks / groupSize;
            let avgCredits = totalGroupCredits / groupSize;

            subjectCount += count;
            totalMarks += avgMarks * count;
            totalCredits += avgCredits * count;
        });
    </script>

@endsection
