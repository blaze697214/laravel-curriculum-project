@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Edit Scheme Details
    </h1>





    {{-- ================= FORM ================= --}}
    <div class="bg-white p-6 rounded-xl shadow w-full">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Scheme Information
        </h2>

        <form method="POST" action="{{ route('cdc.schemes.update', $scheme->id) }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm text-gray-600 mb-1">Scheme Name</label>
                <input type="text" name="name" value="{{ old('name', $scheme->name) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Year Start</label>
                    <select name="year_start" id="yearStart" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <option value="">Select Year</option>

                        @for ($y = date('Y'); $y <= date('Y') + 5; $y++)
                            <option value="{{ $y }}"
                                {{ old('year_start', $scheme->year_start) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor

                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Year End</label>
                    <input name="year_end" type="text" id="yearEnd" readonly
                        value="{{ old('year_end', $scheme->year_end) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 ">
                </div>

            </div>

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Total Credits</label>
                    <input type="number" min="0" name="total_credits"
                        value="{{ old('total_credits', $scheme->total_credits) }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Total Marks</label>
                    <input type="number" min="0" name="total_marks"
                        value="{{ old('total_marks', $scheme->total_marks) }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

            </div>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Update Scheme
            </button>

        </form>

    </div>



    {{-- ================= NAVIGATION BUTTONS ================= --}}
    <div class="mt-6 flex justify-between ">

        {{-- BACK --}}
        <a href="{{ route('cdc.schemes.edit.index') }}">
            <button class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg transition w-40">
                ← Back
            </button>
        </a>

        {{-- NEXT --}}
        <a href="{{ route('cdc.schemes.edit.categories', $scheme->id) }}">
            <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg transition w-40">
                Next →
            </button>
        </a>

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
