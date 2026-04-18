@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Create Course
    </h1>


    <div class="bg-white p-6 rounded-xl shadow">

        <form method="POST" action="{{ route('hod.courses.store') }}" class="space-y-6">
            @csrf

            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">
            <input type="hidden" name="existing_course_id" id="existing_course_id" value="{{ old('existing_course_id') }}">

            {{-- ================= BASIC ================= --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Basic Info
                </h3>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Course Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Abbreviation</label>
                        <input type="text" name="abbreviation" id="abbreviation" value="{{ old('abbreviation') }}"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>

                </div>

                <div class="mt-4">
                    <label class="inline-flex items-center gap-2 text-gray-700">
                        <input type="checkbox" name="is_common" id="is_common" {{ old('is_common') ? 'checked' : '' }}>
                        Common Course <span class="px-2 py-1 text-xs text-gray-700 bg-gray-200 rounded-md">Is the course
                            used by other departments too?</span>
                    </label>
                </div>
                {{-- COMMON SEARCH RESULTS --}}
                <div id="commonResults" class="mt-3"></div>


                {{-- OWNER DROPDOWN (only for new common) --}}
                <div id="ownerSelectSection" class="hidden mt-4">
                    <label class="block text-sm text-gray-600 mb-1">
                        Owner Department
                    </label>

                    <select id="owner_dropdown"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- OWNER DISPLAY (for existing common) --}}
                <div id="ownerDisplaySection" class="hidden mt-4">
                    <label class="block text-sm text-gray-600 mb-1">
                        Owner Department
                    </label>

                    <div id="owner_name" class="w-full border border-gray-200 bg-gray-100 rounded px-3 py-2 text-gray-700">
                    </div>
                </div>


                {{-- ACTUAL SUBMIT VALUE --}}
                <input type="hidden" name="owner_department_id" id="owner_department_id"
                    value="{{ auth()->user()->department->id }}">

                {{-- CATEGORY --}}
                <div class="mt-4">
                    <label class="block text-sm text-gray-600 mb-1">Category</label>
                    <select name="category_id" id="category_id" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Select</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->abbreviation }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- SEMESTER --}}
                <div class="mt-4">
                    <label class="block text-sm text-gray-600 mb-1">Semester</label>
                    <select name="semester_no" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">--Select--</option>
                        @php
                            $semesters = [
                                1 => 'FY Odd',
                                2 => 'FY Even',
                                3 => 'SY Odd',
                                4 => 'SY Even',
                                5 => 'TY Odd',
                                6 => 'TY Even',
                            ];
                        @endphp
                        @foreach ($semesters as $val => $label)
                            <option value="{{ $val }}" {{ old('semester_no') == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="electiveSection" class="mt-3" style="display:none;">
                    <label class="inline-flex items-center gap-2 text-gray-700">
                        <input class="pointer-events-none" type="checkbox" name="is_elective" id="is_elective"
                            {{ old('is_elective') ? 'checked' : '' }} onclick="return false;">
                        Is Elective
                    </label>
                </div>

            </div>


            {{-- ================= HOURS ================= --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Hours
                </h3>

                <div class="grid grid-cols-5 gap-4">

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">IKS</label>
                        <input type="number" min="0" name="iks_hours" id="iks_hours" value="{{ old('iks_hours') }}"
                            class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">CL</label>
                        <input type="number" min="0" name="cl_hours" id="cl_hours" value="{{ old('cl_hours') }}"
                            class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">TL</label>
                        <input type="number" min="0" name="tl_hours" id="tl_hours" value="{{ old('tl_hours') }}"
                            class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">LL</label>
                        <input type="number" min="0" name="ll_hours" id="ll_hours" value="{{ old('ll_hours') }}"
                            class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">SLA</label>
                        <input type="number" min="0" name="sla_hours" id="sla_hours"
                            value="{{ old('sla_hours') }}" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                </div>
            </div>


            {{-- ================= MARKS ================= --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Marks
                </h3>

                <div class="grid grid-cols-3 gap-4">

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Credits</label>
                        <input type="number" min="0" name="credits" id="credits"
                            value="{{ old('credits') }}" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Paper Duration</label>
                        <input type="number" min="0" name="paper_duration" id="paper_duration"
                            value="{{ old('paper_duration') }}" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                </div>

                <div class="grid grid-cols-3 gap-4 mt-4">

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">FA TH</label>
                        <input type="number" min="0" name="fa_th" id="fa_th" value="{{ old('fa_th') }}"
                            class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">SA TH</label>
                        <input type="number" min="0" name="sa_th" id="sa_th" value="{{ old('sa_th') }}"
                            class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">FA PR</label>
                        <input type="number" min="0" name="fa_pr" id="fa_pr" value="{{ old('fa_pr') }}"
                            class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">SA PR</label>
                        <input type="number" min="0" name="sa_pr" id="sa_pr" value="{{ old('sa_pr') }}"
                            class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">SLA Marks</label>
                        <input type="number" min="0" name="sla_marks" id="sla_marks"
                            value="{{ old('sla_marks') }}" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Total Marks</label>
                        <input type="number" min="0" name="total_marks" id="total_marks" readonly
                            class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed">
                    </div>

                </div>

            </div>


            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                Save Course
            </button>

        </form>

    </div>


    <script>
        const isCommon = document.getElementById('is_common');
        const ownerSelectSection = document.getElementById('ownerSelectSection');
        const ownerDisplaySection = document.getElementById('ownerDisplaySection');
        const ownerDropdown = document.getElementById('owner_dropdown');
        const ownerInput = document.getElementById('owner_department_id');

        const titleInput = document.getElementById('title');
        const abbrInput = document.getElementById('abbreviation');
        const submitBtn = document.querySelector('button[type="submit"]');

        let searchTimeout = null;

        // ================= TOGGLE =================
        function toggleOwner() {

            if (isCommon.checked) {

                // NEW COMMON → show dropdown
                ownerSelectSection.style.display = 'block';
                ownerDisplaySection.style.display = 'none';

                ownerInput.value = ownerDropdown.value;

            } else {

                // RESET EVERYTHING
                ownerSelectSection.style.display = 'none';
                ownerDisplaySection.style.display = 'none';

                ownerInput.value = '';
                ownerDropdown.disabled = false;

                document.getElementById('existing_course_id').value = '';
                document.getElementById('commonResults').innerHTML = '';

                resetFormFields();
            }
        }

        // 🔥 IMPORTANT FIX
        isCommon.addEventListener('change', () => {
            toggleOwner();
            searchCommon(); // FIX 3
        });


        // ================= RESET FORM =================
        function resetFormFields() {

            [
                'category_id', 'iks_hours', 'cl_hours', 'tl_hours', 'll_hours', 'sla_hours',
                'credits', 'paper_duration',
                'fa_th', 'sa_th', 'fa_pr', 'sa_pr', 'sla_marks', 'total_marks'
            ].forEach(id => {
                let el = document.getElementById(id);
                if (el) el.value = '';
            });
        }


        // ================= SEARCH =================
        function searchCommon() {

            let title = titleInput.value;
            let abbrev = abbrInput.value;

            if (!isCommon.checked) return;
            if (!title && !abbrev) return;

            fetch(`/hod/courses/search-common?title=${title}&abbreviation=${abbrev}`)
                .then(res => res.json())
                .then(data => {

                    let html = '';

                    if (data.length > 0) {
                        html += "<strong>Select existing common course:</strong><br>";
                    }

                    data.forEach(c => {
                        html += `<div style="cursor:pointer;" onclick="selectCourse(${c.id})">
                        ${c.title} (${c.abbreviation}) — <span style="color:gray; font-size:0.85em;">${c.owner_dept_name}</span>
                        </div>`;
                    });

                    document.getElementById('commonResults').innerHTML = html;
                });
        }

        // trigger on typing
        function debounceSearch() {

            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                searchCommon();
            }, 300);
        }

        titleInput.addEventListener('input', debounceSearch);
        abbrInput.addEventListener('input', debounceSearch);


        // ================= SELECT COURSE =================
        function selectCourse(id) {

            submitBtn.disabled = true; // FIX 6

            fetch(`/hod/courses/${id}/details`)
                .then(res => res.json())
                .then(c => {

                    document.getElementById('existing_course_id').value = c.id;

                    // AUTO FILL
                    titleInput.value = c.title;
                    abbrInput.value = c.abbreviation;

                    document.getElementById('category_id').value = c.course_category_id;

                    document.getElementById('iks_hours').value = c.iks_hours;
                    document.getElementById('cl_hours').value = c.cl_hours;
                    document.getElementById('tl_hours').value = c.tl_hours;
                    document.getElementById('ll_hours').value = c.ll_hours;
                    document.getElementById('sla_hours').value = c.sla_hours;

                    document.getElementById('credits').value = c.credits;
                    document.getElementById('paper_duration').value = c.paper_duration;

                    document.getElementById('fa_th').value = c.fa_th;
                    document.getElementById('sa_th').value = c.sa_th;
                    document.getElementById('fa_pr').value = c.fa_pr;
                    document.getElementById('sa_pr').value = c.sa_pr;
                    document.getElementById('sla_marks').value = c.sla_marks;

                    document.getElementById('total_marks').value = c.total_marks;

                    // OWNER LOGIC (FIX 5)
                    ownerSelectSection.style.display = 'none';
                    ownerDisplaySection.style.display = 'block';

                    ownerInput.value = c.owner_department_id;

                    let selectedDept = ownerDropdown.querySelector(
                        `option[value="${c.owner_department_id}"]`
                    );

                    document.getElementById('owner_name').innerText =
                        selectedDept ? selectedDept.text : 'Unknown';

                    submitBtn.disabled = false; // FIX 6
                });
        }


        // ================= OWNER SYNC =================
        ownerDropdown.addEventListener('change', function() {
            ownerInput.value = this.value;
        });


        // ================= TOTAL CALC =================
        function calculateTotal() {

            let fa_th = +document.getElementById('fa_th').value || 0;
            let sa_th = +document.getElementById('sa_th').value || 0;
            let fa_pr = +document.getElementById('fa_pr').value || 0;
            let sa_pr = +document.getElementById('sa_pr').value || 0;
            let sla = +document.getElementById('sla_marks').value || 0;

            document.getElementById('total_marks').value =
                fa_th + sa_th + fa_pr + sa_pr + sla;
        }

        ['fa_th', 'sa_th', 'fa_pr', 'sa_pr', 'sla_marks'].forEach(id => {
            document.getElementById(id).addEventListener('input', calculateTotal);
        });


        const electiveCategories = @json($electiveCategories);

        function toggleElective() {
            const selected = +document.getElementById('category_id').value;
            const section = document.getElementById('electiveSection');
            if (electiveCategories.includes(selected)) {
                section.style.display = 'block';
                document.getElementById('is_elective').checked = true;
            } else {
                section.style.display = 'none';
                document.getElementById('is_elective').checked = false; // auto-uncheck
            }
        }

        document.getElementById('category_id').addEventListener('change', toggleElective);
        toggleElective(); // run on load in case of old() repopulation

        calculateTotal();
    </script>
@endsection
