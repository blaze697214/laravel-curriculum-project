@extends('layouts.expert')

@section('content')

<h1 class="text-2xl font-bold mb-6">
    My Syllabus
</h1>

<div class="bg-white rounded-xl shadow overflow-hidden">
    
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Course</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-700">Progress</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Remarks</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-700 w-50">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach ($data as $item)

                @php
                $course = $item['course'];
                $syllabus = $item['syllabus'];
                $progress = $item['progress'];
                $remarks = $item['remarks'];
                $status = $syllabus->status;
                @endphp

                <tr class="hover:bg-gray-50 border-gray-200">

                    {{-- COURSE --}}
                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $course->title }}
                    </td>
                    
                    {{-- PROGRESS --}}
                    <td class="px-6 py-4 text-center">
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                            <div class="bg-blue-600 h-2 rounded-full"
                                 style="width: {{ $progress }}%"></div>
                        </div>
                        <span class="text-xs text-gray-600">{{ $progress }}%</span>
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4 text-center">
                        @php
                            $statusClass = match($status) {
                                'approved' => 'text-green-600',
                                'rejected' => 'text-red-500',
                                'submitted' => 'text-yellow-600',
                                default => 'text-gray-600'
                            };
                        @endphp

                        <span class="font-semibold {{ $statusClass }}">
                            {{ ucfirst(str_replace('_',' ', $status)) }}
                        </span>
                    </td>

                    {{-- REMARKS --}}
                    <td class="px-6 py-4 text-sm text-gray-700">

                        @if($remarks->isEmpty())
                            <span class="text-gray-400">No remarks</span>
                        @else

                            <div class="space-y-2 max-h-32 overflow-y-auto">

                                @foreach($remarks as $r)
                                    <div class="bg-gray-50 p-2 rounded border text-xs">
                                        <strong>{{ $r->givenBy->name ?? 'HOD' }}</strong><br>
                                        {{ $r->remark }}<br>
                                        <span class="text-gray-400">{{ $r->created_at }}</span>
                                    </div>
                                    @endforeach

                            </div>

                        @endif

                    </td>

                    {{-- ACTION --}}
                    <td class="px-6 py-4 text-center flex justify-center gap-5 space-y-2">

                        {{-- VIEW --}}
                        <a href="{{ route('expert.syllabus.preview', $course->id) }}">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded text-sm">
                                View
                            </button>
                        </a>
                        
                        {{-- SUBMIT --}}
                        @if(in_array($status, ['draft','moderator_rejected']))
                            <form method="POST"
                                  action="{{ route('expert.syllabus.submit', $course->id) }}"
                                  onsubmit="return confirm('Are you sure you want to submit?');">
                                @csrf
                                <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

                                <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded text-sm">
                                    Submit
                                </button>
                            </form>
                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection