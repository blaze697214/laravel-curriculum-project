@extends('layouts.syllabus')

@section('content')
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Major Equipments / Instruments</h3>

    <div class="bg-white p-6 rounded-xl shadow">


        <form method="POST" action="{{ route('expert.syllabus.equipments.save', $course->id) }}">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

            <div id="container" class="space-y-4">

                @php
                    $oldItems = old(
                        'items',
                        $equipments
                            ->map(
                                fn($e) => [
                                    'equipment_name' => $e->equipment_name,
                                    'specification' => $e->specification,
                                ],
                            )
                            ->toArray(),
                    );
                @endphp

                @forelse ($oldItems as $index => $item)
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">

                        <label class="block text-sm font-medium mb-1">
                            <span class="w-6 text-md ">
                                {{ $index + 1 }}.
                            </span>Equipment Name</label>
                        <input type="text" name="items[{{ $index }}][equipment_name]"
                            value="{{ $item['equipment_name'] ?? '' }}"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm">

                        <br><br>

                        <label class="block text-sm font-medium mb-1">Specification</label>
                        <textarea name="items[{{ $index }}][specification]"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm" rows="3">{{ $item['specification'] ?? '' }}</textarea>

                        <br><br>

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>

                @empty
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">

                        <label class="block text-sm font-medium mb-1">
                            <span class="w-6 text-md ">
                                1.
                            </span>Equipment Name</label>
                        <input type="text" name="items[0][equipment_name]"
                            value="{{ $item['equipment_name'] ?? '' }}"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm">

                        <br><br>

                        <label class="block text-sm font-medium mb-1">Specification</label>
                        <textarea name="items[0][specification]"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm" rows="3">{{ $item['specification'] ?? '' }}</textarea>

                        <br><br>

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>
                
                @endforelse

            </div>

            <div class="mt-4 flex gap-3">

                <button type="button" onclick="addRow()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                    Add Equipment
                </button>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded text-sm">
                    Save
                </button>

            </div>

        </form>

    </div>


    <script>
        let index = {{ count($oldItems) > 0 ? count($oldItems) : 1 }};

        function addRow() {

            let container = document.getElementById('container');

            let div = document.createElement('div');

            div.classList.add('border', 'border-gray-300', 'rounded-lg', 'p-4', 'bg-gray-50');

            div.innerHTML = `
        <label class="block text-sm font-medium mb-1"><span class="w-6 text-md "></span>Equipment Name</label>
        <input type="text"
               name="items[${index}][equipment_name]"
               class="w-full border border-gray-300 rounded px-3 py-2 text-sm">

        <br><br>

        <label class="block text-sm font-medium mb-1">Specification</label>
        <textarea name="items[${index}][specification]"
                  class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                  rows="3"></textarea>

        <br><br>

        <button type="button"
                onclick="removeRow(this)"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
            Remove
        </button>
    `;

            container.appendChild(div);
updateNumbers();

            index++;
        }

        function removeRow(btn) {
            btn.parentElement.remove();
            updateNumbers();

        }

        function updateNumbers() {
    let rows = document.querySelectorAll('#container > div');

    rows.forEach((row, i) => {
        row.querySelector('span').innerText = (i + 1) + '.';
    });
}

// initialize numbering
updateNumbers();
    </script>
@endsection
