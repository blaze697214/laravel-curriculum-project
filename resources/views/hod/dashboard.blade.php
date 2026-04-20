@extends('layouts.hod')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

{{-- ── NO ACTIVE SCHEME ─────────────────────────────────────────────────── --}}
@if(isset($noActiveScheme) && $noActiveScheme)
    <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-xl p-6 text-center">
        <p class="font-semibold text-lg mb-1">No Active Scheme</p>
        <p class="text-sm">The CDC has not activated a scheme yet. Please check back later.</p>
    </div>
    @php return; @endphp
@endif

{{-- ── SCHEME BANNER ───────────────────────────────────────────────────── --}}
<div class="bg-slate-800 text-white rounded-xl px-6 py-4 mb-6 flex items-center justify-between">
    <div>
        <p class="text-xs text-slate-400 uppercase tracking-wide mb-0.5">Active Scheme</p>
        <p class="text-lg font-semibold">{{ $scheme->name }}</p>
    </div>
    <div class="text-right">
        <p class="text-xs text-slate-400">{{ $scheme->year_start }} – {{ $scheme->year_end }}</p>
        <p class="text-xs text-slate-400 mt-0.5">{{ $department->name }}</p>
    </div>
</div>


{{-- ── SUMMARY CARDS ────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

    <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-blue-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Courses</p>
        <h2 class="text-3xl font-bold text-blue-600">{{ $totalCourses }}</h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-green-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Assigned</p>
        <h2 class="text-3xl font-bold text-green-600">{{ $assignedCount }}</h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-red-400">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Unassigned</p>
        <h2 class="text-3xl font-bold text-red-500">{{ $unassignedCount }}</h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-purple-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">HOD Approved</p>
        <h2 class="text-3xl font-bold text-purple-600">{{ $syllabusStats['hod_approved'] }}</h2>
    </div>

</div>


{{-- ── SYLLABUS PIPELINE ────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl shadow p-6 mb-6">

    <h2 class="text-base font-semibold text-gray-700 mb-4">Syllabus Pipeline</h2>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">

        @php
            $pipeline = [
                ['label' => 'Not Started',  'count' => $syllabusStats['not_started'],        'color' => 'bg-gray-100 text-gray-500'],
                ['label' => 'Draft',        'count' => $syllabusStats['draft'],               'color' => 'bg-yellow-100 text-yellow-700'],
                ['label' => 'Submitted',    'count' => $syllabusStats['submitted'],           'color' => 'bg-purple-100 text-purple-700'],
                ['label' => 'Rejected',     'count' => $syllabusStats['moderator_rejected'],  'color' => 'bg-red-100 text-red-700'],
                ['label' => 'Mod. Approved','count' => $syllabusStats['moderator_approved'],  'color' => 'bg-blue-100 text-blue-700'],
            ];
        @endphp

        @foreach($pipeline as $step)
            <div class="rounded-lg p-4 text-center {{ $step['color'] }}">
                <p class="text-2xl font-bold">{{ $step['count'] }}</p>
                <p class="text-xs mt-1 font-medium">{{ $step['label'] }}</p>
            </div>
        @endforeach

    </div>

</div>


{{-- ── PENDING HOD APPROVAL ─────────────────────────────────────────────── --}}
@if(count($pendingApprovalItems) > 0)
<div class="bg-white rounded-xl shadow overflow-hidden mb-6">

    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-base font-semibold text-gray-800">Awaiting Your Approval</h2>
        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1 rounded-full">
            {{ count($pendingApprovalItems) }} ready
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
                @foreach($pendingApprovalItems as $item)
                    <tr class="hover:bg-blue-50 transition-colors">

                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $item['course']->title }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $item['course']->abbreviation }}</div>
                        </td>

                        <td class="px-6 py-4 text-gray-600 text-sm">
                            {{ $item['expert']->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-center w-36">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full bg-green-500"
                                         style="width: {{ $item['progress'] }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-8 shrink-0">{{ $item['progress'] }}%</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('hod.syllabus.preview', $item['course']->id) }}">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                    Review & Approve
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


{{-- ── TWO COLUMN: TEAM + SETUP ─────────────────────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- TEAM --}}
    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-base font-semibold text-gray-700 mb-4">Team</h2>

        <div class="space-y-3">

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-700">Experts</p>
                    <p class="text-xs text-gray-400">Course syllabus authors</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-bold text-gray-800">{{ $expertCount }}</span>
                    <a href="{{ route('hod.users.expert.index') }}">
                        <button class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded-lg transition-colors">
                            Manage
                        </button>
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-700">Moderators</p>
                    <p class="text-xs text-gray-400">Syllabus reviewers</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-bold text-gray-800">{{ $moderatorCount }}</span>
                    <a href="{{ route('hod.users.moderator.index') }}">
                        <button class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded-lg transition-colors">
                            Manage
                        </button>
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-700">Unassigned Courses</p>
                    <p class="text-xs text-gray-400">Need an expert assigned</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-bold {{ $unassignedCount > 0 ? 'text-red-500' : 'text-green-600' }}">
                        {{ $unassignedCount }}
                    </span>
                    <a href="{{ route('hod.assign.index') }}">
                        <button class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded-lg transition-colors">
                            Assign
                        </button>
                    </a>
                </div>
            </div>

        </div>

    </div>


    {{-- SCHEME SETUP (programme only) --}}
    @if($isProgramme)
    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-base font-semibold text-gray-700 mb-4">Scheme Setup</h2>

        <div class="space-y-3">

            {{-- PSO --}}
            <div class="flex items-center justify-between p-3 rounded-lg {{ $psoCount > 0 ? 'bg-green-50' : 'bg-red-50' }}">
                <div>
                    <p class="text-sm font-medium {{ $psoCount > 0 ? 'text-green-800' : 'text-red-800' }}">
                        Programme Specific Outcomes
                    </p>
                    <p class="text-xs {{ $psoCount > 0 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $psoCount > 0 ? $psoCount . ' PSOs defined' : 'Not configured' }}
                    </p>
                </div>
                <a href="{{ route('hod.pso') }}">
                    <button class="text-xs bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-3 py-1.5 rounded-lg transition-colors">
                        {{ $psoCount > 0 ? 'Edit' : 'Setup' }}
                    </button>
                </a>
            </div>

            {{-- ELECTIVE GROUPS --}}
            <div class="flex items-center justify-between p-3 rounded-lg {{ $electiveGroupCount > 0 ? 'bg-green-50' : 'bg-yellow-50' }}">
                <div>
                    <p class="text-sm font-medium {{ $electiveGroupCount > 0 ? 'text-green-800' : 'text-yellow-800' }}">
                        Elective Groups
                    </p>
                    <p class="text-xs {{ $electiveGroupCount > 0 ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $electiveGroupCount > 0 ? $electiveGroupCount . ' groups created' : 'None created yet' }}
                    </p>
                </div>
                <a href="{{ route('hod.elective.index') }}">
                    <button class="text-xs bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-3 py-1.5 rounded-lg transition-colors">
                        Manage
                    </button>
                </a>
            </div>

            {{-- CLASS AWARD --}}
            <div class="flex items-center justify-between p-3 rounded-lg {{ $classAwardConfigured ? 'bg-green-50' : 'bg-red-50' }}">
                <div>
                    <p class="text-sm font-medium {{ $classAwardConfigured ? 'text-green-800' : 'text-red-800' }}">
                        Class Award Configuration
                    </p>
                    <p class="text-xs {{ $classAwardConfigured ? 'text-green-600' : 'text-red-500' }}">
                        {{ $classAwardConfigured ? 'Configured' : 'Not configured' }}
                    </p>
                </div>
                <a href="{{ route('hod.class_award.index') }}">
                    <button class="text-xs bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-3 py-1.5 rounded-lg transition-colors">
                        {{ $classAwardConfigured ? 'Edit' : 'Setup' }}
                    </button>
                </a>
            </div>

        </div>

    </div>
    @else
    {{-- SERVICE DEPT: just quick links --}}
    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-base font-semibold text-gray-700 mb-4">Quick Links</h2>

        <div class="space-y-2">
            <a href="{{ route('hod.courses.view') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                <span class="text-sm font-medium text-gray-700">View Courses</span>
                <span class="text-gray-400 text-xs">→</span>
            </a>
            <a href="{{ route('hod.assign.index') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                <span class="text-sm font-medium text-gray-700">Assign Courses</span>
                <span class="text-gray-400 text-xs">→</span>
            </a>
            <a href="{{ route('hod.syllabus.index') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                <span class="text-sm font-medium text-gray-700">Syllabus Approval</span>
                <span class="text-gray-400 text-xs">→</span>
            </a>
        </div>

    </div>
    @endif

</div>

@endsection