<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('school.name') }} — Parent Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
<div class="max-w-4xl mx-auto py-6 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-green-900">{{ config('school.name') }}</h1>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-sm text-red-600">Logout</button></form>
    </div>
    <nav class="flex gap-2 mb-6">
        @foreach([['parent.dashboard','Dashboard'],['parent.progress.index','Progress'],['parent.fees.index','Fees'],['parent.notes.index','Notes']] as [$r,$l])
        <a href="{{ route($r) }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-white border hover:bg-green-50 text-gray-700">{{ $l }}</a>
        @endforeach
    </nav>
    {{ $slot }}
</div>
@livewireScripts
</body>
</html>
