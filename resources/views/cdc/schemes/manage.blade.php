@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Manage Schemes
    </h1>




    <div class="bg-white p-6 rounded-xl shadow">

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-50">Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-40">Years</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-60 text-center">Active</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-60 text-center">Locked</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600 text-center">Action</th>
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


                            {{-- ACTIVE TOGGLE --}}
                            <td class="px-4 py-3 text-center">
                                <form method="POST" action="{{ route('cdc.schemes.toggleActive', $scheme->id) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                        class="px-3 py-1 rounded  text-gray-800
                                    {{ $scheme->is_active
                                        ? 'bg-green-600 text-white hover:bg-green-700'
                                        : 'bg-gray-300 text-gray-700 hover:bg-gray-400' }}">

                                        {{ $scheme->is_active ? 'Deactivate' : 'Activate' }}

                                    </button>
                                </form>
                            </td>


                            {{-- LOCK TOGGLE --}}
                            <td class="px-4 py-3 text-center">
                                <form method="POST" action="{{ route('cdc.schemes.toggleLock', $scheme->id) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                        class="px-3 py-1 rounded  text-gray-800
                                    {{ $scheme->is_locked
                                        ? 'bg-red-600 text-white hover:bg-red-700'
                                        : 'bg-gray-300 text-gray-700 hover:bg-gray-400' }}">

                                        {{ $scheme->is_locked ? 'Unlock' : 'Lock' }}

                                    </button>
                                </form>
                            </td>


                            {{-- DELETE --}}
                            <td class="px-4 py-3 text-center">

                                @if (!$scheme->is_locked)
                                    <form method="POST" action="{{ route('cdc.schemes.destroy', $scheme->id) }}"
                                        onsubmit="return confirm('Delete this scheme?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                            Delete
                                        </button>

                                    </form>
                                @else
                                    <button class="bg-gray-300 text-gray-600 px-3 py-1 rounded cursor-not-allowed">
                                        Unavailable
                                    </button>
                                @endif

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
