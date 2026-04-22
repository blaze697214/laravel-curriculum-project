@extends('layouts.moderator')

@section('content')
    <h1 class="text-2xl font-bold mb-6">
        Moderator Syllabus Review
    </h1>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Course</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Expert</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-700">Progress</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse ($data as $item)
                        @php
                            $course = $item['course'];
                            $expert = $item['expert'];
                            $syllabus = $item['syllabus'];
                            $status = $syllabus->status;
                            $progress = $item['progress'];
                        @endphp

                        <tr class="hover:bg-gray-50 border-gray-200">

                            {{-- COURSE --}}
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $course->title }}
                            </td>

                            {{-- EXPERT --}}
                            <td class="px-6 py-4 text-gray-700">
                                {{ $expert->name }}
                            </td>

                            {{-- PROGRESS --}}
                            <td class="px-6 py-4 text-center">
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600">{{ $progress }}%</span>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusClass = match ($status) {
                                        'approved' => 'text-green-600',
                                        'rejected' => 'text-red-500',
                                        'submitted' => 'text-yellow-600',
                                        default => 'text-gray-600',
                                    };
                                @endphp

                                <span class="font-semibold {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </td>

                            {{-- ACTION --}}
                            <td class="px-6 py-4 text-center space-y-2 flex ">

                                {{-- VIEW --}}
                                <div class="flex flex-col justify-center gap-5 basis-1/2">
                                    <a href="{{ route('moderator.syllabus.preview', $course->id) }}">
                                        <button
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded text-sm">
                                            View
                                        </button>
                                    </a>

                                    {{-- APPROVE / REJECT --}}
                                    @if ($status === 'submitted')
                                        <form method="POST"
                                            action="{{ route('moderator.syllabus.approve', $syllabus->id) }}">
                                            @csrf
                                            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded text-sm">
                                                Approve
                                            </button>
                                        </form>
                                </div>

                                <div class="basis-1/2 flex flex-col">
                                    <form method="POST" action="{{ route('moderator.syllabus.reject', $syllabus->id) }}"
                                        class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

                                        <textarea name="remark" rows="5" placeholder="Enter remarks"
                                            class="w-full border border-gray-300 rounded px-2 py-1 text-xs"></textarea>

                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded text-sm">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                    @endif

                    </td>

                    </tr>
                @empty
                    <tr class="hover:bg-gray-50 border-gray-200">
                        <td colspan="5" class="px-6 py-4 text-center space-y-2 text-gray-400 font-semibold">
                            No Syllabus are there to review
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
@endsection
