@extends('layouts.syllabus')

@section('content')
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        Course Outcomes (CO)
    </h3>

    <div class="bg-white p-6 rounded-xl shadow">

        <form method="POST" action="{{ route('expert.syllabus.co.save', $course->id) }}">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

            <p class="text-gray-600 mb-4">
                The course content should be taught and learning imparted in such a manner that students are
                able to acquire required learning outcome in cognitive, psychomotor and affective domain to
                demonstrate following course outcomes:
            </p>

            <div id="coContainer" class="space-y-3">

                @php
                    $oldItems = old('outcomes', $outcomes->map(fn($out) => [
                        'co_code'     => $out->co_code,
                        'description' => $out->description,
                    ])->toArray());
                @endphp

                @forelse ($oldItems as $i => $item)
                    <div class="flex items-center gap-3">

                        <input type="text"
                            name="outcomes[{{ $i }}][co_code]"
                            value="{{ $item['co_code'] ?? '' }}"
                            placeholder="CO{{ $i + 1 }}"
                            class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <input type="text"
                            name="outcomes[{{ $i }}][description]"
                            value="{{ $item['description'] ?? '' }}"
                            placeholder="Enter course outcome..."
                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>
                @empty
                    <div class="flex items-center gap-3">

                        <input type="text"
                            name="outcomes[0][co_code]"
                            placeholder="CO{{ 1 }}"
                            class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <input type="text"
                            name="outcomes[0][description]"
                            placeholder="Enter course outcome..."
                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>
                @endforelse

            </div>

            <div class="mt-4">
                <button type="button" onclick="addRow()"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm">
                    + Add CO
                </button>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                    Save
                </button>
            </div>

        </form>
    </div>

    <script>
        function getNextIndex() {
            return document.querySelectorAll('#coContainer > div').length;
        }

        function addRow() {
            let index = getNextIndex();

            let div = document.createElement('div');
            div.className = "flex items-center gap-3";

            div.innerHTML = `
                <input type="text"
                    name="outcomes[${index}][co_code]"
                    placeholder="CO${index + 1}"
                    class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <input type="text"
                    name="outcomes[${index}][description]"
                    placeholder="Enter course outcome..."
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <button type="button" onclick="removeRow(this)"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                    Remove
                </button>
            `;

            document.getElementById('coContainer').appendChild(div);
            reindexRows();
        }

        function removeRow(btn) {
            btn.parentElement.remove();
            reindexRows();
        }

        // keep name indices sequential so POST array has no gaps
        function reindexRows() {
            document.querySelectorAll('#coContainer > div').forEach((row, index) => {
                let inputs = row.querySelectorAll('input[type="text"]');
                inputs[0].name = `outcomes[${index}][co_code]`;
                inputs[0].placeholder = `CO${index + 1}`;
                inputs[1].name = `outcomes[${index}][description]`;
            });
        }
    </script>
@endsection