@extends('layouts.syllabus')

@section('content')
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        Laboratory Learning Outcomes & Practical Tasks
    </h3>


    <div class="bg-white p-6 rounded-xl shadow">

        <form method="POST" action="{{ route('expert.syllabus.practicals.save', $course->id) }}">
            @csrf
            <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">


            <div id="taskContainer" class="space-y-4">

                @foreach ($tasks as $tIndex => $task)
                    <div class="task-block border border-gray-200 rounded-lg p-4">

                        {{-- ── HEADER ── --}}
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="task-heading font-semibold text-gray-700">Task {{ $tIndex + 1 }}</h4>
                            <button type="button" onclick="removeTask(this)"
                                class="bg-red-100 hover:bg-red-200 text-red-700 text-sm px-3 py-1 rounded">
                                Remove Task
                            </button>
                        </div>

                        {{-- Units --}}
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Units</label>

                            <div class="grid grid-cols-1">
                                @foreach ($units as $unit)
                                    <label class="flex items-center gap-1 text-sm">
                                        <input type="checkbox" name="tasks[{{ $tIndex }}][units][]"
                                            value="{{ $unit->id }}" class="unit-checkbox accent-blue-600"
                                            {{ $task->units->contains($unit->id) ? 'checked' : '' }}>
                                        Unit {{ $unit->unit_no }} - {{ $unit->title }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Outcome --}}
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600 mb-1">
                                Lab Learning Outcome
                            </label>
                            <textarea name="tasks[{{ $tIndex }}][outcome]" required
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" rows="3">{{ $task->lab_learning_outcome }}</textarea>
                        </div>

                        {{-- Exercise --}}
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600 mb-1">
                                Experiment / Task
                            </label>
                            <textarea name="tasks[{{ $tIndex }}][exercise]" required
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" rows="3">{{ $task->exercise }}</textarea>
                        </div>

                        {{-- Hours --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Hours</label>
                            <input type="number" min="0" name="tasks[{{ $tIndex }}][hours]"
                                value="{{ $task->hours }}" required
                                class="w-24 border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        </div>

                    </div>
                @endforeach

            </div>


            {{-- ACTION BUTTONS --}}
            <div class="mt-6 flex gap-3">

                <button type="button" onclick="addTask()"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                    + Add Task
                </button>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                    Save
                </button>

            </div>

        </form>

    </div>

    {{-- 
// ── Units list built by Blade once — reused when adding new tasks ──────
        // value="{{ $unit->id }}" is critical — without it the checkbox submits 'on' --}}
    {{-- ================= JS ================= --}}
    <script>
        const UNITS_HTML = `
            @foreach ($units as $unit)
                <label class="flex items-center gap-1 text-sm">
                    <input type="checkbox"
                        class="unit-checkbox accent-blue-600"
                        value="{{ $unit->id }}">
                    Unit {{ $unit->unit_no }} - {{ $unit->title }}
                </label>
            @endforeach
        `;

        // ─── RE-INDEX ─────────────────────────────────────────────────────────

        function reIndexTasks() {
            document.querySelectorAll('.task-block').forEach((block, idx) => {

                // Heading
                const heading = block.querySelector('.task-heading');
                if (heading) heading.textContent = 'Task ' + (idx + 1);

                // Unit checkboxes — name only, value stays untouched
                block.querySelectorAll('.unit-checkbox').forEach(cb => {
                    cb.name = `tasks[${idx}][units][]`;
                });

                // Outcome textarea
                const outcome = block.querySelector('textarea[name*="[outcome]"]');
                if (outcome) outcome.name = `tasks[${idx}][outcome]`;

                // Exercise textarea
                const exercise = block.querySelector('textarea[name*="[exercise]"]');
                if (exercise) exercise.name = `tasks[${idx}][exercise]`;

                // Hours input
                const hours = block.querySelector('input[name*="[hours]"]');
                if (hours) hours.name = `tasks[${idx}][hours]`;
            });
        }

        // Page load — reIndex so names are correct from the start
        reIndexTasks();


        // ─── REMOVE TASK ──────────────────────────────────────────────────────

        function removeTask(btn) {
            btn.closest('.task-block').remove();
            reIndexTasks();
        }


        // ─── ADD TASK ─────────────────────────────────────────────────────────

        function addTask() {
            const index = document.querySelectorAll('.task-block').length;

            const div = document.createElement('div');
            div.className = "task-block border border-gray-200 rounded-lg p-4";

            div.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <h4 class="task-heading font-semibold text-gray-700">Task ${index + 1}</h4>
                    <button type="button" onclick="removeTask(this)"
                        class="bg-red-100 hover:bg-red-200 text-red-700 text-sm px-3 py-1 rounded">
                        Remove Task
                    </button>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Units</label>
                    <div class="grid grid-cols-1">${UNITS_HTML}</div>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Lab Learning Outcome</label>
                    <textarea name="tasks[${index}][outcome]" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Experiment / Task</label>
                    <textarea name="tasks[${index}][exercise]" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        rows="3"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Hours</label>
                    <input type="number" min="0" name="tasks[${index}][hours]" required
                        class="w-24 border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
            `;

            document.getElementById('taskContainer').appendChild(div);
            reIndexTasks(); // sets correct name on newly added checkboxes too
        }
    </script>
@endsection
