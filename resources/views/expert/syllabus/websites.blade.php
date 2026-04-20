@extends('layouts.syllabus')

@section('content')

    <h3 class="text-lg font-semibold text-gray-800 mb-4">Software / Learning Websites</h3>

<div class="bg-white p-6 rounded-xl shadow">



    <form method="POST" action="{{ route('expert.syllabus.websites.save', $course->id) }}">
    @csrf
    <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

   

    <div class="overflow-x-auto">
        @php
            $oldItems = old('items', $websites->map(fn($w) => ['url' => $w->url, 'description' => $w->description])->toArray());
        @endphp
    <table class="w-full text-sm border-separate border-spacing-y-2">

        <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
            <tr>
                <th class="px-4 py-3 w-14">Sr No</th>
                <th class="px-4 py-3">Website URL</th>
                <th class="px-4 py-3">Description</th>
                <th class="px-4 py-3 w-24">Action</th>
            </tr>
        </thead>

        <tbody id="websiteTable" class="divide-y">

            @forelse ($oldItems as $index => $item)
                <tr class="bg-white shadow-sm hover:shadow-md transition rounded-lg text-center">

                    <td class="px-4 py-3 sr-no">{{ $index + 1 }}</td>

                    <td class="px-4 py-3">
                        <input type="text"
                               name="items[{{ $index }}][url]"
                               value="{{ $item['url'] ?? '' }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </td>

                    <td class="px-4 py-3">
                        <input type="text"
                               name="items[{{ $index }}][description]"
                               value="{{ $item['description'] ?? '' }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </td>

                    <td class="px-4 py-3">
                        <button type="button"
                                onclick="removeRow(this)"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>
                    </td>

                </tr>
            @empty
                {{-- empty row --}}
                <tr class="bg-white shadow-sm hover:shadow-md transition rounded-lg text-center">

                    <td class="px-4 py-3 sr-no">1</td>

                    <td class="px-4 py-3">
                        <input type="text"
                               name="items[0][url]"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </td>

                    <td class="px-4 py-3">
                        <input type="text"
                               name="items[0][description]"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </td>

                    <td class="px-4 py-3">
                        <button type="button"
                                onclick="removeRow(this)"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>
                    </td>

                </tr>
            @endforelse

        </tbody>
    </table>
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

    let table = document.getElementById('websiteTable');

    let row = document.createElement('tr');
    row.classList.add('bg-white', 'shadow-sm', 'hover:shadow-md', 'transition', 'rounded-lg', 'text-center');

    row.innerHTML = `
        <td class="px-4 py-3 sr-no"></td>

        <td class="px-4 py-3">
            <input type="text"
                   name="items[${index}][url]"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </td>

        <td class="px-4 py-3">
            <input type="text"
                   name="items[${index}][description]"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </td>

        <td class="px-4 py-3">
            <button type="button"
                    onclick="removeRow(this)"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                Remove
            </button>
        </td>
    `;

    table.appendChild(row);

    index++;

    updateSerialNumbers();
}

function removeRow(button) {
    button.closest('tr').remove();
    updateSerialNumbers();
}

function updateSerialNumbers() {
    let rows = document.querySelectorAll('#websiteTable tr');

    rows.forEach((row, i) => {
        row.querySelector('.sr-no').innerText = i + 1;
    });
}

</script>

@endsection