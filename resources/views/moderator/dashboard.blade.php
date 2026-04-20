@extends('layouts.moderator')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

{{-- ================= SUMMARY CARDS ================= --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

    <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-blue-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Assigned</p>
        <h2 class="text-3xl font-bold text-blue-600">{{ $total }}</h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-yellow-400">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Pending Review</p>
        <h2 class="text-3xl font-bold text-yellow-500">{{ $submitted }}</h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-red-400">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Rejected</p>
        <h2 class="text-3xl font-bold text-red-500">{{ $rejected }}</h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-green-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Approved</p>
        <h2 class="text-3xl font-bold text-green-600">{{ $approved }}</h2>
    </div>

</div>


{{-- ================= PENDING REVIEW ================= --}}
@if($pendingCourses->count() > 0)
<div class="bg-white rounded-xl shadow overflow-hidden mb-6">

    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-800">Pending Review</h2>
        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full">
            {{ $pendingCourses->count() }} awaiting
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Course</th>
                    <th class="px-6 py-3 text-left">Expert</th>
                    <th class="px-6 py-3 text-center">Progress</th>
                    <th class="px-6 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($pendingCourses as $item)
                    <tr class="hover:bg-yellow-50 transition-colors">

                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $item['course']->title }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $item['course']->abbreviation }}</div>
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $item['expert']->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-center w-36">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full bg-blue-500"
                                         style="width: {{ $item['progress'] }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-8 shrink-0">{{ $item['progress'] }}%</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('moderator.syllabus.index') }}">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                    Review
                                </button>
                            </a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endif


{{-- ================= ALL COURSES ================= --}}
<div class="bg-white rounded-xl shadow overflow-hidden">

    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-800">All Assigned Courses</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Course</th>
                    <th class="px-6 py-3 text-left">Expert</th>
                    <th class="px-6 py-3 text-center">Progress</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">

                @forelse($courses as $item)
                    @php
                        $statusConfig = match($item['raw_status']) {
                            'hod_approved'       => ['label' => 'HOD Approved',       'class' => 'bg-green-100 text-green-800'],
                            'moderator_approved' => ['label' => 'Approved',           'class' => 'bg-blue-100 text-blue-800'],
                            'submitted'          => ['label' => 'Pending Review',     'class' => 'bg-yellow-100 text-yellow-800'],
                            'moderator_rejected' => ['label' => 'Rejected',           'class' => 'bg-red-100 text-red-800'],
                            'draft'              => ['label' => 'Draft',              'class' => 'bg-gray-100 text-gray-600'],
                            default              => ['label' => 'Not Started',        'class' => 'bg-gray-100 text-gray-400'],
                        };
                    @endphp

                    <tr class="hover:bg-gray-50 transition-colors">

                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $item['course']->title }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $item['course']->abbreviation }}</div>
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $item['expert']->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-center w-36">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full transition-all
                                        @if($item['progress'] == 100) bg-green-500
                                        @elseif($item['progress'] >= 50) bg-blue-500
                                        @else bg-yellow-400 @endif"
                                        style="width: {{ $item['progress'] }}%">
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500 w-8 shrink-0">{{ $item['progress'] }}%</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusConfig['class'] }}">
                                {{ $statusConfig['label'] }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($item['syllabus'])
                                <a href="{{ route('moderator.syllabus.preview', $item['course']->id) }}">
                                    <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                        View
                                    </button>
                                </a>
                            @else
                                <span class="text-gray-300 text-xs">No syllabus</span>
                            @endif
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            No courses assigned yet.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>

@endsection