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

<div class="unit-block border border-gray-200 rounded-lg p-5">

    {{-- ================= UNIT ================= --}}
    <h4 class="font-semibold text-gray-700 mb-3">
        Unit {{ $uIndex + 1 }}
    </h4>

    <div class="grid grid-cols-2 gap-4 mb-4">

        <div>
            <label class="block text-sm text-gray-600 mb-1">Unit Title</label>
            <input type="text"
                name="units[{{ $uIndex }}][title]"
                value="{{ $unit->title }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Hours</label>
            <input type="number"
                name="units[{{ $uIndex }}][hours]"
                value="{{ $unit->hours }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>

    </div>


    {{-- ================= OUTCOMES ================= --}}
    <h5 class="font-medium text-gray-700 mb-2">Major Learning Outcomes</h5>

    <div class="outcomes space-y-2 mb-3">

        @foreach ($unit->topics->where('type','outcome') as $oIndex => $outcome)

        <div class="flex gap-3 items-center">

            <input type="text"
                name="units[{{ $uIndex }}][outcomes][]"
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

        @foreach ($unit->topics->where('type','topic') as $tIndex => $topic)

        <div class="topic-block border border-gray-100 rounded-lg p-3">

            <div class="flex gap-3 items-center mb-2">

                <input type="text"
                    name="units[{{ $uIndex }}][topics][{{ $tIndex }}][title]"
                    value="{{ $topic->content }}"
                    placeholder="Topic Title"
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">

                <button type="button" onclick="removeRow(this)"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                    Remove
                </button>

            </div>

            {{-- SUBTOPICS --}}
            <div class="subtopics space-y-2 ml-4">

                @foreach ($topic->subtopics as $sIndex => $sub)

                <div class="flex gap-3 items-center">

                    <input type="text"
                        name="units[{{ $uIndex }}][topics][{{ $tIndex }}][subtopics][]"
                        value="{{ $sub->subtopic }}"
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">

                    <button type="button" onclick="removeRow(this)"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                        Remove
                    </button>

                </div>

                @endforeach

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
    <button type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
        Save
    </button>
</div>

</form>

</div>



{{-- ================= JS ================= --}}
<script>

function addUnit() {
    let container = document.getElementById('unitContainer');
    let index = container.children.length;

    let div = document.createElement('div');
    div.className = "unit-block border border-gray-200 rounded-lg p-5 mt-4";

    div.innerHTML = `
        <h4 class="font-semibold text-gray-700 mb-3">Unit ${index + 1}</h4>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <input type="text" name="units[${index}][title]" placeholder="Unit Title"
                class="border border-gray-300 rounded-lg px-3 py-2">

            <input type="number" name="units[${index}][hours]" placeholder="Hours"
                class="border border-gray-300 rounded-lg px-3 py-2">
        </div>

        <h5 class="font-medium mb-2">Major Learning Outcomes</h5>
        <div class="outcomes space-y-2"></div>
        <button type="button" onclick="addOutcome(this)"
            class="bg-gray-200 px-3 py-1 rounded text-sm mt-2">+ Add Outcome</button>

        <h5 class="font-medium mt-4 mb-2">Topics</h5>
        <div class="topics space-y-3"></div>
        <button type="button" onclick="addTopic(this)"
            class="bg-gray-200 px-3 py-1 rounded text-sm mt-2">+ Add Topic</button>
    `;

    container.appendChild(div);
}

function addOutcome(btn) {
    let div = document.createElement('div');
    div.className = "flex gap-3 items-center";

    div.innerHTML = `
        <input type="text" name="" class="flex-1 border border-gray-300 rounded-lg px-3 py-2">
        <button type="button" onclick="removeRow(this)"
            class="bg-red-500 text-white px-3 py-1 rounded text-sm">Remove</button>
    `;

    btn.previousElementSibling.appendChild(div);
}

function addTopic(btn) {
    let div = document.createElement('div');
    div.className = "topic-block border border-gray-100 rounded-lg p-3";

    div.innerHTML = `
        <input type="text" placeholder="Topic Title"
            class="border border-gray-300 rounded-lg px-3 py-2 w-full mb-2">

        <button type="button" onclick="removeRow(this)"
            class="bg-red-500 text-white px-3 py-1 rounded text-sm mb-2">Remove</button>

        <div class="subtopics space-y-2"></div>

        <button type="button" onclick="addSubtopic(this)"
            class="bg-gray-200 px-3 py-1 rounded text-sm mt-2">+ Add Subtopic</button>
    `;

    btn.previousElementSibling.appendChild(div);
}

function addSubtopic(btn) {
    let div = document.createElement('div');
    div.className = "flex gap-3 items-center";

    div.innerHTML = `
        <input type="text" placeholder="Subtopic"
            class="flex-1 border border-gray-300 rounded-lg px-3 py-2">
        <button type="button" onclick="removeRow(this)"
            class="bg-red-500 text-white px-3 py-1 rounded text-sm">Remove</button>
    `;

    btn.previousElementSibling.appendChild(div);
}

function removeRow(btn) {
    btn.parentElement.remove();
}

</script>

@endsection