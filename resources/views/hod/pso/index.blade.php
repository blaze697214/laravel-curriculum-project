@extends('layouts.hod')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold mb-4">Programme Specific Outcomes (PSO)</h2>

        <form method="POST" action="{{ route('hod.pso.save', $scheme->id) }}">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

            <div id="psoContainer" class="space-y-4">

                @php
                    $oldPsos = old(
                        'psos',
                        $psos
                            ->map(
                                fn($p) => [
                                    'po_code' => $p->po_code,
                                    'description' => $p->description,
                                ],
                            )
                            ->toArray(),
                    );
                @endphp

                @forelse ($oldPsos as $index => $pso)
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">

                        <label class="block text-sm font-medium mb-1">PSO Code</label>
                        <input type="text" name="psos[{{ $index }}][po_code]" value="{{ $pso['po_code'] ?? '' }}"
                            placeholder="PSO1" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">

                        <br><br>

                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="psos[{{ $index }}][description]" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                            rows="3">{{ $pso['description'] ?? '' }}</textarea>

                        <br><br>

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>
                @empty
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">

                        <label class="block text-sm font-medium mb-1">PSO Code</label>
                        <input type="text" name="psos[0][po_code]" value="{{ $pso['po_code'] ?? '' }}"
                            placeholder="PSO1" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">

                        <br><br>

                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="psos[0][description]" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                            rows="3">{{ $pso['description'] ?? '' }}</textarea>

                        <br><br>

                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Remove
                        </button>

                    </div>
                @endforelse

            </div>

            <div class="mt-4 flex gap-3">

                <button type="button" onclick="addRow()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                    Add PSO
                </button>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded text-sm">
                    Save
                </button>

            </div>

        </form>

    </div>


    <script>
        let index = {{ count($oldPsos) > 0 ? count($oldPsos) : 1 }};

        function addRow() {

            let container = document.getElementById('psoContainer');

            let div = document.createElement('div');

            div.classList.add('border', 'border-gray-300', 'rounded-lg', 'p-4', 'bg-gray-50');

            div.innerHTML = `
        <label class="block text-sm font-medium mb-1">PSO Code</label>
        <input type="text"
               name="psos[${index}][po_code]"
               placeholder="PSO${index + 1}"
               class="w-full border border-gray-300 rounded px-3 py-2 text-sm">

        <br><br>

        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="psos[${index}][description]"
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
