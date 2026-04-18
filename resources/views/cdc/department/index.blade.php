@extends('layouts.cdc')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Department Management
    </h1>


    <!-- Create Department -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">

        <h2 class="text-lg font-semibold mb-4 text-gray-700">
            Add Department
        </h2>

        <form method="POST" action="{{ route('cdc.departments.store') }}" class="space-y-4">
            @csrf

            <div class="flex gap-10">
                <div class="basis-2/3">
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Department Name
                    </label>

                    <input type="text" name="name" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="basis-1/3">
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Department Abbreviation
                    </label>

                    <input type="text" name="abbreviation" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <!-- Department Type -->
            <div class="flex gap-10">

                <div class="basis-1/2">
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Department Order no.
                    </label>

                    <input type="number" min="0" name="order_no" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                </div>

                <div class="basis-1/2">
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Department Type
                    </label>

                    <select name="type"
                        class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500">

                        <option value="programme">
                            Programme Department
                        </option>

                        <option value="service">
                            Service Department
                        </option>

                    </select>
                </div>

            </div>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition">
                Create Department
            </button>

        </form>

    </div>



    <!-- Department List -->
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold text-gray-700 mb-4">
            Existing Departments
        </h2>

        <div class="overflow-x-auto">

            <table class="w-full text-left border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Order
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Department Name
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Department Abbreviation
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Department Type
                        </th>

                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse ($departments as $department)
                        <tr class="hover:bg-gray-50 border-gray-200">

                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('cdc.departments.update', $department->id) }}"
                                    class="flex items-center gap-3">

                                    @csrf
                                    @method('PUT')
                                    <input type="number" min="0" name="order_no" value="{{ $department->order_no }}"
                                        class="border border-gray-300 rounded px-2 py-1 w-15">

                            </td>

                            <td class="px-4 py-3">



                                <input type="text" name="name" value="{{ $department->name }}"
                                    class="border border-gray-300 rounded px-2 py-1 w-60">
                            </td>

                            <td class="px-4 py-3">
                                <input type="text" name="abbreviation" value="{{ $department->abbreviation }}"
                                    class="border border-gray-300 rounded px-2 py-1 w-48">
                            </td>

                            <td class="px-4 py-3">
                                {{ ucfirst($department->type) }}
                            </td>

                            <td class="px-4 py-3 flex gap-2">

                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    Update
                                </button>

                                </form>


                                <form method="POST" action="{{ route('cdc.departments.destroy', $department->id) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-gray-500 text-center py-3">
                                No Departments created yet
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
@endsection
