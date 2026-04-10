@extends('layouts.hod')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">
    Assign Courses to Experts
</h1>


{{-- ================= SUMMARY ================= --}}
@php
    $total = $courses->count();
    $assigned = $assignments->count();
    $remaining = $total - $assigned;
@endphp

<div class="grid grid-cols-3 gap-6 mb-8">

    <div class="bg-white p-5 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">Total Courses</p>
        <h2 class="text-2xl font-bold text-blue-600 mt-1">{{ $total }}</h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">Assigned</p>
        <h2 class="text-2xl font-bold text-green-600 mt-1">{{ $assigned }}</h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">Remaining</p>
        <h2 class="text-2xl font-bold text-red-600 mt-1">{{ $remaining }}</h2>
    </div>

</div>


{{-- ================= COURSE TABLE ================= --}}
<div class="bg-white p-6 rounded-xl shadow mb-8">

    <h2 class="text-lg font-semibold mb-4">Courses</h2>

    <div class="overflow-x-auto rounded-xl shadow bg-white">
        <table class="w-full text-sm border border-gray-200 text-center">

            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    @if (auth()->user()->department->type === 'programme')
                        <th class="px-4 py-2 w-10">Semester</th>
                    @endif
                    <th class="px-4 py-2 w-80">Title</th>
                    <th class="px-4 py-2">Abbrev</th>
                    <th class="px-4 py-2">Expert</th>
                    <th class="px-4 py-2 w-30">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                @php
                    $isDept = auth()->user()->department->type === 'programme';
                    $grouped = $isDept ? $courses->groupBy('semester_no') : collect(['all' => $courses]);
                @endphp

                @foreach ($grouped as $sem => $semCourses)
                    @foreach ($semCourses as $i => $c)

                        @php
                            $course = $c->courseMaster;
                            $assignment = $assignments[$course->id] ?? null;
                        @endphp

                        <tr class="hover:bg-gray-50">

                            @if ($isDept && $i === 0)
                                <td class="px-4 py-2 font-medium border-gray-200 border-r"
                                    rowspan="{{ $semCourses->count() }}">
                                    {{ $sem }}
                                </td>
                            @endif

                            <td class="px-4 py-2 text-left">
                                {{ $course->title }}
                            </td>

                            <td class="px-4 py-2">
                                {{ $course->abbreviation }}
                            </td>

                            <td class="px-4 py-2">

                                <form method="POST"
                                    action="{{ route('hod.assign.store') }}"
                                    class="flex justify-center gap-2">

                                    @csrf

                                    <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">
                                    <input type="hidden" name="course_master_id" value="{{ $course->id }}">

                                    <select name="expert_id"
                                        class="border border-gray-300 w-full rounded px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500"
                                        required>

                                        <option value="">Select Expert</option>

                                        @foreach ($expert as $f)
                                            <option value="{{ $f->id }}"
                                                {{ $assignment && $assignment->expert_id == $f->id ? 'selected' : '' }}>
                                                {{ $f->name }}
                                            </option>
                                        @endforeach

                                    </select>

                            </td>

                            <td class="px-4 py-2">

                                @if ($assignment)
                                    <button type="submit"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded text-sm">
                                        Change
                                    </button>
                                @else
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
                                        Assign
                                    </button>
                                @endif

                                </form>

                            </td>

                        </tr>

                    @endforeach
                @endforeach

            </tbody>

        </table>
    </div>

</div>



{{-- ================= EXPERT-WISE TABLE ================= --}}
<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-lg font-semibold mb-4">Expert-wise Assigned Courses</h2>

    <div class="overflow-x-auto rounded-xl shadow bg-white">
        <table class="w-full text-sm border border-gray-200 text-center">

            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Expert</th>
                    <th class="px-4 py-2">Courses</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                @foreach ($expertAssignments as $expertId => $items)

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-2 font-medium text-left">
                            {{ $items->first()->expert->name }}
                        </td>

                        <td class="px-4 py-2">

                            @foreach ($items as $item)
                                <span class="inline-block bg-gray-100 px-2 py-1 rounded text-xs mr-1 mb-1">
                                    {{ $item->courseMaster->abbreviation }}
                                </span>
                            @endforeach

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>
    </div>

</div>

@endsection