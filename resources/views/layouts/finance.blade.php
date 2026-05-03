<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('school.name') }} — Finance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="flex h-screen overflow-hidden">
    <aside class="w-60 bg-indigo-900 flex flex-col flex-shrink-0">
        <div class="h-16 flex items-center px-5 bg-indigo-950">
            <span class="text-white font-bold text-sm">Finance / Bursar</span>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1">
            @foreach([
                ['finance.dashboard','Dashboard'],['finance.payments.index','Fee Payments'],
                ['finance.invoices.index','Invoices'],['finance.inventory.index','Inventory'],
                ['finance.reports.index','Reports'],
            ] as [$route,$label])
            <a href="{{ route($route) }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-indigo-800 transition-colors">{{ $label }}</a>
            @endforeach
        </nav>
        <div class="px-4 py-3 border-t border-indigo-800">
            <p class="text-indigo-300 text-xs">{{ auth()->user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-xs text-indigo-400 hover:text-white mt-1">Sign out</button></form>
        </div>
    </aside>
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white h-14 flex items-center px-6 shadow-sm">
            <h1 class="text-lg font-semibold text-gray-800">{{ $header ?? 'Finance' }}</h1>
        </header>
        <main class="flex-1 overflow-y-auto p-6">{{ $slot }}</main>
    </div>
</div>
@livewireScripts
</body>
</html>
