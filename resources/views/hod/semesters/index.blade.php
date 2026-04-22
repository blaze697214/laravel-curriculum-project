@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold mb-6">
        Semester Tables
    </h1>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Semester</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-700">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach ($semesters as $sem)
                    <tr class="hover:bg-gray-50 border-gray-200">

                        {{-- SEMESTER --}}
                        <td class="px-6 py-4 font-medium text-gray-800">
                            Semester {{ $sem['number'] }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4 text-center">

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

                        {{-- ACTION --}}
                        <td class="px-6 py-4 text-center">

                            @if ($sem['configured'])
                                <div class="flex justify-center gap-3">
                                    <a
                                        href="{{ route('hod.semesters.preview',  $sem['number']) }}">
                                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">
                                            View
                                        </button>
                                    </a>
                                    <a href="{{ route('hod.semesters.print',  $sem['number']) }}"
                                        target="_blank">
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
@endsection
