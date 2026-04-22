@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Course Categories - {{ $scheme->name }}
    </h1>





    <div class="flex gap-7">

        {{-- ================= LEFT: FORM ================= --}}
        <div class="bg-white p-6 rounded-xl shadow basis-1/3">

            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Add Category
            </h2>

            <form method="POST" action="{{ route('cdc.schemes.categories.store', $scheme->id) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Name</label>
                    <input type="text" name="name"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex gap-3">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Abbreviation</label>
                        <input type="text" name="abbreviation"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Order no.</label>
                        <input type="number" min="0" name="order_no"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="inline-flex items-center gap-2 text-gray-700">
                        <input type="checkbox" name="is_elective" {{ old('is_elective') ? 'checked' : '' }}>
                        Is Elective
                    </label>
                </div>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition">
                    Add Category
                </button>

            </form>

        </div>



        {{-- ================= RIGHT: TABLE ================= --}}
        <div class="bg-white p-6 rounded-xl shadow basis-2/3">

            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Categories
            </h2>

            <div class="overflow-x-auto rounded-xl shadow">

                <table class="w-full text-left border border-gray-200 rounded-xl overflow-hidden">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-22">Order</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-80">Name</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-22">Abbreviation</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600 ">Elective</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50 border-gray-200">

                                <td class="px-4 py-3">
                                    <form method="POST"
                                        action="{{ route('cdc.schemes.categories.update', $category->id) }}"
                                        class="flex gap-2">
                                        @csrf
                                        @method('PATCH')

                                        <input type="number" min="0" name="order_no"
                                            value="{{ $category->order_no }}"
                                            class="border border-gray-300 rounded px-2 py-1 w-full">
                                </td>

                                {{-- INLINE EDIT --}}
                                <td class="px-4 py-3">

                                    <input type="text" name="name" value="{{ $category->name }}"
                                        class="border border-gray-300 rounded px-2 py-1 w-full">

                                </td>

                                <td class="px-4 py-3">
                                    <input type="text" name="abbreviation" value="{{ $category->abbreviation }}"
                                        class="border border-gray-300 rounded px-2 py-1 w-full">
                                </td>

                                <td class="px-4 py-3">
                                    <input type="checkbox" name="is_elective"
                                        @if ($category->is_elective) checked @endif>
                                </td>

                                <td class="px-4 py-3 flex gap-2">

                                    <button type="submit"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                        Update
                                    </button>

                                    </form>

                                    <form method="POST"
                                        action="{{ route('cdc.schemes.categories.destroy', $category->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                            Delete
                                        </button>
                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">
                                    No categories added
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>



    {{-- ================= NEXT BUTTON ================= --}}
    <div class="mt-8 text-right">
        <form method="POST" action="{{ route('cdc.schemes.categories.next', $scheme->id) }}">
            @csrf

            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white w-40 px-6 py-2 rounded-lg font-medium transition">
                Next →
            </button>
        </form>
    </div>
@endsection
