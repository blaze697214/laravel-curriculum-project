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
                    $oldItems = old('outcomes', $outcomes->pluck('description')->toArray());
                @endphp

                @foreach ($oldItems as $i => $item)
                    <div class="flex items-center gap-3">

                        <strong class="w-14 text-gray-700">
                            CO{{ $i + 1 }}:
                        </strong>

                        <input type="text" name="outcomes[]" value="{{ $item }}"
                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>
                @endforeach

            </div>


            {{-- ADD BUTTON --}}
            <div class="mt-4">
                <button type="button" onclick="addRow()"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm">
                    + Add CO
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


    <script>
        function refreshLabels() {
            document.querySelectorAll('#coContainer > div').forEach((row, index) => {
                row.querySelector('strong').innerText = "CO" + (index + 1) + ":";
            });
        }

        function addRow() {

            let div = document.createElement('div');
            div.className = "flex items-center gap-3";

            div.innerHTML = `
        <strong class="w-14 text-gray-700"></strong>

        <input type="text" name="outcomes[]"
            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">

        <button type="button" onclick="removeRow(this)"
            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
            Remove
        </button>
    `;

            document.getElementById('coContainer').appendChild(div);
            refreshLabels();
        }

        function removeRow(btn) {
            btn.parentElement.remove();
            refreshLabels();
        }

        refreshLabels();
    </script>
@endsection
