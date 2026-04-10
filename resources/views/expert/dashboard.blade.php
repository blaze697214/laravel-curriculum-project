@extends('layouts.expert')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Dashboard
    </h1>


    {{-- ================= SUMMARY ================= --}}
    <div class="grid grid-cols-4 gap-6 mb-8">

        <div class="bg-white p-5 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Assigned Courses</p>
            <h2 class="text-2xl font-bold text-blue-600 mt-1">{{ $total }}</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Completed</p>
            <h2 class="text-2xl font-bold text-green-600 mt-1">{{ $completed }}</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Draft</p>
            <h2 class="text-2xl font-bold text-yellow-500 mt-1">{{ $draft }}</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500">Pending</p>
            <h2 class="text-2xl font-bold text-red-600 mt-1">{{ $pending }}</h2>
        </div>

    </div>



    {{-- ================= COURSES TABLE ================= --}}
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold mb-4">My Courses</h2>

        <div class="overflow-x-auto rounded-xl shadow bg-white">
            <table class="w-full text-sm border border-gray-200 text-center">

                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2">Course</th>
                        <th class="px-4 py-2">Abbrev</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Progress</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @foreach ($courses as $item)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-2 text-left">
                                {{ $item['course']->title }}
                            </td>

                            <td class="px-4 py-2">
                                {{ $item['course']->abbreviation }}
                            </td>

                            <td class="px-4 py-2">

                                @if ($item['status'] == 'Completed')
                                    <span class="text-green-600 font-medium">Completed</span>
                                @elseif($item['status'] == 'Draft')
                                    <span class="text-yellow-600 font-medium">Draft</span>
                                @else
                                    <span class="text-gray-500">Not Started</span>
                                @endif

                            </td>

                            <td class="px-4 py-2">
                                {{ $item['progress'] }}%
                            </td>

                            <td class="px-4 py-2">

                                @if ($item['status'] == 'Not Started')

                                    <a href="{{ route('expert.syllabus.preview', $item['course']->id) }}">
                                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                            Create
                                        </button>
                                    </a>
                                @elseif($item['status'] == 'Draft')
                                {{-- {{ route('expert.syllabus.edit', $item['course']->id) }} --}}
                                    <a href="{{ route('expert.syllabus.preview', $item['course']->id) }}">
                                        <button
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                            Continue
                                        </button>
                                    </a>
                                @else
                                {{-- {{ route('expert.syllabus.preview', $item['course']->id) }} --}}
                                    <a href="">
                                        <button class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm">
                                            View
                                        </button>
                                    </a>
                                @endif

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>

    </div>
@endsection
