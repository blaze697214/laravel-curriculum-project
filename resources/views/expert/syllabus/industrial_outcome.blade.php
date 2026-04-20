@extends('layouts.syllabus')

@section('content')
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        Industrial Outcomes
    </h3>


    <div class="bg-white p-6 rounded-xl shadow">

        <form method="POST" action="{{ route('expert.syllabus.industrial.save', $course->id) }}">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">


            {{-- ================= FIXED TEXT ================= --}}
            <p class="text-gray-600 mb-4">
                The Aim of this course is to help the students to attain these industry-identified outcomes
                through various teaching learning experiences:
            </p>


            {{-- ================= INPUT LIST ================= --}}
            <div id="outcomeContainer" class="space-y-3">

                @php
                    $oldItems = old('outcomes', $outcomes->pluck('content')->toArray());
                @endphp

                @forelse ($oldItems as $index => $item)
                    <div class="flex items-center gap-2">

                        <span class="item-label text-sm text-gray-600 w-6 shrink-0"></span>

                        <input type="text" name="outcomes[{{ $index }}][content]" value="{{ $item }}" 
                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>
                @empty
                    <div class="flex items-center gap-2">

                        <span class="item-label text-sm text-gray-600 w-6 shrink-0"></span>

                        <input type="text" name="outcomes[0][content]" placeholder="Enter Outcome"
                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>
                @endforelse

            </div>


            {{-- ADD BUTTON --}}
            <div class="mt-4">
                <button type="button" onclick="addRow()"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm">
                    + Add Outcome
                </button>
            </div>


            {{-- SAVE --}}
            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                    Save
                </button>
            </div>

        </form>

    </div>


    {{-- ================= JS ================= --}}
    <script>
        function addRow() {
            let container = document.getElementById('outcomeContainer');

            let div = document.createElement('div');
            div.className = "flex items-center gap-2";

            div.innerHTML = `
        <span class="item-label text-sm text-gray-600 w-6 shrink-0"></span>

        <input type="text"
            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
            placeholder="Enter outcome">

        <button type="button" onclick="removeRow(this)"
            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
            Remove
        </button>
    `;

            container.appendChild(div);
            reIndex();
        }

        function reIndex() {
            document.querySelectorAll('#outcomeContainer > div').forEach((row, idx) => {

                const label = row.querySelector('.item-label');
                if (label) label.textContent = (idx + 1) + '.';

                const input = row.querySelector('input[type="text"]');
                if (input) input.name = `outcomes[${idx}][content]`;
            });
        }

        function removeRow(btn) {
            btn.parentElement.remove();
            reIndex();
        }
        document.addEventListener('DOMContentLoaded', function() {
            reIndex();
        });
    </script>
@endsection
