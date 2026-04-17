@extends('layouts.syllabus')

@section('content')

    <h3 class="text-lg font-semibold text-gray-800 mb-4">Software / Learning Websites</h3>

<div class="bg-white p-6 rounded-xl shadow">



    <form method="POST" action="{{ route('expert.syllabus.websites.save', $course->id) }}">
    @csrf
    <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

    <div id="container" class="space-y-3">

        @php
            $oldItems = old('items', $websites->map(fn($w) => ['url' => $w->url, 'description' => $w->description])->toArray());
        @endphp

        @forelse ($oldItems as $index => $item)
            <div class="flex items-center gap-3">

                <span class="w-6 text-sm text-gray-600">
                    {{ $index + 1 }}.
                </span>

                <input type="text"
                       name="items[{{ $index }}][url]"
                       value="{{ $item['url'] ?? '' }}"
                       placeholder="Enter website URL"
                       class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm">

                <input type="text"
                       name="items[{{ $index }}][description]"
                       value="{{ $item['description'] ?? '' }}"
                       placeholder="Description"
                       class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm">

                <button type="button"
                        onclick="removeRow(this)"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                    Remove
                </button>

            </div>
        @empty
            <div class="flex items-center gap-3">

                <span class="w-6 text-sm text-gray-600">
                    1.
                </span>

                <input type="text"
                       name="items[0][url]"
                       value="{{ $item['url'] ?? '' }}"
                       placeholder="Enter website URL"
                       class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm">
                
                <input type="text"
                       name="items[0][description]"
                       value="{{ $item['description'] ?? '' }}"
                       placeholder="Description"
                       class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm">


                <button type="button"
                        onclick="removeRow(this)"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                    Remove
                </button>

            </div>
        @endforelse

    </div>

    <div class="mt-4 flex gap-3">
        <button type="button"
                onclick="addRow()"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
            Add Website
        </button>

        <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded text-sm">
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

    div.classList.add('flex', 'items-center', 'gap-3');

    div.innerHTML = `
        <span class="w-6 text-sm text-gray-600"></span>

        <input type="text"
               name="items[${index}][url]"
               placeholder="Enter website URL"
               class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm">

        <input type="text"
               name="items[${index}][description]"
               placeholder="Description"
               class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm">


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