@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold mb-6">
        HOD Approval
    </h1>

    @foreach ($grouped as $key => $items)
        {{-- SEMESTER HEADING --}}
        @if ($key !== 'all')
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
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Expert</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-700">Progress</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @foreach ($items as $item)
                            @php
                                $course = $item['course'];
                                $expert = $item['expert'];
                                $syllabus = $item['syllabus'];
                                $progress = $item['progress'];
                            @endphp

                            <tr class="hover:bg-gray-50 border-gray-200">

                                {{-- COURSE --}}
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $course->title }}
                                </td>

                                {{-- EXPERT --}}
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $expert->name ?? '' }}
                                </td>

                                {{-- PROGRESS --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $progress }}%</span>
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $status = $item['status'] ; // use precomputed status
                                        $statusClass = match ($status) {
                                            'hod_approved' => 'text-green-600',
                                            'moderator_approved' => 'text-blue-600',
                                            'rejected' => 'text-red-500',
                                            'submitted' => 'text-yellow-600',
                                            'not_started' => 'text-gray-400',
                                            default => 'text-gray-600',
                                        };
                                    @endphp
                                    <span class="font-semibold {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>

                                {{-- ACTION --}}
                                <td class="px-6 py-4 text-center space-y-2">

                                    {{-- VIEW --}}
                                    @if($item['syllabus'])
                                    <a href="{{ route('hod.syllabus.preview', $course->id) }}">
                                        <button
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded text-sm">
                                            View
                                        </button>
                                    </a>
                                    @endif

                                    {{-- FINAL APPROVE --}}
                                    @if ($item['status'] === 'moderator_approved')
                                        <form method="POST" action="{{ route('hod.syllabus.approve', $syllabus->id) }}">
                                            @csrf
                                            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded text-sm">
                                                Final Approve
                                            </button>
                                        </form>
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
