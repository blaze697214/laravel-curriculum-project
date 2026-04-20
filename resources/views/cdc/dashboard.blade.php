@extends('layouts.cdc')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

{{-- ── TOP STATS ─────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

    <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-blue-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Schemes</p>
        <h2 class="text-3xl font-bold text-blue-600">{{ $totalSchemes }}</h2>
        <p class="text-xs text-gray-400 mt-1">{{ $lockedSchemes }} locked</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-slate-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Departments</p>
        <h2 class="text-3xl font-bold text-slate-600">{{ $totalDepartments }}</h2>
        <p class="text-xs text-gray-400 mt-1">{{ $programmeDepts }} prog · {{ $serviceDepts }} service</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 border-purple-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">HODs</p>
        <h2 class="text-3xl font-bold text-purple-600">{{ $hodCount }}</h2>
        <p class="text-xs text-gray-400 mt-1">across all depts</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow text-center border-t-4 {{ $activeScheme ? 'border-green-500' : 'border-red-400' }}">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Active Scheme</p>
        @if($activeScheme)
            <p class="text-base font-bold text-green-600 mt-1">{{ $activeScheme->name }}</p>
            <p class="text-xs text-gray-400">{{ $activeScheme->year_start }}–{{ $activeScheme->year_end }}</p>
        @else
            <p class="text-base font-bold text-red-500 mt-2">None</p>
        @endif
    </div>

</div>


{{-- ── NO ACTIVE SCHEME WARNING ─────────────────────────────────────────── --}}
@if(!$activeScheme)
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-5 mb-6 flex items-start gap-3">
        <span class="text-red-400 text-lg mt-0.5">⚠</span>
        <div>
            <p class="font-semibold">No active scheme</p>
            <p class="text-sm mt-0.5">HODs cannot add courses or configure scheme details until a scheme is activated.</p>
            <a href="{{ route('cdc.schemes.manage') }}" class="inline-block mt-2 text-sm font-medium text-red-700 underline">
                Go to Manage Schemes →
            </a>
        </div>
    </div>
@endif


@if($activeScheme && $schemeStats)

{{-- ── ACTIVE SCHEME SETUP STATUS ──────────────────────────────────────── --}}
<div class="bg-white rounded-xl shadow p-6 mb-6">

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-gray-700">Scheme Setup — {{ $activeScheme->name }}</h2>
        <a href="{{ route('cdc.schemes.edit.index') }}" class="text-xs text-blue-600 hover:underline">Edit Scheme →</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

        {{-- POs --}}
        <div class="flex items-center gap-3 p-3 rounded-lg {{ $schemeStats['poCount'] > 0 ? 'bg-green-50' : 'bg-red-50' }}">
            <span class="text-xl">{{ $schemeStats['poCount'] > 0 ? '✓' : '✗' }}</span>
            <div>
                <p class="text-sm font-medium {{ $schemeStats['poCount'] > 0 ? 'text-green-800' : 'text-red-800' }}">
                    Programme Outcomes
                </p>
                <p class="text-xs {{ $schemeStats['poCount'] > 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ $schemeStats['poCount'] > 0 ? $schemeStats['poCount'] . ' POs defined' : 'Not configured' }}
                </p>
            </div>
        </div>

        {{-- Award Rule --}}
        <div class="flex items-center gap-3 p-3 rounded-lg {{ $schemeStats['awardRuleSet'] ? 'bg-green-50' : 'bg-red-50' }}">
            <span class="text-xl">{{ $schemeStats['awardRuleSet'] ? '✓' : '✗' }}</span>
            <div>
                <p class="text-sm font-medium {{ $schemeStats['awardRuleSet'] ? 'text-green-800' : 'text-red-800' }}">
                    Class Award Rule
                </p>
                <p class="text-xs {{ $schemeStats['awardRuleSet'] ? 'text-green-600' : 'text-red-500' }}">
                    {{ $schemeStats['awardRuleSet'] ? 'Configured' : 'Not set' }}
                </p>
            </div>
        </div>

        {{-- Courses --}}
        <div class="flex items-center gap-3 p-3 rounded-lg bg-blue-50">
            <span class="text-xl text-blue-400">📚</span>
            <div>
                <p class="text-sm font-medium text-blue-800">Courses</p>
                <p class="text-xs text-blue-600">
                    {{ $schemeStats['totalCourses'] }} total · {{ $schemeStats['assignedCourses'] }} assigned
                </p>
            </div>
        </div>

    </div>

</div>


{{-- ── DEPT VERIFICATION + SYLLABUS PIPELINE ─────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

    {{-- DEPT VERIFICATION STATUS --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-700">Department Verification</h2>
            <a href="{{ route('cdc.schemes.verify.index') }}" class="text-xs text-blue-600 hover:underline">Verify →</a>
        </div>

        <div class="p-4 grid grid-cols-2 gap-3 mb-2">
            <div class="bg-green-50 rounded-lg p-3 text-center">
                <p class="text-2xl font-bold text-green-600">{{ $schemeStats['readyCount'] }}</p>
                <p class="text-xs text-green-700 mt-0.5">Ready</p>
            </div>
            <div class="bg-red-50 rounded-lg p-3 text-center">
                <p class="text-2xl font-bold text-red-500">{{ $schemeStats['incompleteCount'] }}</p>
                <p class="text-xs text-red-600 mt-0.5">Incomplete</p>
            </div>
        </div>

        <div class="px-4 pb-4 space-y-1.5 max-h-52 overflow-y-auto">
            @foreach($schemeStats['deptStatuses'] as $ds)
                <div class="flex items-center justify-between px-3 py-2 rounded-lg {{ $ds['is_complete'] ? 'bg-green-50' : 'bg-gray-50' }}">
                    <span class="text-sm text-gray-700">{{ $ds['dept']->abbreviation }}</span>
                    @if($ds['is_complete'])
                        <span class="text-xs font-semibold text-green-700 bg-green-100 px-2 py-0.5 rounded-full">Ready</span>
                    @else
                        <span class="text-xs font-semibold text-red-600 bg-red-100 px-2 py-0.5 rounded-full">Incomplete</span>
                    @endif
                </div>
            @endforeach
        </div>

    </div>


    {{-- SYLLABUS PIPELINE --}}
    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-base font-semibold text-gray-700 mb-4">Syllabus Overview</h2>

        @php
            $total = $schemeStats['syllabusStats']['total'];
        @endphp

        @if($total === 0)
            <p class="text-sm text-gray-400 text-center py-8">No syllabuses created yet.</p>
        @else
            <div class="space-y-2.5">

                @php
                    $bars = [
                        ['label' => 'HOD Approved',       'key' => 'hod_approved',       'color' => 'bg-green-500'],
                        ['label' => 'Mod. Approved',      'key' => 'moderator_approved',  'color' => 'bg-blue-500'],
                        ['label' => 'Submitted',          'key' => 'submitted',           'color' => 'bg-purple-400'],
                        ['label' => 'Rejected',           'key' => 'moderator_rejected',  'color' => 'bg-red-400'],
                        ['label' => 'Draft',              'key' => 'draft',               'color' => 'bg-yellow-400'],
                    ];
                @endphp

                @foreach($bars as $bar)
                    @php
                        $count = $schemeStats['syllabusStats'][$bar['key']];
                        $pct   = $total > 0 ? round(($count / $total) * 100) : 0;
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500 w-28 shrink-0">{{ $bar['label'] }}</span>
                        <div class="flex-1 bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $bar['color'] }} transition-all"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 w-6 text-right">{{ $count }}</span>
                    </div>
                @endforeach

                <p class="text-xs text-gray-400 mt-1 text-right">{{ $total }} total syllabuses</p>

            </div>
        @endif

    </div>

</div>

@endif


{{-- ── QUICK LINKS ──────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-base font-semibold text-gray-700 mb-4">Quick Links</h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        <a href="{{ route('cdc.departments.index') }}"
           class="flex flex-col items-center p-4 bg-gray-50 hover:bg-slate-100 rounded-xl transition-colors text-center group">
            <span class="text-2xl mb-2">🏢</span>
            <span class="text-sm font-medium text-gray-700 group-hover:text-slate-900">Departments</span>
        </a>

        <a href="{{ route('cdc.users.index') }}"
           class="flex flex-col items-center p-4 bg-gray-50 hover:bg-blue-50 rounded-xl transition-colors text-center group">
            <span class="text-2xl mb-2">👥</span>
            <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Users</span>
        </a>

        <a href="{{ route('cdc.schemes.create') }}"
           class="flex flex-col items-center p-4 bg-gray-50 hover:bg-purple-50 rounded-xl transition-colors text-center group">
            <span class="text-2xl mb-2">📋</span>
            <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Create Scheme</span>
        </a>

        <a href="{{ route('cdc.schemes.verify.index') }}"
           class="flex flex-col items-center p-4 bg-gray-50 hover:bg-green-50 rounded-xl transition-colors text-center group">
            <span class="text-2xl mb-2">✅</span>
            <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Verify Scheme</span>
        </a>

    </div>

</div>

@endsection