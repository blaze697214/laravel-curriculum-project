@extends('layouts.expert')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

    {{-- ================= SUMMARY CARDS ================= --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

        <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-blue-500">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Assigned</p>
            <h2 class="text-3xl font-bold text-blue-600">{{ $total }}</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-green-500">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Approved</p>
            <h2 class="text-3xl font-bold text-green-600">{{ $approved }}</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-yellow-400">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">In Progress</p>
            <h2 class="text-3xl font-bold text-yellow-500">{{ $draft }}</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-purple-500">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">In Review</p>
            <h2 class="text-3xl font-bold text-purple-600">{{ $inReview }}</h2>
        </div>

    </div>

    {{-- ================= COURSES TABLE ================= --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">My Courses</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Course</th>
                        <th class="px-6 py-3 text-center">Progress</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-left">Remarks</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($courses as $item)
                        @php
                            $raw = $item['raw_status'];
                            $isLocked = $item['is_locked'];
                            $remark = $item['latest_remark'];

                            $statusConfig = match ($raw) {
                                'hod_approved' => ['label' => 'Approved', 'class' => 'bg-green-100 text-green-800'],
                                'moderator_approved' => [
                                    'label' => 'Moderator Approved',
                                    'class' => 'bg-blue-100 text-blue-800',
                                ],
                                'submitted' => ['label' => 'Under Review', 'class' => 'bg-purple-100 text-purple-800'],
                                'moderator_rejected' => ['label' => 'Rejected', 'class' => 'bg-red-100 text-red-800'],
                                'draft' => ['label' => 'Draft', 'class' => 'bg-yellow-100 text-yellow-800'],
                                default => ['label' => 'Not Started', 'class' => 'bg-gray-100 text-gray-600'],
                            };
                        @endphp

                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- COURSE --}}
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $item['course']->title }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $item['course']->abbreviation }}</div>
                            </td>

                            {{-- PROGRESS --}}
                            <td class="px-6 py-4 text-center w-36">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full transition-all
                                        @if ($item['progress'] == 100) bg-green-500
                                        @elseif($item['progress'] >= 50) bg-blue-500
                                        @else bg-yellow-400 @endif"
                                            style="width: {{ $item['progress'] }}%">
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500 w-8 shrink-0">{{ $item['progress'] }}%</span>
                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusConfig['class'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                                @if ($isLocked)
                                    <div class="text-xs text-gray-400 mt-2 flex justify-center items-center"><svg
                                            class="w-5 h-5 text-gray-800 fill-yellow-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M12 14v3m-3-6V7a3 3 0 1 1 6 0v4m-8 0h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z" />
                                        </svg>

                                        Locked</div>
                                @endif
                            </td>

                            {{-- REMARKS --}}
                            <td class="px-6 py-4 text-xs text-gray-600 max-w-xs">
                                @if ($remark)
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-2">
                                        <p class="font-medium text-red-700 mb-0.5">
                                            {{ $remark->givenBy->name ?? 'Moderator' }}</p>
                                        <p class="text-red-600 line-clamp-2">{{ $remark->remark }}</p>
                                    </div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- ACTION --}}
                            <td class="px-6 py-4 text-center">
                                @if ($raw === 'not_started')
                                    <a href="{{ route('expert.syllabus.preview', $item['course']->id) }}">
                                        <button
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                            Start
                                        </button>
                                    </a>
                                @elseif(in_array($raw, ['draft', 'moderator_rejected']))
                                    <a href="{{ route('expert.syllabus.preview', $item['course']->id) }}">
                                        <button
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                            {{ $raw === 'moderator_rejected' ? 'Fix & Resubmit' : 'Continue' }}
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('expert.syllabus.preview', $item['course']->id) }}">
                                        <button
                                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                            View
                                        </button>
                                    </a>
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
