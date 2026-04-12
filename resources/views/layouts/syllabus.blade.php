<!DOCTYPE html>
<html>

<head>
    <title>Syllabus</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-100 font-sans">

    <div class="flex min-h-screen">

        {{-- ================= SIDEBAR ================= --}}
        <aside class="w-64 bg-slate-900 text-slate-200 flex flex-col">

            <div class="p-6 border-b border-slate-700">
                <h2 class="text-lg font-semibold">
                    Syllabus
                </h2>
            </div>

            <nav class="flex-1 mt-4 space-y-1 overflow-y-auto">

                <a href="{{ route('expert.syllabus.preview', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Preview
                </a>

                <a href="{{ route('expert.syllabus.rationale', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Rationale
                </a>

                <a href="{{ route('expert.syllabus.industrial', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Industrial Outcome
                </a>

                <a href="{{ route('expert.syllabus.co', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Course Outcome
                </a>

                <a href="{{ route('expert.syllabus.details', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Course Details
                </a>

                <a href="{{ route('expert.syllabus.specification', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Specification Table
                </a>

                <a href="{{ route('expert.syllabus.practicals', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Laboratory Learning
                </a>

                <a href="{{ route('expert.syllabus.self', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Self Learning
                </a>

                <a href="{{ route('expert.syllabus.tutorial', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Tutorial
                </a>

                <a href="{{ route('expert.syllabus.instruction', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Instruction Strategies
                </a>

                <a href="{{ route('expert.syllabus.assessment', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Assessment Methodology
                </a>

                <a href="{{ route('expert.syllabus.books', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Books
                </a>

                <a href="{{ route('expert.syllabus.software', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Software / Websites
                </a>

                <a href="{{ route('expert.syllabus.equipment', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Major Equipment
                </a>

                <a href="{{ route('expert.syllabus.mapping', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    CO-PO-PSO Mapping
                </a>

                <a href="{{ route('expert.syllabus.qp', $course->id) }}"
                    class="block px-6 py-3 hover:bg-slate-800 transition">
                    Question Paper Profile
                </a>

            </nav>

        </aside>



        {{-- ================= CONTENT ================= --}}
        <div class="flex-1 p-8">

            <div class="bg-white rounded-xl shadow p-6 min-h-full">

                {{-- Alerts --}}
            @if (session('success'))
                <div id="msg" class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div id="msg" class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

                {{-- COURSE HEADER --}}
                <div class="mb-6 border-b border-gray-200 pb-4">

                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $course->title }}
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $course->abbreviation }}
                    </p>

                </div>

                {{-- SECTION CONTENT --}}
                @yield('content')

            </div>

        </div>

    </div>

</body>

<script>
    setTimeout(function() {

        const msg = document.getElementById('msg');

        if (msg) {
            msg.style.display = 'none';
        }

    }, 2000);
</script>

</html>
