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
<svg class="w-6 h-6 mt-2 text-gray-800 fill-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
</svg>
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
            <div class="flex">
                <svg class="w-6 h-6 mt-1.5 text-gray-800 fill-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 19V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v13H7a2 2 0 0 0-2 2Zm0 0a2 2 0 0 0 2 2h12M9 3v14m7 0v4"/>
</svg>

                <div class="ml-1"><p class="text-sm font-medium text-blue-800">Courses</p>
                <p class="text-xs text-blue-600">
                    {{ $schemeStats['totalCourses'] }} total · {{ $schemeStats['assignedCourses'] }} assigned
                </p></div>
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
           <svg class="w-6 h-6 text-gray-800 group-hover:text-slate-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
  <path fill-rule="evenodd" d="M4 4a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2v14a1 1 0 1 1 0 2H5a1 1 0 1 1 0-2V5a1 1 0 0 1-1-1Zm5 2a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H9Zm5 0a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1h-1Zm-5 4a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1H9Zm5 0a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-1Zm-3 4a2 2 0 0 0-2 2v3h2v-3h2v3h2v-3a2 2 0 0 0-2-2h-2Z" clip-rule="evenodd"/>
</svg>


            <span class="text-sm font-medium text-gray-700 group-hover:text-slate-900">Departments</span>
        </a>

        <a href="{{ route('cdc.users.index') }}"
           class="flex flex-col items-center p-4 bg-gray-50 hover:bg-blue-50 rounded-xl transition-colors text-center group">
           <svg class="w-6 h-6 text-gray-800 group-hover:text-blue-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
  <path fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z" clip-rule="evenodd"/>
</svg>



            <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Users</span>
        </a>

        <a href="{{ route('cdc.schemes.create') }}"
           class="flex flex-col items-center p-4 bg-gray-50 hover:bg-purple-50 rounded-xl transition-colors text-center group">
           <svg class="w-6 h-6 text-gray-800 group-hover:text-purple-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
  <path fill-rule="evenodd" d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Zm6 1h-4v2H9a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2h-1V4Zm-3 8a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Zm2 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Z" clip-rule="evenodd"/>
</svg>

            <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Create Scheme</span>
        </a>

        <a href="{{ route('cdc.schemes.verify.index') }}"
           class="flex flex-col items-center p-4 bg-gray-50 hover:bg-green-50 rounded-xl transition-colors text-center group">
           <svg class="w-6 h-6 text-gray-800 group-hover:text-green-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
</svg>

            <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Verify Scheme</span>
        </a>

    </div>

</div>

@endsection