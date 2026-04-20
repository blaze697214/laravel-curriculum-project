@extends('layouts.syllabus')

@section('content')

<h1 class="text-lg font-semibold mb-6">
    Syllabus Sections Status
</h1>
<div class="bg-white p-6 rounded-xl shadow">

        <div class="bg-white rounded-xl shadow overflow-hidden">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Section</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach ($checks as $key => $status)
                        @if (is_null($status))
                            @continue
                        @endif

                        <tr class="hover:bg-gray-50 border-gray-200">

                            {{-- SECTION --}}
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $labels[$key] ?? $key }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4 text-center">

                                @if ($status)
                                    <span
                                        class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                        Filled
                                    </span>
                                @else
                                    <span
                                        class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                                        Not Filled
                                    </span>
                                @endif

                            </td>

                            {{-- ACTION --}}
                            <td class="px-6 py-4 text-center">

                                <a href="{{ $routes[$key] }}">

                                    @if ($status)
                                        <button
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded text-sm">
                                            View
                                        </button>
                                    @else
                                        <button
                                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-1.5 rounded text-sm">
                                            Continue
                                        </button>
                                    @endif

                                </a>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
    </div>
@endsection
