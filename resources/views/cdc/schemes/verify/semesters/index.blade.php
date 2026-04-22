@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Semester Verification - {{ $department->name }}
    </h1>


    <div class="bg-white p-6 rounded-xl shadow">

        <div class="overflow-x-auto rounded-xl shadow">

            <table class="w-full text-left border border-gray-200 rounded-xl overflow-hidden">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Semester</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach ($semesters as $sem)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3">
                                Semester {{ $sem['number'] }}
                            </td>

                            <td class="px-4 py-3">

                                @if ($sem['configured'])
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

                                @if ($sem['configured'])
                                    <div class="flex justify-center gap-3">
                                        <a href="{{ route('cdc.schemes.verify.semester.preview', [$scheme->id, $department->id, $sem['number']]) }}">
                                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">
                                            View
                                        </button>
                                    </a>
                                    <a href="{{ route('cdc.schemes.verify.semester.print', [$scheme->id, $department->id, $sem['number']]) }}" target="_blank">
                                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">
                                            Print
                                        </button>
                                    </a>
                                    </div>
                                @else
                                    <button class="bg-gray-300 text-gray-600 px-4 py-1 rounded cursor-not-allowed">
                                        Unavailable
                                    </button>
                                @endif

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>



    {{-- NAVIGATION --}}
    <div class="mt-6">

        <a href="{{ route('cdc.schemes.verify.department.detail', [$scheme->id, $department->id]) }}">
            <button class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg transition">
                ← Back
            </button>
        </a>

    </div>
@endsection
