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

                @foreach ($oldItems as $i => $item)
                    <div class="flex gap-3 items-center">

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

            let div = document.createElement('div');
            div.className = "flex gap-3 items-center";

            div.innerHTML = `
        <input type="text" name="outcomes[]"
            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">

        <button type="button" onclick="removeRow(this)"
            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
            Remove
        </button>
    `;

            document.getElementById('outcomeContainer').appendChild(div);
        }

        function removeRow(btn) {
            btn.parentElement.remove();
        }
    </script>
@endsection
