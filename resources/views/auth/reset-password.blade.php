<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — {{ config('school.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-900 to-green-700 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-gray-800 text-xl font-semibold mb-1">Set a New Password</h2>
            <p class="text-gray-500 text-sm mb-6">Choose a new password for your account.</p>

            @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Email Address</label>
                    <input name="email" type="email" value="{{ old('email', $email) }}" required autofocus
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">New Password</label>
                    <input name="password" type="password" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Confirm New Password</label>
                    <input name="password_confirmation" type="password" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                </div>
                <button type="submit" class="w-full bg-green-700 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-green-800">
                    Reset Password
                </button>
            </form>
            <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="text-sm text-green-700 hover:underline">← Back to login</a>
            </div>
        </div>
    </div>
</body>
</html>
