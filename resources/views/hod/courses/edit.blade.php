@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Edit Course
    </h1>


    <div class="bg-white p-6 rounded-xl shadow">

        <form method="POST"
            action="{{ isset($offering)
                ? route('hod.courses.update', $offering->id)
                : route('hod.courses.common.update', $course->id) }}"
            class="space-y-6">
            @csrf
            @method('PATCH')

            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">


            {{-- ================= BASIC ================= --}}
            <div>
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm text-gray-600 mb-1"> Course Title</label>
                        <input type="text" name="title" value="{{ old('title', $course->title) }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Abbreviation</label>
                        <input type="text" name="abbreviation" value="{{ old('abbreviation', $course->abbreviation) }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>

                </div>

                {{-- CATEGORY --}}
                <div class="mt-4">
                    <label class="block text-sm text-gray-600 mb-1">Category</label>
                    <select name="category_id" {{ !$isOwner && $course->is_common ? 'disabled' : '' }}
                        class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ $course->course_category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @isset($offering)
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
                                <option value="{{ $val }}" {{ $offering->semester_no == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="electiveSection" class="mt-3" style="display:none;">
                        <label class="inline-flex items-center gap-2 text-gray-700">
                            <input class="pointer-events-none" type="checkbox" name="is_elective" id="is_elective"
                                {{ $offering->is_elective ? 'checked' : '' }} onclick="return false;">
                            Is Elective
                        </label>
                    </div>
                @endisset

            </div>


            {{-- ================= HOURS ================= --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Hours
                </h3>

                <div class="grid grid-cols-5 gap-4">

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">IKS</label>

                        <input type="number" name="iks_hours" value="{{ $course->iks_hours }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">CL</label>
                        <input type="number" name="cl_hours" value="{{ $course->cl_hours }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">TL</label>
                        <input type="number" name="tl_hours" value="{{ $course->tl_hours }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">LL</label>
                        <input type="number" name="ll_hours" value="{{ $course->ll_hours }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">SLA</label>
                        <input type="number" name="sla_hours" value="{{ $course->sla_hours }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
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
                        <input type="number" name="credits" value="{{ $course->credits }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Paper Duration</label>
                        <input type="number" name="paper_duration" value="{{ $course->paper_duration }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">FA TH</label>
                        <input type="number" name="fa_th" id="fa_th" value="{{ $course->fa_th }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">SA TH</label>
                        <input type="number" name="sa_th" id="sa_th" value="{{ $course->sa_th }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">FA PR</label>
                        <input type="number" name="fa_pr" id="fa_pr" value="{{ $course->fa_pr }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">SA PR</label>
                        <input type="number" name="sa_pr" id="sa_pr" value="{{ $course->sa_pr }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">SLA Marks</label>
                        <input type="number" name="sla_marks" id="sla_marks" value="{{ $course->sla_marks }}"
                            {{ !$isOwner && $course->is_common ? 'readonly' : '' }}
                            class="w-full border border-gray-300 rounded px-3 py-2 {{ !$isOwner && $course->is_common ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Total Marks</label>
                        <input type="number" name="total_marks" id="total_marks" value="{{ $course->total_marks }}"
                            readonly class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed">
                    </div>
                </div>

            </div>


            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                Update
            </button>

        </form>

    </div>

    <script>
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
    </script>
@endsection
