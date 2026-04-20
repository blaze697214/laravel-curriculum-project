<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Moderator Panel</title>

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

                <a href="/moderator/dashboard" class="block px-6 py-3 hover:bg-slate-800 transition">
                    Dashboard
                </a>
                <a href="{{ route('moderator.syllabus.index') }}" class="block px-6 py-3 hover:bg-slate-800 transition">
                    Syllabus Review
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
