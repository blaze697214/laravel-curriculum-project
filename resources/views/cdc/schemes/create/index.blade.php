@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Create Scheme
    </h1>



    {{-- ================= FORM ================= --}}
    <div class="bg-white p-6 rounded-xl shadow mb-10">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            New Scheme
        </h2>

        <form method="POST" action="{{ route('cdc.schemes.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf

            <div>
                <label class="block text-sm text-gray-600 mb-1">Scheme Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Year Start</label>
                <select name="year_start" id="yearStart" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    <option value="">Select Year</option>

                    @for ($y = date('Y'); $y <= date('Y') + 5; $y++)
                        <option value="{{ $y }}" {{ old('year_start') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor

                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Year End</label>
                <input name="year_end" type="text" id="yearEnd" readonly value="{{ old('year_end') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Total Credits</label>
                <input type="number" min="0" name="total_credits" value="{{ old('total_credits') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Total Marks</label>
                <input type="number" min="0" name="total_marks" value="{{ old('total_marks') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    Create Scheme
                </button>
            </div>

        </form>

    </div>



    {{-- ================= TABLE ================= --}}
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Existing Schemes
        </h2>

        <div class="overflow-x-auto rounded-xl shadow">

            <table class="w-full text-left border border-gray-200 rounded-xl overflow-hidden">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Years</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Credits</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Marks</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Locked</th>
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

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                No schemes found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    <script>
        const yearStart = document.getElementById('yearStart');
        const yearEnd = document.getElementById('yearEnd');

        yearStart.addEventListener('change', function() {

            let start = parseInt(this.value);

            if (start) {
                yearEnd.value = start + 3;
            }

        });
    </script>
@endsection
