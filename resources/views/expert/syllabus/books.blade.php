@extends('layouts.syllabus')

@section('content')
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Books</h3>


<div class="bg-white p-6 rounded-xl shadow">


    <form method="POST" action="{{ route('expert.syllabus.books.save', $course->id) }}">
        @csrf
        <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

        @php
            $oldBooks = old('books', $books->map(fn($b) => [
                'title'       => $b->title,
                'author'      => $b->author,
                'publication' => $b->publication,
            ])->toArray());
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-separate border-spacing-y-2">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 w-14">Sr No</th>
                        <th class="px-4 py-3">Author</th>
                        <th class="px-4 py-3">Title of Book</th>
                        <th class="px-4 py-3">Publication</th>
                        <th class="px-4 py-3 w-24">Action</th>
                    </tr>
                </thead>

                <tbody id="booksTable" class="divide-y">

                    @forelse ($oldBooks as $index => $book)
                        <tr class="bg-white shadow-sm hover:shadow-md transition rounded-lg text-center">

                            <td class="px-4 py-3 sr-no">{{ $index + 1 }}</td>

                            <td class="px-4 py-3">
                                <input type="text" name="books[{{ $index }}][author]"
                                    value="{{ $book['author'] ?? '' }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                            </td>

                            <td class="px-4 py-3">
                                <input type="text" name="books[{{ $index }}][title]"
                                    value="{{ $book['title'] ?? '' }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                            </td>

                            <td class="px-4 py-3">
                                <input type="text" name="books[{{ $index }}][publication]"
                                    value="{{ $book['publication'] ?? '' }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                            </td>

                            <td class="px-4 py-3">
                                <button type="button" onclick="removeRow(this)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    Remove
                                </button>
                            </td>

                        </tr>
                    @empty
                        {{-- empty table — user will add via button --}}
                        <tr class="bg-white shadow-sm hover:shadow-md transition rounded-lg text-center">

                            <td class="px-4 py-3 sr-no">{{ 0 + 1 }}</td>

                            <td class="px-4 py-3">
                                <input type="text" name="books[0][author]"
                                    value="{{ $book['author'] ?? '' }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                            </td>

                            <td class="px-4 py-3">
                                <input type="text" name="books[0][title]"
                                    value="{{ $book['title'] ?? '' }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                            </td>

                            <td class="px-4 py-3">
                                <input type="text" name="books[0][publication]"
                                    value="{{ $book['publication'] ?? '' }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                            </td>

                            <td class="px-4 py-3">
                                <button type="button" onclick="removeRow(this)"
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
            <button type="button" onclick="addRow()"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                + Add Book
            </button>

            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded text-sm">
                Save
            </button>
        </div>

    </form>

</div>


<script>

    // ─── RE-INDEX ─────────────────────────────────────────────────────────────
    // Rewrites sr no column and all input name attributes after every add/remove

    function reIndex() {
        document.querySelectorAll('#booksTable tr').forEach((row, idx) => {

            // Serial number cell
            const srCell = row.querySelector('.sr-no');
            if (srCell) srCell.textContent = idx + 1;

            // Input names
            const author = row.querySelector('input[name*="[author]"]');
            const title  = row.querySelector('input[name*="[title]"]');
            const pub    = row.querySelector('input[name*="[publication]"]');

            if (author) author.name = `books[${idx}][author]`;
            if (title)  title.name  = `books[${idx}][title]`;
            if (pub)    pub.name    = `books[${idx}][publication]`;
        });
    }

    // Run on page load
    reIndex();


    // ─── ADD ROW ──────────────────────────────────────────────────────────────

    function addRow() {
        const table = document.getElementById('booksTable');
        const index = table.children.length;

        const row = document.createElement('tr');
        row.className = "bg-white shadow-sm hover:shadow-md transition rounded-lg text-center";
        row.innerHTML = `
            <td class="px-4 py-3 sr-no"></td>

            <td class="px-4 py-3">
                <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition" name="books[${index}][author]" placeholder="Author">
            </td>

            <td class="px-4 py-3">
                <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition" name="books[${index}][title]" placeholder="Title">
            </td>

            <td class="px-4 py-3">
                <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition" name="books[${index}][publication]" placeholder="Publication">
            </td>

            <td class="px-4 py-3">
                <button type="button" onclick="removeRow(this)"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                    Remove
                </button>
            </td>
        `;

        table.appendChild(row);
        reIndex(); // assigns correct sr no + names to new row
    }


    // ─── REMOVE ROW ───────────────────────────────────────────────────────────

    function removeRow(btn) {
        btn.closest('tr').remove();
        reIndex();
    }

</script>

@endsection