@extends('layouts.syllabus')

@section('content')

<h3 class="text-lg font-semibold text-gray-800 mb-4">
    Instruction Strategies
</h3>


<div class="bg-white p-6 rounded-xl shadow">

    <form method="POST" action="{{ route('expert.syllabus.instruction.save', $course->id) }}">
        @csrf
        <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">
        <p class="text-gray-600 mb-4">
            These are sample strategies, which the teacher can use to accelerate the attainment of the 
various outcomes in this course
        </p>
        <div id="container" class="space-y-3">

            @php
                $oldItems = old('items', $items->map(fn($i) => ['content' => $i->content])->toArray());
            @endphp

            @forelse ($oldItems as $index => $item)

                <div class="flex items-center gap-2">

                    <span class="item-label text-sm text-gray-600 w-6 shrink-0"></span>

                    <input type="text"
                        name="items[{{ $index }}][content]"
                        value="{{ $item['content'] ?? '' }}"
                        placeholder="Enter instruction strategy"
                        class="flex-1 border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">

                    <button type="button" onclick="removeRow(this)"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                        Remove
                    </button>

                </div>

            @empty

                <div class="flex items-center gap-2">

                    <span class="item-label text-sm text-gray-600 w-6 shrink-0"></span>

                    <input type="text"
                        name="items[0][content]"
                        placeholder="Enter instruction strategy"
                        class="flex-1 border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">

                    <button type="button" onclick="removeRow(this)"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                        Remove
                    </button>

                </div>

            @endforelse

        </div>

        <div class="mt-6 flex gap-3">

            <button type="button" onclick="addRow()"
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                + Add Item
            </button>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                Save
            </button>

        </div>

    </form>

</div>


<script>

    // ─── RE-INDEX ─────────────────────────────────────────────────────────────

    function reIndex() {
        document.querySelectorAll('#container > div').forEach((row, idx) => {

            const label = row.querySelector('.item-label');
            if (label) label.textContent = (idx + 1) + '.';

            const input = row.querySelector('input[type="text"]');
            if (input) input.name = `items[${idx}][content]`;
        });
    }

    reIndex();


    // ─── ADD ROW ──────────────────────────────────────────────────────────────

    function addRow() {
        const container = document.getElementById('container');

        const div = document.createElement('div');
        div.className = "flex items-center gap-2";
        div.innerHTML = `
            <span class="item-label text-sm text-gray-600 w-6 shrink-0"></span>

            <input type="text"
                placeholder="Enter instruction strategy"
                class="flex-1 border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">

            <button type="button" onclick="removeRow(this)"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                Remove
            </button>
        `;

        container.appendChild(div);
        reIndex();
    }


    // ─── REMOVE ROW ───────────────────────────────────────────────────────────

    function removeRow(btn) {
        btn.parentElement.remove();
        reIndex();
    }

</script>

@endsection