@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Edit Scheme
    </h1>


    <div class="bg-white p-6 rounded-xl shadow">

        <div class="overflow-x-auto rounded-xl shadow">

            <table class="w-full text-left border border-gray-200 rounded-xl overflow-hidden">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Years</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Credits</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Marks</th>
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
                                {{ $scheme->total_credits }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $scheme->total_marks }}
                            </td>


                            {{-- Active --}}
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


                            {{-- Locked --}}
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


                            {{-- Action --}}
                            <td class="px-4 py-3">

                                @if (!$scheme->is_locked)
                                    <a href="{{ route('cdc.schemes.edit', $scheme->id) }}">

                                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded">
                                            Edit
                                        </button>

                                    </a>
                                @else
                                    <button class="bg-gray-300 text-gray-600 px-4 py-1 rounded cursor-not-allowed">
                                        Locked
                                    </button>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">
                                No schemes found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
@endsection
