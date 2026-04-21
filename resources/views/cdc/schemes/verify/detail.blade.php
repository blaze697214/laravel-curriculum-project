@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Verify - {{ $department->name }} ({{ $scheme->name }})
    </h1>


    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif



    <div class="bg-white p-6 rounded-xl shadow">

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Section</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">


                    {{-- ================= SCHEME AT GLANCE ================= --}}
                    <tr class="hover:bg-gray-50 border-gray-200">

                        <td class="px-4 py-3 ">
                            Scheme At Glance<span class="ml-2 rounded-full bg-yellow-300 px-2 py-1 text-xs text-gray-600 font-semibold">In Development</span>
                        </td>

                        <td class="px-4 py-3">

                            @if ($status['scheme_at_glance'])
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                    Configured
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                    Missing
                                </span>
                            @endif

                        </td>

                        <td class="px-4 py-3">

                            @if ($status['scheme_at_glance'])
                                {{-- {{ route('cdc.schemes.verify.preview.scheme', [$scheme->id, $department->id]) }} --}}
                                <a href="">
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">
                                        View
                                    </button>
                                </a>
                            @else
                                <button class="bg-gray-300 text-gray-600 px-4 py-1 rounded cursor-not-allowed">
                                    Unavailable
                                </button>
                            @endif

                        </td>

                    </tr>


                    {{-- ================= SEMESTERS ================= --}}
                    <tr class="hover:bg-gray-50 border-gray-200">

                        <td class="px-4 py-3 ">
                            Semester Tables (1–6) <span class="ml-2 rounded-full bg-yellow-300 px-2 py-1 text-xs text-gray-600 font-semibold">In Development</span>
                        </td>

                        <td class="px-4 py-3">

                            @if ($status['all_semesters_configured'])
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                    Configured
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded">
                                    Incomplete
                                </span>
                            @endif

                        </td>

                        <td class="px-4 py-3">

                            <a href="{{ route('cdc.schemes.verify.semesters', [$scheme->id, $department->id]) }}">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">
                                    View
                                </button>
                            </a>

                        </td>

                    </tr>


                    {{-- ================= CLASS AWARD ================= --}}
                    <tr class="hover:bg-gray-50 border-gray-200">

                        <td class="px-4 py-3 ">
                            Class Award<span class="ml-2 rounded-full bg-yellow-300 px-2 py-1 text-xs text-gray-600 font-semibold">In Development</span>
                        </td>

                        <td class="px-4 py-3">

                            @if ($status['class_award'])
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                    Configured
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                    Missing
                                </span>
                            @endif

                        </td>

                        <td class="px-4 py-3">

                            @if ($status['class_award'])
                                <a href="{{ route('cdc.schemes.verify.class-award', [$scheme->id, $department->id]) }}">
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">
                                        View
                                    </button>
                                </a>
                            @else
                                <button class="bg-gray-300 text-gray-600 px-4 py-1 rounded cursor-not-allowed">
                                    Unavailable
                                </button>
                            @endif

                        </td>

                    </tr>

                    <tr class="hover:bg-gray-50 border-gray-200">

                        <td class="px-4 py-3 ">
                            Syllabus
                        </td>

                        <td class="px-4 py-3">

                            @if ($status['syllabus'])
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                    Completed
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                    Pending
                                </span>
                            @endif

                        </td>

                        <td class="px-4 py-3">

                            <a href="{{ route('cdc.schemes.verify.syllabus', [$scheme->id,$department->id]) }}">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">
                                    View
                                </button>
                            </a>

                        </td>

                    </tr>


                </tbody>

            </table>

        </div>

    </div>



    {{-- NAVIGATION --}}
    <div class="mt-6">

        <a href="{{ route('cdc.schemes.verify.departments', $scheme->id) }}">
            <button class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg transition">
                ← Back
            </button>
        </a>

    </div>
@endsection
