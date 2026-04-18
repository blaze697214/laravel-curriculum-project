@extends('layouts.syllabus')

@section('content')
        <h3 class="text-lg text-gray-800 font-semibold mb-4">
            CO - PO - PSO Mapping 
            @if(count($psos)<1)
                <span class="text-gray-400 text-xs font-normal">
                    No PSOs available
                </span>
            @endif
        </h3>

    <div class="bg-white p-6 rounded-xl shadow">


        <form method="POST" action="{{ route('expert.syllabus.mapping.save', $course->id) }}">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

            <div class="overflow-x-auto">

                <table class="w-full border border-gray-300 text-sm text-center">

                    <tr class="bg-gray-100">
                        <th rowspan="2" class="border px-3 py-2 text-wrap w-15">Course Outcomes</th>

                        <th colspan="{{ count($pos) }}" class="border px-3 py-2">
                            Programme Outcomes (PO)
                        </th>

                        @if (count($psos) > 0)
                            <th colspan="{{ count($psos) }}" class="border px-3 py-2">
                                Programme Specific Outcomes (PSO)
                            </th>
                        @endif
                    </tr>

                    <tr class="bg-gray-100">

                        @foreach ($pos as $po)
                            <th class="border px-2 py-2">{{ $po->po_code }}</th>
                        @endforeach

                        @foreach ($psos as $pso)
                            <th class="border px-2 py-2">{{ $pso->po_code }}</th>
                        @endforeach

                    </tr>

                    {{-- BODY --}}
                    @foreach ($cos as $co)
                        <tr class="hover:bg-gray-50">

                            <td class="border px-3 py-2 font-medium text-center">
                                {{ $co->co_code }}
                            </td>

                            {{-- PO --}}
                            @foreach ($pos as $po)
                                @php
                                    $key = $co->id . '_' . $po->id;
                                    $val = $mappings[$key]->level ?? '';
                                @endphp

                                <td class="border px-2 py-2">
                                    <select name="mapping[{{ $co->id }}][{{ $po->id }}]"
                                        class="border border-gray-300 rounded px-2 py-1 text-sm">

                                        <option value="">-</option>
                                        <option value="1" {{ $val == 1 ? 'selected' : '' }}>L</option>
                                        <option value="2" {{ $val == 2 ? 'selected' : '' }}>M</option>
                                        <option value="3" {{ $val == 3 ? 'selected' : '' }}>H</option>

                                    </select>
                                </td>
                            @endforeach

                            {{-- PSO --}}
                            @foreach ($psos as $pso)
                                @php
                                    $key = $co->id . '_' . $pso->id;
                                    $val = $mappings[$key]->level ?? '';
                                @endphp

                                <td class="border px-2 py-2">
                                    <select name="mapping[{{ $co->id }}][{{ $pso->id }}]"
                                        class="border border-gray-300 rounded px-2 py-1 text-sm">

                                        <option value="">-</option>
                                        <option value="1" {{ $val == 1 ? 'selected' : '' }}>L</option>
                                        <option value="2" {{ $val == 2 ? 'selected' : '' }}>M</option>
                                        <option value="3" {{ $val == 3 ? 'selected' : '' }}>H</option>

                                    </select>
                                </td>
                            @endforeach

                        </tr>
                    @endforeach

                </table>

            </div>

            <div class="mt-4 text-sm text-gray-600">
                <strong>H:</strong> High &nbsp;&nbsp;
                <strong>M:</strong> Medium &nbsp;&nbsp;
                <strong>L:</strong> Low
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded text-sm">
                    Save
                </button>
            </div>

        </form>

    </div>
@endsection
