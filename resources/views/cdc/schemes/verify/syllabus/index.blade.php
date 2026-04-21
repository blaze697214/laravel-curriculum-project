@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold mb-6">
        Syllabus Verification
    </h1>

    @foreach ($grouped as $key => $items)
        <h2 class="text-lg font-semibold mt-8 mb-3 text-gray-700">
            @if ($key === 'all')
                Service Department Courses
            @else
                Semester {{ $key }}
            @endif
        </h2>

        <div class="bg-white rounded-xl shadow overflow-hidden mb-4">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Course</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Owner Dept</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @foreach ($items as $item)
                            @php
                                $course = $item['course'];
                                $syllabus = $item['syllabus'];
                                $status = $item['status'];

                                $badgeClass = match ($status) {
                                    'hod_approved' => 'bg-green-100 text-green-800',
                                    'moderator_approved' => 'bg-blue-100 text-blue-800',
                                    'submitted' => 'bg-yellow-100 text-yellow-800',
                                    'moderator_rejected' => 'bg-red-100 text-red-800',
                                    'draft' => 'bg-gray-100 text-gray-600',
                                    default => 'bg-gray-100 text-gray-400',
                                };

                                $statusLabel = match ($status) {
                                    'hod_approved' => 'HOD Approved',
                                    'moderator_approved' => 'Moderator Approved',
                                    'submitted' => 'Submitted',
                                    'moderator_rejected' => 'Rejected',
                                    'draft' => 'Draft',
                                    default => 'Not Created',
                                };
                            @endphp

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $course->title }}
                                    @if ($course->is_common)
                                        <span
                                            class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded font-semibold">Common</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $course->ownerDepartment->abbreviation ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($status !== 'not_created')
                                        <a
                                            href="{{ route('cdc.schemes.verify.syllabus.preview', [$scheme->id,$department->id, $course->id]) }}">
                                            <button
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded text-sm">
                                                View
                                            </button>
                                        </a>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    @endforeach
@endsection
