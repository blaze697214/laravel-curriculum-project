@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Moderator Management
    </h1>

    <!-- Create User -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">

        <h2 class="text-lg font-semibold text-gray-700 mb-4">
            Create User
        </h2>

        <form method="POST" action="{{ route('hod.users.moderator.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Name
                </label>

                <input type="text" name="name" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Email
                </label>

                <input type="email" name="email" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Password
                </label>

                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            @foreach ($roles as $role)
                @if ($role->name == 'moderator')
                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                @endif
            @endforeach


            {{-- <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Role
                </label>

                <select name="role_id" id="roleSelect"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">

                    <option value="">Select Role</option>

                    @foreach ($roles as $role)
                        @if ($role->name == 'moderator')
                            <option value="{{ $role->id }}">
                                {{ strtoupper($role->name) }}
                            </option>
                        @endif
                    @endforeach

                </select>

            </div> --}}

            @php
                $department = auth()->user()->department;
            @endphp

            <input type="hidden" name="department_id" value="{{ $department->id }}">

            {{-- <div id="departmentField">

                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Department
                </label>

                <select name="department_id"
                    class="w-full md:w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">

                    <option value="">None</option>

                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">
                            {{ $department->name }}
                        </option>
                    @endforeach

                </select>

            </div> --}}


            <div class="md:col-span-2">

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition">
                    Create User
                </button>

            </div>

        </form>

    </div>



    <!-- User List -->
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold text-gray-700 mb-4">
            Moderators
        </h2>

        <div class="overflow-x-auto rounded-xl shadow">

            <table class="w-full text-left border border-gray-200 rounded-xl overflow-hidden">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Role</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Department</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Actions</th>
                    </tr>

                </thead>


                <tbody class="divide-y">

                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 border-gray-200">
                            <form method="POST" action="{{ route('hod.users.moderator.update', $user->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

                                <td class="px-4 py-3">
                                    <input type="text" name="name" value="{{ $user->name }}"
                                        class="border border-gray-300 rounded px-2 py-1 w-full">
                                </td>


                                <td class="px-4 py-3">
                                    <input type="text" name="email" value="{{ $user->email }}"
                                        class="border border-gray-300 rounded px-2 py-1 w-full">
                                </td>


                                <td class="px-4 py-3 font-medium text-gray-700">
                                    {{ strtoupper($user->roles->first()->name) ?? '-' }}
                                </td>


                                <td class="px-4 py-3 text-gray-600">
                                    {{ $user->department->name ?? '-' }}
                                </td>


                                <td class="px-4 py-3 flex gap-2">
                                    <button type="submit"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                        Update
                                    </button>
                            </form>

                            <form method="POST" action="{{ route('hod.users.moderator.destroy', $user->id) }}">

                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    Delete
                                </button>
                            </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
    <script>
        const roleSelect = document.getElementById('roleSelect');
        const departmentField = document.getElementById('departmentField');

        // roleSelect.addEventListener('change', function() {

        //     const selectedRole = roleSelect.options[roleSelect.selectedIndex].text.toLowerCase();

        //     if (selectedRole === 'hod') {
        //         departmentField.style.display = 'block';
        //     } else {
        //         departmentField.style.display = 'none';
        //     }

        // })
    </script>
@endsection
