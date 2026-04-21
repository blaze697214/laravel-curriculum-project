@extends('layouts.cdc')

@section('content')

<h1 class="text-2xl font-bold mb-6">
    Syllabus Verification
</h1>

@foreach($grouped as $key => $items)

    {{-- SEMESTER HEADING --}}
    @if($key !== 'all')
        <h2 class="text-lg font-semibold mt-8 mb-3 text-gray-700">
            Semester {{ $key }}
        </h2>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Course</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Department</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach($items as $item)

                    @php
                    $course = $item['course'];
                    $syllabus = $item['syllabus'];
                    $status = $syllabus->status;
                    @endphp

                    <tr class="hover:bg-gray-50">

                        {{-- COURSE --}}
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $course->title }}
                        </td>

                        {{-- DEPARTMENT --}}
                        <td class="px-6 py-4 text-gray-700">
                            {{ $course->department->name }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4 text-center">

                            @php
                                $statusClass = match($status) {
                                    'approved' => 'text-green-600',
                                    'moderator_approved' => 'text-blue-600',
                                    'rejected' => 'text-red-500',
                                    'submitted' => 'text-yellow-600',
                                    default => 'text-gray-600'
                                };
                            @endphp

                            <span class="font-semibold {{ $statusClass }}">
                                {{ ucfirst(str_replace('_',' ', $status)) }}
                            </span>

                        </td>

                        {{-- ACTION --}}
                        <td class="px-6 py-4 text-center">

                            <a href="{{ route('cdc.schemes.syllabus.preview', $course->id) }}">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded text-sm">
                                    View
                                </button>
                            </a>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

@endforeach

@endsection