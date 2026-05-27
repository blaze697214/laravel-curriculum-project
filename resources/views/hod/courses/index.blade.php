@extends('layouts.hod')

@section('content')

{{-- ══════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════ --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Course Management</h1>
        <p class="text-sm text-gray-500 mt-0.5">Validate and submit department courses to CDC</p>
    </div>

    {{-- Add Course button — only when not submitted --}}
    @if (!$submission->is_submitted_to_cdc)
        <a href="{{ route('hod.courses.create') }}">
            <button class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add Course
            </button>
        </a>
    @endif
</div>


{{-- ══════════════════════════════════════════
     SUBMISSION STATUS BANNER
══════════════════════════════════════════ --}}
@if ($submission->is_submitted_to_cdc)
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-4 mb-6">
        <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
        <div class="flex-1">
            <p class="font-semibold text-sm">Submitted to CDC</p>
            <p class="text-xs text-green-700 mt-0.5">Courses have been submitted. Course code allocation is pending from CDC.</p>
        </div>
        <form method="POST" action="{{ route('hod.courses.unsubmit') }}">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">
            <button type="submit" class="text-xs text-green-700 border border-green-300 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-colors">
                Revert Submission
            </button>
        </form>
    </div>
@endif
{{-- ══════════════════════════════════════════
     VALIDATION TABLE
══════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">

    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-800">Course Category Validation</h2>
            <p class="text-xs text-gray-500 mt-0.5">Actual totals vs. targets defined in Scheme at Glance</p>
        </div>

        {{-- Overall badge --}}
        @if ($validation['overall']['is_valid'])
            <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                All valid
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                Issues found
            </span>
        @endif
    </div>

    @if (count($validation['categories']) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-3 text-left font-semibold">Category</th>
                        <th class="px-4 py-3 text-center font-semibold">Courses</th>
                        <th class="px-4 py-3 text-center font-semibold">Hours (TH+TU+PR)</th>
                        <th class="px-4 py-3 text-center font-semibold">Credits</th>
                        <th class="px-4 py-3 text-center font-semibold">Marks</th>
                        <th class="px-4 py-3 text-center font-semibold w-24">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">

                    @foreach ($validation['categories'] as $row)
                        @php
                            $ok = $row['is_valid'];
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition-colors {{ $ok ? '' : 'bg-red-50/30' }}">

                            {{-- Category name --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="inline-block w-2 h-2 rounded-full {{ $ok ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $row['category']->courseCategory->abbreviation }}</p>
                                        <p class="text-xs text-gray-400">{{ $row['category']->courseCategory->name }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Courses --}}
                            <td class="px-4 py-4 text-center">
                                @include('partials._val_cell', [
                                    'actual' => $row['actual']['courses'],
                                    'target' => $row['target']['courses'],
                                    'valid'  => $row['validations']['courses'],
                                ])
                            </td>

                            {{-- Hours --}}
                            <td class="px-4 py-4 text-center">
                                @include('partials._val_cell', [
                                    'actual' => $row['actual']['hours'],
                                    'target' => $row['target']['hours'],
                                    'valid'  => $row['validations']['hours'],
                                ])
                            </td>

                            {{-- Credits --}}
                            <td class="px-4 py-4 text-center">
                                @include('partials._val_cell', [
                                    'actual' => $row['actual']['credits'],
                                    'target' => $row['target']['credits'],
                                    'valid'  => $row['validations']['credits'],
                                ])
                            </td>

                            {{-- Marks --}}
                            <td class="px-4 py-4 text-center">
                                @include('partials._val_cell', [
                                    'actual' => $row['actual']['marks'],
                                    'target' => $row['target']['marks'],
                                    'valid'  => $row['validations']['marks'],
                                ])
                            </td>

                            {{-- Row status --}}
                            <td class="px-4 py-4 text-center">
                                @if ($ok)
                                    <span class="inline-flex items-center justify-center w-7 h-7 bg-green-100 rounded-full">
                                        <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                        </svg>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-7 h-7 bg-red-100 rounded-full">
                                        <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                        </svg>
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @endforeach

                </tbody>

                {{-- Overall totals footer --}}
                <tfoot>
                    <tr class="bg-gray-100 border-t-2 border-gray-200 font-semibold text-gray-700">
                        <td class="px-6 py-3 text-sm">Overall Total</td>

                        <td class="px-4 py-3 text-center text-sm">
                            <span class="{{ $validation['overall']['validations']['courses'] ? 'text-green-700' : 'text-red-600' }}">
                                {{ $validation['overall']['actual']['courses'] }}
                            </span>
                            <span class="text-gray-400 mx-1">/</span>
                            <span class="text-gray-600">{{ $validation['overall']['target']['courses'] }}</span>
                        </td>

                        <td class="px-4 py-3 text-center text-sm">
                            <span class="{{ $validation['overall']['validations']['hours'] ? 'text-green-700' : 'text-red-600' }}">
                                {{ $validation['overall']['actual']['hours'] }}
                            </span>
                            <span class="text-gray-400 mx-1">/</span>
                            <span class="text-gray-600">{{ $validation['overall']['target']['hours'] }}</span>
                        </td>

                        <td class="px-4 py-3 text-center text-sm">
                            <span class="{{ $validation['overall']['validations']['credits'] ? 'text-green-700' : 'text-red-600' }}">
                                {{ $validation['overall']['actual']['credits'] }}
                            </span>
                            <span class="text-gray-400 mx-1">/</span>
                            <span class="text-gray-600">{{ $validation['overall']['target']['credits'] }}</span>
                        </td>

                        <td class="px-4 py-3 text-center text-sm">
                            <span class="{{ $validation['overall']['validations']['marks'] ? 'text-green-700' : 'text-red-600' }}">
                                {{ $validation['overall']['actual']['marks'] }}
                            </span>
                            <span class="text-gray-400 mx-1">/</span>
                            <span class="text-gray-600">{{ $validation['overall']['target']['marks'] }}</span>
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if ($validation['overall']['is_valid'])
                                <span class="text-xs font-bold text-green-600">✓ Valid</span>
                            @else
                                <span class="text-xs font-bold text-red-500">✗ Invalid</span>
                            @endif
                        </td>
                    </tr>
                </tfoot>

            </table>
        </div>

    @else
        {{-- No scheme-at-glance configured --}}
        <div class="flex flex-col items-center justify-center py-14 text-center">
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-600">Scheme at Glance not configured</p>
            <p class="text-xs text-gray-400 mt-1 max-w-xs">Configure your scheme structure first before validating courses.</p>
            <a href="{{ route('hod.scheme.edit') }}" class="mt-4 text-xs text-blue-600 hover:underline font-medium">
                Configure Scheme at Glance →
            </a>
        </div>
    @endif

</div>


{{-- ══════════════════════════════════════════
     QUICK ACTIONS
══════════════════════════════════════════ --}}
<div class="flex items-center gap-3 flex-wrap">

    {{-- View Courses --}}
    <a href="{{ route('hod.courses.view') }}">
        <button class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>
            </svg>
            View All Courses
        </button>
    </a>

    {{-- Submit to CDC — only when not yet submitted --}}
    @if (!$submission->is_submitted_to_cdc)
        <form method="POST"
              action="{{ route('hod.courses.submit') }}"
              onsubmit="return confirm('Submit all department courses to CDC for course code allocation?')">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                </svg>
                Submit to CDC
            </button>
        </form>
    @endif

</div>

{{-- Inline partial for a value cell --}}
{{-- Save as resources/views/partials/_val_cell.blade.php or inline below --}}

@endsection
