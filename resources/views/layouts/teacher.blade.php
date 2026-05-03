<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('school.name') }} — Teacher Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="flex h-screen overflow-hidden">
    <aside class="w-60 bg-blue-900 flex flex-col flex-shrink-0">
        <div class="h-16 flex items-center px-5 bg-blue-950">
            <span class="text-white font-bold text-sm">Teacher Portal</span>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1">
            @foreach([
                ['teacher.dashboard','Dashboard'],['teacher.assessment.index','Assessment Entry'],
                ['teacher.notes.index','Learning Notes'],['teacher.attendance.index','Attendance'],
                ['teacher.timetable.index','Timetable'],
            ] as [$route,$label])
            <a href="{{ route($route) }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-blue-100 hover:bg-blue-800 transition-colors">{{ $label }}</a>
            @endforeach
        </nav>
        <div class="px-4 py-3 border-t border-blue-800">
            <p class="text-blue-300 text-xs">{{ auth()->user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-xs text-blue-400 hover:text-white mt-1">Sign out</button></form>
        </div>
    </aside>
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white h-14 flex items-center px-6 shadow-sm">
            <h1 class="text-lg font-semibold text-gray-800">{{ $header ?? 'Teacher Portal' }}</h1>
        </header>
        <main class="flex-1 overflow-y-auto p-6">{{ $slot }}</main>
    </div>
</div>
@livewireScripts
</body>
</html>
