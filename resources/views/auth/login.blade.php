<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ config('school.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-900 to-green-700 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        {{-- Logo / School Name --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg mb-4">
                <svg class="w-9 h-9 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422A12.083 12.083 0 0121 13c0 2.386-.37 4.687-1.058 6.85A12.006 12.006 0 0112 21a12.006 12.006 0 01-7.942-1.15A12.083 12.083 0 013 13c0-.836.068-1.655.2-2.455L12 14z"/>
                </svg>
            </div>
            <h1 class="text-white text-2xl font-bold">{{ config('school.name') }}</h1>
            <p class="text-green-200 text-sm mt-1">{{ config('school.motto') }}</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-gray-800 text-xl font-semibold mb-1">Welcome back</h2>
            <p class="text-gray-500 text-sm mb-6">Sign in to your account</p>

            @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
            @endif

            @if (session('status'))
            <div class="mb-4 bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-600 mb-1">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition">
                </div>
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-600 mb-1">Password</label>
                    <input id="password" name="password" type="password" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-600"> Remember me
                    </label>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-green-700 hover:text-green-800">Forgot password?</a>
                    @endif
                </div>
                <button type="submit"
                        class="w-full bg-green-700 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-green-800 active:bg-green-900 transition-colors mt-2">
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-green-300 text-xs mt-6">
            CBC School Management System &copy; {{ date('Y') }}
        </p>
    </div>
</body>
</html>
