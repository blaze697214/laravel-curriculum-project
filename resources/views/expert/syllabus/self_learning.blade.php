@extends('layouts.syllabus')

@section('content')
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        Self Learning
    </h3>


    <div class="bg-white p-6 rounded-xl shadow">

        @if($course->sla_marks > 0)
        <form method="POST" action="{{ route('expert.syllabus.self-learning.save', $course->id) }}">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">


            <div id="container" class="space-y-3">

                @forelse($items as $index => $item)
                    <div class="flex items-center gap-2">

                        <span class="item-label text-sm text-gray-600 w-6 shrink-0"></span>

                        <input type="text" name="items[{{ $index }}][content]"
                            value="{{ old("items.$index.content", $item->content) }}"
                            class="flex-1 border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter self learning item">

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>

                @empty

                    <div class="flex items-center gap-2">

                        <span class="item-label text-sm text-gray-600 w-6 shrink-0"></span>

                        <input type="text" name="items[0][content]"
                            class="flex-1 border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter self learning item">

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>

                @endforelse

            </div>


            {{-- ACTION BUTTONS --}}
            <div class="mt-6 flex gap-3">

                <button type="button" onclick="addRow()"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                    + Add Item
                </button>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                    Save
                </button>

            </div>

        </form>
        @else
        <div class="flex justify-center h-40 items-center text-xl font-semibold text-gray-400">
            Self Learning is NOT applicable for this Course
        </div>
        @endif

    </div>



    {{-- ================= JS ================= --}}
    <script>

        // ─── RE-INDEX ─────────────────────────────────────────────────────────
        // Rewrites serial numbers and input name indices after every add/remove

        function reIndex() {
            document.querySelectorAll('#container > div').forEach((row, idx) => {

                // Number label
                const label = row.querySelector('.item-label');
                if (label) label.textContent = (idx + 1) + '.';

                // Input name
                const input = row.querySelector('input[type="text"]');
                if (input) input.name = `items[${idx}][content]`;
            });
        }

        // Run on page load
        reIndex();


        // ─── ADD ROW ──────────────────────────────────────────────────────────

        function addRow() {
            const container = document.getElementById('container');

            const div = document.createElement('div');
            div.className = "flex items-center gap-2";
            div.innerHTML = `
                <span class="item-label text-sm text-gray-600 w-6 shrink-0"></span>

                <input type="text"
                    class="flex-1 border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter self learning item">

                <button type="button" onclick="removeRow(this)"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                    Remove
                </button>
            `;

            container.appendChild(div);
            reIndex(); // assigns correct number + name to the new row
        }


        // ─── REMOVE ROW ───────────────────────────────────────────────────────

        function removeRow(btn) {
            btn.parentElement.remove();
            reIndex(); // re-number remaining rows
        }

    </script>
@endsection