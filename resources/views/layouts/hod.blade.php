<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>HOD Panel</title>

    @vite('resources/css/app.css')

</head>

<body class="flex bg-slate-100 font-sans">

    <!-- Sidebar -->
    <div class="w-55 h-screen bg-slate-900 text-slate-200 flex flex-col justify-between fixed">

        <div>

            <!-- Profile -->
            <div class="p-6 border-b border-slate-700">

                <h3 class="text-lg font-semibold">
                    {{ auth()->user()->name }}
                </h3>

                <p class="text-sm text-slate-400 mt-1">
                    {{ strtoupper(auth()->user()->roles->first()->name) }}
                </p>

                <p class="text-sm font-medium text-slate-500">
                    {{ auth()->user()->department->name ?? '' }}
                </p>

            </div>


            <!-- Navigation -->
            <nav class="mt-4 space-y-1">

                <a href="/hod/dashboard" class="block px-6 py-3 hover:bg-slate-800 transition">
                    Dashboard
                </a>

                @if (auth()->user()->department->type == 'service')
                    <a href="/hod/courses/view" class="block px-6 py-3 hover:bg-slate-800 transition">
                        View Courses
                    </a>
                @else
                    <h4 class="px-6 py-2 text-xs uppercase text-slate-400 mt-4">
                        Scheme Details
                    </h4>
                    <a href="/hod/pso" class="block px-6 py-3 hover:bg-slate-800 transition">
                        Programme Specific Outcome
                    </a>

                    <a href="/hod/courses/create" class="block px-6 py-3 hover:bg-slate-800 transition">
                        Courses
                    </a>
                    <a href="/hod/courses/view" class="block px-6 py-3 hover:bg-slate-800 transition">
                        View Courses
                    </a>

                    <a href="/hod/elective-groups" class="block px-6 py-3 hover:bg-slate-800 transition">
                        Elective Groups
                    </a>

                    <a href="/hod/class-award" class="block px-6 py-3 hover:bg-slate-800 transition">
                        Class Award Courses
                    </a>
                @endif

                <a href="/hod/assign-courses" class="block px-6 py-3 hover:bg-slate-800 transition">
                    Assign Courses
                </a>


                <h4 class="px-6 py-2 text-xs uppercase text-slate-400 mt-4">
                    Users
                </h4>

                {{-- <a href="/hod/users/moderator" class="block px-6 py-3 hover:bg-slate-800 transition">
                    Moderator
                </a> --}}

                <a href="/hod/users/expert" class="block px-6 py-3 hover:bg-slate-800 transition">
                    Expert Users
                </a>

            </nav>

        </div>


        <!-- Logout -->
        <div class="p-6 border-t border-slate-700">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-medium transition">
                    Logout
                </button>

            </form>

        </div>

    </div>



    <!-- Main Content -->

    <div class="ml-55 flex-1 p-8 min-h-screen">

        <div class="bg-white shadow rounded-xl p-6 h-full overflow-auto">

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

            @yield('content')

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
