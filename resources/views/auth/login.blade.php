<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-md">

    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
        Login
    </h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Email<br>
                <span class="text-red-500">
                    @error('email')
                        {{ $message }}
                    @enderror
                </span>
            </label>
            <input 
                type="email" 
                name="email" 
                required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Password<br>
                <span class="text-red-500">
                    @error('password')
                        {{ $message }}
                    @enderror
                </span>
            </label>

            <input 
                type="password" 
                name="password" 
                required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
            >
        </div>

        <button 
            type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-all"
        >
            Login
        </button>

    </form>

</div>

</body>
</html>