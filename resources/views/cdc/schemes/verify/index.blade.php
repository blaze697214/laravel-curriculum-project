@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Verify Schemes
    </h1>


    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif



    <div class="bg-white p-6 rounded-xl shadow">

        <div class="overflow-x-auto rounded-xl shadow">

            <table class="w-full text-left border border-gray-200 rounded-xl overflow-hidden">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Years</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Active</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Locked</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($schemes as $scheme)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3">
                                {{ $scheme->name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $scheme->year_start }} - {{ $scheme->year_end }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($scheme->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-600 rounded">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @if ($scheme->is_locked)
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                        Locked
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-600 rounded">
                                        Unlocked
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">

                                <a href="{{ route('cdc.schemes.verify.departments', $scheme->id) }}">

                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">
                                        Verify
                                    </button>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">
                                No schemes found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
@endsection
