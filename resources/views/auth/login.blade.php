<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-xl w-full">
        <h2 class="sr-only">
            Login page for Government Polytechnic Nashik Curriculum Management System
        </h2>

        <div class="min-h-screen flex bg-gray-100 relative">

            <!-- LEFT PANEL -->
            <div class="w-1/2 bg-[#0F4C81] flex flex-col justify-center p-16 relative overflow-hidden">

                <!-- decorative circles -->
                <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/5"></div>
                <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full bg-white/5"></div>

                <!-- badge -->
                <div class="flex items-center gap-5 mb-12 relative z-10">

                    <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shrink-0">
                        <span class="text-lg font-semibold text-[#0F4C81] tracking-tight">GP</span>
                    </div>

                    <div class="text-white">
                        <div class="text-sm font-medium opacity-80 tracking-widest uppercase mb-1">
                            Govt. Polytechnic
                        </div>
                        <div class="text-base font-medium leading-snug">
                            Nashik, Maharashtra
                        </div>
                    </div>

                </div>

                <!-- divider -->
                <div class="w-14 h-0.5 bg-white/30 mb-10 relative z-10"></div>

                <!-- hero -->
                <div class="relative z-10">
                    <h1 class="text-3xl font-semibold text-white leading-tight mb-3">
                        Curriculum<br>
                        <span class="text-[#7EC8F5]">Management</span><br>
                        System
                    </h1>

                    <p class="text-base text-white/70 leading-relaxed max-w-md mt-4">
                        A unified platform to manage schemes, syllabi, course assignments, and academic workflows across
                        all departments.
                    </p>
                </div>

                <!-- tags -->
                <div class="mt-14 flex gap-3 flex-wrap relative z-10">
                    <span class="text-sm px-4 py-1.5 rounded-full bg-white/10 text-white/80 border border-white/20">
                        Scheme Design
                    </span>
                    <span class="text-sm px-4 py-1.5 rounded-full bg-white/10 text-white/80 border border-white/20">
                        Syllabus Builder
                    </span>
                    <span class="text-sm px-4 py-1.5 rounded-full bg-white/10 text-white/80 border border-white/20">
                        Course Assignments
                    </span>
                    <span class="text-sm px-4 py-1.5 rounded-full bg-white/10 text-white/80 border border-white/20">
                        HOD Approvals
                    </span>
                </div>

            </div>

            <!-- RIGHT PANEL -->
            <div class="w-1/2 flex items-center justify-center p-8 bg-white">

                <div class="w-full max-w-sm">

                    <!-- header -->
                    <div class="mb-8">
                        <div class="text-xs font-medium text-[#0F4C81] tracking-widest uppercase mb-2">
                            Secure Access
                        </div>

                        <h2 class="text-xl font-medium text-gray-800 mb-1">
                            Sign in to continue
                        </h2>

                        <p class="text-sm text-gray-500">
                            Use your institutional credentials to access the portal.
                        </p>
                    </div>

                    <!-- FORM (Laravel integrated) -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- EMAIL -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-500 mb-1">
                                Email address
                            </label>

                            <div class="relative">
                                <div
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 flex items-center justify-center">
                                    <svg class="stroke-gray-500 opacity-50" width="15" height="15" fill="none"
                                        stroke-width="1.8">
                                        <rect x="2" y="4" width="20" height="16" rx="2" />
                                        <path d="M2 7l10 7 10-7" />
                                    </svg>
                                </div>

                                <input type="email" name="email" value="{{ old('email') }}" required
                                    placeholder="you@gpnashik.ac.in"
                                    class="w-full h-10 pl-10 pr-3 text-sm bg-gray-100 border border-gray-300 rounded-md outline-none focus:border-[#0F4C81] focus:ring-4 focus:ring-[#0F4C81]/10" />
                            </div>

                            @error('email')
                                <div class="text-xs text-red-500 mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-500 mb-1">
                                Password
                            </label>

                            <div class="relative">
                                <div
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 flex items-center justify-center">
                                    <svg class="stroke-gray-500 opacity-50" width="15" height="15" fill="none"
                                        stroke-width="1.8">
                                        <rect x="3" y="11" width="18" height="11" rx="2" />
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                    </svg>
                                </div>

                                <input type="password" name="password" required placeholder="Enter your password"
                                    class="w-full h-10 pl-10 pr-3 text-sm bg-gray-100 border border-gray-300 rounded-md outline-none focus:border-[#0F4C81] focus:ring-4 focus:ring-[#0F4C81]/10" />
                            </div>

                            @error('password')
                                <div class="text-xs text-red-500 mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- BUTTON -->
                        <button type="submit"
                            class="w-full h-10 bg-[#0F4C81] text-white rounded-md text-sm font-medium flex items-center justify-center gap-0.5 mt-6 hover:bg-[#0a3a65] active:scale-95 transition">
                            <svg class="w-6 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                            </svg>


                            Sign in
                        </button>

                    </form>

                    <!-- roles -->
                    <div class="mt-7 border-t border-gray-200 pt-5">
                        <div class="text-xs text-gray-500 mb-2">
                            Portal roles
                        </div>

                        <div class="flex gap-1.5 flex-wrap">
                            <span
                                class="text-xs px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">CDC</span>
                            <span
                                class="text-xs px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">HOD</span>
                            <span
                                class="text-xs px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">Expert</span>
                            <span
                                class="text-xs px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">Moderator</span>
                        </div>
                    </div>

                    <!-- footer -->
                    <div class="mt-6 text-center text-xs text-gray-500">
                        For access issues, contact
                        <strong class="text-gray-800 font-medium">
                            cdc@gpnashik.ac.in
                        </strong>
                    </div>
                </div>
            </div>

            <!-- About Button -->
            <div id="openDialog"
                class="absolute bottom-5 right-5 rounded-2xl bg-[#cce6fc] border-2 border-[#1066b0] py-1 px-3 flex items-center gap-1 cursor-pointer z-10">
                <svg class="w-5 h-5 text-[#4388c4]" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                <span class="text-[#4388c4] text-sm font-medium">About</span>
            </div>

            <!-- Dialog Overlay -->
            <div id="dialogOverlay" class="hidden absolute inset-0 bg-black/40 z-50 flex items-center justify-center">

                <div class="bg-white rounded-4xl w-full max-w-md mx-4 overflow-hidden shadow-xl">

                    <!-- Dialog Header -->
                    <div class="bg-[#0F4C81] px-6 py-5 relative">
                        <button id="closeDialog"
                            class="absolute top-4 right-4 bg-white/15 hover:bg-white/25 text-white rounded-2xl w-8 h-8 flex items-center justify-center transition cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <h2 class="text-white text-lg font-medium">Curriculum Management System</h2>
                        <p class="text-white/70 text-sm mt-0.5">Government Polytechnic Nashik &nbsp;·&nbsp; v1.0.0</p>
                    </div>

                    <!-- Dialog Body -->
                    <div class="px-6 py-5">

                        <!-- App Description -->
                        <p class="text-sm text-gray-600 leading-relaxed border-l-4 border-[#0F4C81] pl-3 mb-5">
                            A centralised digital platform for managing academic schemes, course syllabi, expert
                            assignments,
                            moderator reviews, and multi-level HOD approvals across all departments of Government
                            Polytechnic Nashik.
                        </p>

                        <!-- Features Grid -->
                        <div class="grid grid-cols-2 gap-2 mb-5">
                            <div class="flex items-center gap-2 bg-blue-50 rounded-lg px-3 py-2 text-sm text-blue-800">
                                <svg class="w-4 h-4 text-blue-500 shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                </svg>
                                Scheme design
                            </div>
                            <div class="flex items-center gap-2 bg-blue-50 rounded-lg px-3 py-2 text-sm text-blue-800">
                                <svg class="w-4 h-4 text-blue-500 shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                </svg>
                                Syllabus builder
                            </div>
                            <div class="flex items-center gap-2 bg-blue-50 rounded-lg px-3 py-2 text-sm text-blue-800">
                                <svg class="w-4 h-4 text-blue-500 shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                                Course assignments
                            </div>
                            <div class="flex items-center gap-2 bg-blue-50 rounded-lg px-3 py-2 text-sm text-blue-800">
                                <svg class="w-4 h-4 text-blue-500 shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                HOD approvals
                            </div>
                            {{-- <div class="flex items-center gap-2 bg-blue-50 rounded-lg px-3 py-2 text-sm text-blue-800">
                                <svg class="w-4 h-4 text-blue-500 shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                                </svg>
                                CO-PO-PSO mapping
                            </div>
                            <div class="flex items-center gap-2 bg-blue-50 rounded-lg px-3 py-2 text-sm text-blue-800">
                                <svg class="w-4 h-4 text-blue-500 shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                Question profiles
                            </div> --}}
                        </div>

                        <!-- Developers -->
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-3">Development team
                        </p>

                        <div class="flex flex-col gap-2">

                            <div
                                class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5">
                                <div
                                    class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-medium shrink-0">
                                    OB</div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">Om J. Borle</p>
                                    <p class="text-xs text-gray-500">Database Architect &amp; Full-stack Developer</p>
                                </div>
                                <span
                                    class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-md font-medium">Lead</span>
                            </div>

                            

                        </div>

                        <p class="text-center text-xs text-gray-400 mt-4">Built with Laravel · Tailwind CSS · Blade
                            &nbsp;·&nbsp; © 2026 GP Nashik</p>

                    </div>

                </div>

            </div>
        </div>

        </dialog>
    </div>
    </div>
</body>

</html>

<script>
    const overlay = document.getElementById('dialogOverlay');
    const openBtn = document.getElementById('openDialog');
    const closeBtn = document.getElementById('closeDialog');

    openBtn.addEventListener('click', () => overlay.classList.remove('hidden'));
    closeBtn.addEventListener('click', () => overlay.classList.add('hidden'));

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.classList.add('hidden');
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') overlay.classList.add('hidden');
    });

    setTimeout(function() {
        const msg = document.getElementById('msg');
        if (msg) msg.style.display = 'none';
    }, 2000);
</script>
