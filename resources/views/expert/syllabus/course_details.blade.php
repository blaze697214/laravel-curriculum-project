@extends('layouts.syllabus')

@section('content')
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        Course Details
    </h3>


    <div class="bg-white p-6 rounded-xl shadow">

        <form method="POST" action="{{ route('expert.syllabus.details.save', $course->id) }}">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">

            <div id="unitContainer" class="space-y-6">

                @foreach ($units as $uIndex => $unit)
                    <div class="unit-block border border-gray-200 rounded-lg p-5" data-unit-index="{{ $uIndex }}">

                        {{-- ================= UNIT HEADER ================= --}}
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="unit-heading font-semibold text-gray-700"></h4>
                            <button type="button" onclick="removeUnit(this)"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm font-semibold">
                                Remove Unit
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Unit Title</label>
                                <input type="text" name="units[{{ $uIndex }}][title]" value="{{ $unit->title }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Hours</label>
                                <input type="number" name="units[{{ $uIndex }}][hours]" value="{{ $unit->hours }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            </div>

                        </div>


                        {{-- ================= OUTCOMES ================= --}}
                        <h5 class="font-medium text-gray-700 mb-2">Major Learning Outcomes</h5>

                        <div class="outcomes space-y-2 mb-3">

                            @foreach ($unit->topics->where('type', 'learning_outcome') as $outcome)
                                <div class="flex gap-3 items-center">

                                    <span class="outcome-label text-sm text-gray-600 w-5 shrink-0"></span>

                                    <input type="text" name="units[{{ $uIndex }}][outcomes][]"
                                        value="{{ $outcome->content }}"
                                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">

                                    <button type="button" onclick="removeRow(this)"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Remove
                                    </button>

                                </div>
                            @endforeach

                        </div>

                        <button type="button" onclick="addOutcome(this)"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded text-sm mb-4">
                            + Add Outcome
                        </button>


                        {{-- ================= TOPICS ================= --}}
                        <h5 class="font-medium text-gray-700 mb-2">Topics & Subtopics</h5>

                        <div class="topics space-y-4">

                            @foreach ($unit->topics->where('type', 'topic') as $tIndex => $topic)
                                <div class="topic-block border border-gray-100 rounded-lg p-3">

                                    <div class="flex gap-3 items-center mb-3">

                                        <span class="topic-label text-sm text-gray-600 w-4 shrink-0"></span>

                                        <input type="text"
                                            name="units[{{ $uIndex }}][topics][{{ $tIndex }}][title]"
                                            value="{{ $topic->content }}" placeholder="Topic Title"
                                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">

                                        <button type="button" onclick="removeRow(this)"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                            Remove
                                        </button>

                                    </div>

                                    {{-- SUBTOPICS --}}
                                    <div class="subtopics space-y-2 ml-4">
                                        <ul class="list-disc ml-5">

                                            @foreach ($topic->subtopics as $sub)
                                                <li>
                                                    <div class="flex gap-3 items-center mb-2">

                                                        <input type="text"
                                                            name="units[{{ $uIndex }}][topics][{{ $tIndex }}][subtopics][]"
                                                            value="{{ $sub->subtopic }}"
                                                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">

                                                        <button type="button" onclick="removeRow(this)"
                                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                            Remove
                                                        </button>

                                                    </div>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>

                                    <button type="button" onclick="addSubtopic(this)"
                                        class="mt-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded text-sm">
                                        + Add Subtopic
                                    </button>

                                </div>
                            @endforeach

                        </div>

                        <button type="button" onclick="addTopic(this)"
                            class="mt-4 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded text-sm">
                            + Add Topic
                        </button>

                    </div>
                @endforeach

            </div>


            {{-- ADD UNIT --}}
            <div class="mt-6">
                <button type="button" onclick="addUnit()"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm">
                    + Add Unit
                </button>
            </div>


            {{-- SAVE --}}
            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                    Save
                </button>
            </div>

        </form>

    </div>



    {{-- ================= JS ================= --}}
    <script>

        // ─── HELPERS ─────────────────────────────────────────────────────────

        function getUnitIndex(el) {
            return parseInt(el.closest('.unit-block').dataset.unitIndex);
        }

        function getTopicIndex(topicBlock) {
            return [...topicBlock.closest('.topics').children].indexOf(topicBlock);
        }

        // Re-labels everything in a unit block, and also re-numbers input names
        // to keep the array indices contiguous after a unit is removed.
        function reIndexAll() {
            const container = document.getElementById('unitContainer');

            [...container.children].forEach((unitBlock, uIdx) => {

                // Update the data attribute so other functions read correctly
                unitBlock.dataset.unitIndex = uIdx;

                // Update unit heading
                const heading = unitBlock.querySelector('.unit-heading');
                if (heading) heading.textContent = 'Unit ' + (uIdx + 1);

                // Re-name unit inputs
                const unitTitle = unitBlock.querySelector('input[name*="[title]"]');
                const unitHours = unitBlock.querySelector('input[name*="[hours]"]');
                if (unitTitle) unitTitle.name = `units[${uIdx}][title]`;
                if (unitHours) unitHours.name = `units[${uIdx}][hours]`;

                // Outcome labels + input names
                unitBlock.querySelectorAll('.outcomes > div').forEach((row, oIdx) => {
                    const label = row.querySelector('.outcome-label');
                    if (label) label.textContent = (uIdx + 1) + String.fromCharCode(97 + oIdx) + '.';
                    const input = row.querySelector('input');
                    if (input) input.name = `units[${uIdx}][outcomes][]`;
                });

                // Topic labels + input names + subtopic names
                unitBlock.querySelectorAll('.topics > .topic-block').forEach((topicBlock, tIdx) => {
                    const label = topicBlock.querySelector('.topic-label');
                    if (label) label.textContent = (uIdx + 1) + '.' + (tIdx + 1);

                    const topicInput = topicBlock.querySelector('input[type="text"]');
                    if (topicInput) topicInput.name = `units[${uIdx}][topics][${tIdx}][title]`;

                    topicBlock.querySelectorAll('.subtopics input').forEach(sub => {
                        sub.name = `units[${uIdx}][topics][${tIdx}][subtopics][]`;
                    });
                });
            });
        }

        // Run on page load
        reIndexAll();


        // ─── REMOVE UNIT ──────────────────────────────────────────────────────

        function removeUnit(btn) {
            const unitBlock = btn.closest('.unit-block');
            unitBlock.remove();
            reIndexAll(); // re-number all remaining units
        }


        // ─── ADD UNIT ─────────────────────────────────────────────────────────

        function addUnit() {
            const container = document.getElementById('unitContainer');
            const index = container.children.length;

            const div = document.createElement('div');
            div.className = "unit-block border border-gray-200 rounded-lg p-5 mt-4";
            div.dataset.unitIndex = index;

            div.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <h4 class="unit-heading font-semibold text-gray-700">Unit ${index + 1}</h4>
                    <button type="button" onclick="removeUnit(this)"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm font-semibold">
                        Remove Unit
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Unit Title</label>
                        <input type="text" name="units[${index}][title]" placeholder="Unit Title"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Hours</label>
                        <input type="number" name="units[${index}][hours]" placeholder="Hours"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <h5 class="font-medium text-gray-700 mb-2">Major Learning Outcomes</h5>
                <div class="outcomes space-y-2 mb-3"></div>
                <button type="button" onclick="addOutcome(this)"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded text-sm mb-4">
                    + Add Outcome
                </button>

                <h5 class="font-medium text-gray-700 mb-2">Topics & Subtopics</h5>
                <div class="topics space-y-4"></div>
                <button type="button" onclick="addTopic(this)"
                    class="mt-4 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded text-sm">
                    + Add Topic
                </button>
            `;

            container.appendChild(div);
        }


        // ─── ADD OUTCOME ──────────────────────────────────────────────────────

        function addOutcome(btn) {
            const unitBlock   = btn.closest('.unit-block');
            const unitIndex   = getUnitIndex(btn);
            const outcomesDiv = unitBlock.querySelector('.outcomes');

            const div = document.createElement('div');
            div.className = "flex gap-3 items-center";
            div.innerHTML = `
                <span class="outcome-label text-sm text-gray-600 w-5 shrink-0"></span>
                <input type="text" name="units[${unitIndex}][outcomes][]"
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Learning outcome">
                <button type="button" onclick="removeRow(this)"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Remove</button>
            `;
            outcomesDiv.appendChild(div);
            reIndexAll();
        }


        // ─── ADD TOPIC ────────────────────────────────────────────────────────

        function addTopic(btn) {
            const unitBlock  = btn.closest('.unit-block');
            const unitIndex  = getUnitIndex(btn);
            const topicsDiv  = unitBlock.querySelector('.topics');
            const topicIndex = topicsDiv.children.length;

            const div = document.createElement('div');
            div.className = "topic-block border border-gray-100 rounded-lg p-3";
            div.innerHTML = `
                <div class="flex gap-3 items-center mb-3">
                    <span class="topic-label text-sm text-gray-600 w-4 shrink-0"></span>
                    <input type="text"
                        name="units[${unitIndex}][topics][${topicIndex}][title]"
                        placeholder="Topic Title"
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="removeRow(this)"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Remove</button>
                </div>
                <div class="subtopics space-y-2 ml-4">
                    <ul class="list-disc ml-5"></ul>
                </div>
                <button type="button" onclick="addSubtopic(this)"
                    class="mt-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded text-sm">
                    + Add Subtopic
                </button>
            `;
            topicsDiv.appendChild(div);
            reIndexAll();
        }


        // ─── ADD SUBTOPIC ─────────────────────────────────────────────────────

        function addSubtopic(btn) {
            const unitIndex   = getUnitIndex(btn);
            const topicBlock  = btn.closest('.topic-block');
            const topicIndex  = getTopicIndex(topicBlock);
            const subtopicsUl = topicBlock.querySelector('.subtopics ul');

            const li = document.createElement('li');
            li.innerHTML = `
                <div class="flex gap-3 items-center mb-2">
                    <input type="text"
                        name="units[${unitIndex}][topics][${topicIndex}][subtopics][]"
                        placeholder="Subtopic"
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="removeRow(this)"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Remove</button>
                </div>
            `;
            subtopicsUl.appendChild(li);
        }


        // ─── REMOVE ROW ───────────────────────────────────────────────────────

        function removeRow(btn) {
            const topicBlock = btn.closest('.topic-block');
            const outcomeRow = btn.closest('.outcomes > div');

            if (topicBlock) {
                const unitBlock = topicBlock.closest('.unit-block');
                topicBlock.remove();
                reIndexAll();
            } else if (outcomeRow) {
                const unitBlock = outcomeRow.closest('.unit-block');
                outcomeRow.remove();
                reIndexAll();
            } else {
                // subtopic <li>
                btn.closest('li').remove();
            }
        }

    </script>
@endsection