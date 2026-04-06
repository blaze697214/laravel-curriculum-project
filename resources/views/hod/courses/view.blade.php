@extends('layouts.hod')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">
    View Courses
</h1>

@if(auth()->user()->department->type == 'programme')

@foreach(range(1,6) as $sem)

    <div class="mb-8">

        <h3 class="text-lg font-semibold text-gray-700 mb-3">
            Semester {{ $sem }}
        </h3>

        <div class="bg-white rounded-xl shadow overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600 w-150">Title</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600 text-center">Abbrev</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600 text-center">Editor</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600 text-center">Credits</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600 text-center">Total Marks</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                @forelse($offerings[$sem] ?? [] as $offering)

                    @php
                        $course = $offering->courseMaster;
                        $isOwner = $course->owner_department_id == auth()->user()->department_id;
                    @endphp

                    <tr class="hover:bg-gray-50 border-gray-200">

                        <td class="px-4 py-2">
                            {{ $course->title }}
                            @if($course->is_common)
                                <span class="bg-green-200 text-green-900 text-xs ml-2 px-2 py-1 rounded-md font-semibold">Common</span>
                            @endif
                            @if($offering->is_elective)
                                <span class="bg-purple-200 text-purple-900 text-xs ml-2 px-2 py-1 rounded font-semibold">Elective</span>
                            @endif
                            
                        </td>

                        <td class="px-4 py-2 text-center">
                            {{ $course->abbreviation }}
                        </td>

                        <td class="px-4 py-2 text-center">
                            <span class="px-3 py-1 bg-gray-200 text-xs font-semibold rounded-lg text-gray-600">
                                {{ $course->ownerDepartment->abbreviation }}
                            </span>
                        </td>

                        <td class="px-4 py-2 text-center">
                            {{ $course->credits }}
                        </td>

                        <td class="px-4 py-2 text-center">
                            {{ $course->total_marks }}
                        </td>

                        <td class="px-4 py-2 flex gap-2 justify-center">

                            {{-- EDIT --}}
                            <a href="{{ route('hod.courses.edit', $offering->id) }}">
                                <button class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-3 py-1 rounded">
                                    Edit
                                </button>
                            </a>

                            {{-- DELETE --}}
                            <form method="POST" action="{{ route('hod.courses.destroy', $offering->id) }}">
                                @csrf
                                @method('DELETE')

                                <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-3 py-1 rounded">
                                    Delete
                                </button>
                            </form>

                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            No courses
                        </td>
                    </tr>
                @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endforeach

@else





<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-left border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">Title</th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">Abbrev</th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">Credits</th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">Total Marks</th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
        @forelse($ownedCourses as $course)
            <tr class="hover:bg-gray-50 border-gray-200">
                <td class="px-4 py-2">{{ $course->title }}</td>
                <td class="px-4 py-2">{{ $course->abbreviation }}</td>
                <td class="px-4 py-2">{{ $course->credits }}</td>
                <td class="px-4 py-2">{{ $course->total_marks }}</td>
                <td class="px-4 py-2 flex gap-2">
                    <a href="{{ route('hod.courses.common.edit', $course->id) }}">
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Edit</button>
                    </a>
                    <form method="POST" action="{{ route('hod.courses.common.destroy', $course->id) }}">
                        @csrf @method('DELETE')
                        <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center py-4 text-gray-500">No courses</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endif

@endsection
