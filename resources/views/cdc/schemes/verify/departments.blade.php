@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Verify Scheme - {{ $scheme->name }}
    </h1>

    <div class="bg-white p-6 rounded-xl shadow">

        <div class="overflow-x-auto rounded-xl shadow">

            <table class="w-full text-left border border-gray-200 rounded-xl overflow-hidden">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Department</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Abbreviation</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($departments as $dept)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3">
                                {{ $dept->name }}
                            </td>

                            <td class="px-4 py-3 text-gray-500">
                                {{ $dept->abbreviation ?? '-' }}
                            </td>

                            <td class="px-4 py-3">

                                @if ($statuses[$dept->id] === 'Complete')
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                        Ready
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                        Incomplete
                                    </span>
                                @endif

                            </td>

                            <td class="px-4 py-3">

                                <a href="{{ route('cdc.schemes.verify.department.detail', [$scheme->id, $dept->id]) }}">

                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">
                                        View
                                    </button>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                No departments found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- NAVIGATION --}}
    <div class="mt-6">

        <a href="{{ route('cdc.schemes.verify.index') }}">
            <button class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg transition">
                ← Back
            </button>
        </a>

    </div>
@endsection
