{{-- @extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-2">
        Configure Department Categories
    </h1>

    <p class="mb-6 text-gray-600">
        Department: {{ $department->name }}
    </p>


    <div class="bg-white p-6 rounded-xl shadow">

        <form method="POST" action="{{  }}">

            @csrf
            
            <div class="overflow-x-auto">

                <table class="w-full text-left border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Level</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Courses Offered</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Courses to Complete</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">TH</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">TU</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">PR</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Credits</th>

                            <th class="px-3 py-2 text-sm font-semibold text-gray-600">Marks</th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @foreach ($levels as $level)
                            @php
                                $row = $existing[$level->id] ?? null;
                            @endphp

                            <tr class="hover:bg-gray-50 border-gray-200">

                                <td class="px-3 py-2 font-medium text-gray-800">

                                    {{ $level->name }}

                                    <input type="hidden" name="levels[]" value="{{ $level->id }}" required>

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="courses_offered[{{ $level->id }}]"
                                        value="{{ old('courses_offered.' . $level->id, $row->courses_offered ?? '') }}"
                                        required class="courses_offered w-20 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="compulsory[{{ $level->id }}]"
                                        value="{{ old('compulsory.' . $level->id, $row->courses_to_complete ?? '') }}"
                                        required class="compulsory w-20 border border-gray-300 rounded px-2 py-1">

                                </td>





                                <td class="px-3 py-2">

                                    <input type="number" name="th[{{ $level->id }}]"
                                        value="{{ old('th.' . $level->id, $row->th_hrs ?? '') }}"
                                        class="th w-16 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="tu[{{ $level->id }}]"
                                        value="{{ old('tu.' . $level->id, $row->tu_hrs ?? '') }}"
                                        class="tu w-16 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="pr[{{ $level->id }}]"
                                        value="{{ old('pr.' . $level->id, $row->pr_hrs ?? '') }}"
                                        class="pr w-16 border border-gray-300 rounded px-2 py-1">

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="credits[{{ $level->id }}]"
                                        value="{{ old('credits.' . $level->id, $row->credits ?? '') }}" required
                                        class="credits w-16 border border-gray-300 disabled:bg-gray-200 rounded px-2 py-1"
                                        @if ($level->is_audit) disabled @endif>

                                </td>


                                <td class="px-3 py-2">

                                    <input type="number" name="marks[{{ $level->id }}]"
                                        value="{{ old('marks.' . $level->id, $row->marks ?? '') }}"
                                        class="marks w-20 border border-gray-300 rounded disabled:bg-gray-200 px-2 py-1"
                                        @if ($level->is_audit) disabled @endif>

                                </td>

                            </tr>
                        @endforeach
                        <tr class="bg-gray-100 font-semibold">
                            <td class="px-3 py-2">Total</td>

                            <td class="px-3 py-2" id="total_offered"></td>
                            <td class="px-3 py-2" id="total_compulsory"></td>
                            <td class="px-3 py-2" id="total_elective"></td>
                            <td class="px-3 py-2" id="total_th"></td>
                            <td class="px-3 py-2" id="total_tu"></td>
                            <td class="px-3 py-2" id="total_pr"></td>
                            <td class="px-3 py-2" id="total_credits"></td>
                            <td class="px-3 py-2" id="total_marks"></td>
                        </tr>

                    </tbody>

                </table>

            </div>


            <div class="mt-6">

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">

                    Save & Preview

                </button>

            </div>


        </form>

    </div>

    <script>
        function calculateTotals() {

            function sum(cls) {
                let total = 0;

                document.querySelectorAll("." + cls).forEach(el => {
                    total += parseFloat(el.value) || 0;
                });

                return total;
            }

            document.getElementById('total_offered').innerText = sum('courses_offered');
            document.getElementById('total_compulsory').innerText = sum('compulsory');
            document.getElementById('total_elective').innerText = sum('elective');
            document.getElementById('total_th').innerText = sum('th');
            document.getElementById('total_tu').innerText = sum('tu');
            document.getElementById('total_pr').innerText = sum('pr');
            document.getElementById('total_credits').innerText = sum('credits');
            document.getElementById('total_marks').innerText = sum('marks');
        }

        document.addEventListener("input", function(e) {

            if (
                e.target.classList.contains('courses_offered') ||
                e.target.classList.contains('compulsory') ||
                e.target.classList.contains('elective') ||
                e.target.classList.contains('th') ||
                e.target.classList.contains('tu') ||
                e.target.classList.contains('pr') ||
                e.target.classList.contains('credits') ||
                e.target.classList.contains('marks')
            ) {
                calculateTotals();
            }

        });

        window.onload = calculateTotals;
    </script>
@endsection --}}

@extends('layouts.hod')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">
    Scheme At Glance
</h1>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

    {{-- TOP INFO --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Department --}}
        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">
                Department
            </p>

            <h3 class="text-lg font-semibold text-gray-800">
                {{ $department->name }}
            </h3>
        </div>

        {{-- Scheme --}}
        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">
                Scheme
            </p>

            <h3 class="text-lg font-semibold text-gray-800">
                {{ $scheme->name }}
            </h3>
        </div>

    </div>


    {{-- STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8">

        {{-- STATUS --}}
        <div class="rounded-xl border border-gray-200 p-5">

            <p class="text-sm text-gray-500 mb-2">
                Status
            </p>

            @if($configured)

                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                    Configured
                </span>

            @else

                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                    Not Configured
                </span>

            @endif

        </div>


        {{-- CATEGORIES --}}
        <div class="rounded-xl border border-gray-200 p-5">

            <p class="text-sm text-gray-500 mb-2">
                Categories Filled
            </p>

            <h2 class="text-3xl font-bold text-gray-800">
                {{ $categories->count() }}
            </h2>

        </div>


        {{-- EXIT COURSES --}}
        <div class="rounded-xl border border-gray-200 p-5">

            <p class="text-sm text-gray-500 mb-2">
                Exit Courses
            </p>

            <h2 class="text-3xl font-bold text-gray-800">
                {{ $exitCourses->count() }}
            </h2>

        </div>

    </div>


    {{-- ACTIONS --}}
    <div class="flex flex-wrap gap-4 mt-10">

        {{-- CREATE / EDIT --}}
        <a href="{{ route('hod.scheme.edit') }}"
           class="bg-blue-600 hover:bg-blue-700 transition text-white font-medium px-5 py-2.5 rounded-lg shadow-sm">

            {{ $configured ? 'Edit Scheme Structure' : 'Create Scheme Structure' }}

        </a>


        {{-- PREVIEW --}}
        @if($configured)

            <a href=""
               class="bg-green-600 hover:bg-green-700 transition text-white font-medium px-5 py-2.5 rounded-lg shadow-sm">

                Preview

            </a>

        @endif

    </div>

</div>

@endsection
