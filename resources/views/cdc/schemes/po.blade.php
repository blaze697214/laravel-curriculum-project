@extends('layouts.cdc')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold mb-4">Programme Outcomes (PO)</h2>

        <form method="POST"
            action="{{ $mode === 'create' ? route('cdc.schemes.po.store', $scheme->id) : route('cdc.schemes.po.update', $scheme->id) }}">

            @csrf

            <div id="poContainer" class="space-y-4">

                @php
                    $oldPos = old(
                        'pos',
                        $pos
                            ->map(
                                fn($p) => [
                                    'po_code' => $p->po_code,
                                    'description' => $p->description,
                                ],
                            )
                            ->toArray(),
                    );
                @endphp

                @forelse ($oldPos as $index => $po)
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">

                        <label class="block text-sm font-medium mb-1">PO Code</label>
                        <input type="text" name="pos[{{ $index }}][po_code]" value="{{ $po['po_code'] ?? '' }}"
                            placeholder="PO1" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">

                        <br><br>

                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="pos[{{ $index }}][description]" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                            rows="3">{{ $po['description'] ?? '' }}</textarea>

                        <br><br>

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>
                @empty
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">

                        <label class="block text-sm font-medium mb-1">PO Code</label>
                        <input type="text" name="pos[0][po_code]" value="{{ $po['po_code'] ?? '' }}" placeholder="PO1"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm">

                        <br><br>

                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="pos[0][description]" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" rows="3">{{ $po['description'] ?? '' }}</textarea>

                        <br><br>

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>
                @endforelse

            </div>

            <div class="mt-4 flex gap-3 mb-5">

                <button type="button" onclick="addRow()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                    Add PO
                </button>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded text-sm">
                    {{ $mode === 'create' ? 'Save' : 'Update' }}
                </button>
                
            </div>
        </form>

            <div class="flex justify-between">
            <a href="{{ route('cdc.schemes.edit.award', $scheme->id) }}">
                <button class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg transition-all w-40">
                ← Back
            </button>
                @if ($mode !== 'create')
                    <a href="{{ route('cdc.schemes.edit.next') }}">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 w-40 rounded-lg transition-all">
                            Finish
                        </button>
                    </a>
                @else
                    <a href="{{ route('cdc.schemes.create.next') }}">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 w-40 rounded-lg transition-all">
                            Finish
                        </button>
                    </a>
                @endif
            </div>



    </div>


    <script>
        let index = {{ count($oldPos) > 0 ? count($oldPos) : 1 }};

        function addRow() {

            let container = document.getElementById('poContainer');

            let div = document.createElement('div');

            div.classList.add('border', 'border-gray-300', 'rounded-lg', 'p-4', 'bg-gray-50');

            div.innerHTML = `
        <label class="block text-sm font-medium mb-1">PO Code</label>
        <input type="text"
               name="pos[${index}][po_code]"
               placeholder="PO${index + 1}"
               class="w-full border border-gray-300 rounded px-3 py-2 text-sm">

        <br><br>

        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="pos[${index}][description]"
                  class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                  rows="3"></textarea>

        <br><br>

        <button type="button"
                onclick="removeRow(this)"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
            Remove
        </button>
    `;

            container.appendChild(div);

            index++;
        }

        function removeRow(btn) {
            btn.parentElement.remove();
        }
    </script>
@endsection
