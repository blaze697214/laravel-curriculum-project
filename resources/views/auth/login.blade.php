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

<div class="min-h-screen flex bg-gray-100">

  <!-- LEFT PANEL -->
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
      A unified platform to manage schemes, syllabi, course assignments, and academic workflows across all departments.
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
            <div class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 flex items-center justify-center">
              <svg class="stroke-gray-500 opacity-50" width="15" height="15" fill="none" stroke-width="1.8">
                <rect x="2" y="4" width="20" height="16" rx="2"/>
                <path d="M2 7l10 7 10-7"/>
              </svg>
            </div>

            <input 
              type="email"
              name="email"
              value="{{ old('email') }}"
              required
              placeholder="you@gpnashik.ac.in"
              class="w-full h-10 pl-10 pr-3 text-sm bg-gray-100 border border-gray-300 rounded-md outline-none focus:border-[#0F4C81] focus:ring-4 focus:ring-[#0F4C81]/10"
            />
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
            <div class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 flex items-center justify-center">
              <svg class="stroke-gray-500 opacity-50" width="15" height="15" fill="none" stroke-width="1.8">
                <rect x="3" y="11" width="18" height="11" rx="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
            </div>

            <input 
              type="password"
              name="password"
              required
              placeholder="Enter your password"
              class="w-full h-10 pl-10 pr-3 text-sm bg-gray-100 border border-gray-300 rounded-md outline-none focus:border-[#0F4C81] focus:ring-4 focus:ring-[#0F4C81]/10"
            />
          </div>

          @error('password')
            <div class="text-xs text-red-500 mt-1">
              {{ $message }}
            </div>
          @enderror
        </div>

        <!-- BUTTON -->
        <button type="submit"
          class="w-full h-10 bg-[#0F4C81] text-white rounded-md text-sm font-medium flex items-center justify-center gap-2 mt-6 hover:bg-[#0a3a65] active:scale-95 transition"
        >
          <svg width="15" height="15" fill="none" stroke="white" stroke-width="2">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/>
            <line x1="15" y1="12" x2="3" y2="12"/>
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
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">CDC</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">HOD</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">Expert</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">Moderator</span>
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
</div>
</div>
</body>
</html>