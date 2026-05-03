<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('school.name') }} — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 bg-green-900 flex flex-col flex-shrink-0">
        <div class="h-16 flex items-center px-5 bg-green-950">
            <span class="text-white font-bold text-base leading-tight">{{ config('school.name') }}</span>
        </div>

        <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-1">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700 {{ request()->routeIs('admin.dashboard') ? 'bg-green-700 font-semibold' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            {{-- Learners --}}
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Learners
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">All Learners</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Enroll Learner</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Transfers</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Alumni</a>
                </div>
            </div>

            {{-- Assessments --}}
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Assessments
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Enter Assessment</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Competency Tracker</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Report Cards</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Bulk Entry</a>
                </div>
            </div>

            {{-- Exams --}}
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Exams
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Exam Schedule</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Question Bank</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Mark Entry</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Results</a>
                </div>
            </div>

            {{-- Curriculum --}}
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Curriculum
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Learning Areas</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Lesson Plans</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Schemes of Work</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Learning Notes</a>
                </div>
            </div>

            {{-- Fees & Payments --}}
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Fees & Payments
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Fee Structures</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Invoices</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Payments</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Arrears</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">M-Pesa</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Bursaries</a>
                </div>
            </div>

            {{-- Staff & HR --}}
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Staff & HR
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">All Staff</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Attendance</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Leave Management</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Payroll</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">CPD Records</a>
                </div>
            </div>

            {{-- Timetable --}}
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Timetable
            </a>

            {{-- Inventory --}}
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Inventory
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Assets Register</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Textbooks</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Issue & Return</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Procurement</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Stationery</a>
                </div>
            </div>

            {{-- Notifications --}}
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Notifications
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Send SMS</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Send Email</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Notice Board</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Push Notifications</a>
                </div>
            </div>

            {{-- Analytics --}}
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Analytics
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Performance Reports</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Attendance Reports</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">Fee Reports</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">BOG Reports</a>
                    <a href="#" class="block px-3 py-2 text-sm text-green-200 hover:text-white hover:bg-green-700 rounded-lg">KEMIS Export</a>
                </div>
            </div>

            {{-- KEMIS Sync --}}
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                KEMIS Sync
            </a>

            {{-- Settings --}}
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-green-100 hover:bg-green-700">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>

        </nav>

        {{-- User info at bottom --}}
        <div class="px-4 py-3 border-t border-green-800">
            <p class="text-green-300 text-xs">{{ auth()->user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-xs text-green-400 hover:text-white mt-1">Sign out</button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white h-14 flex items-center px-6 shadow-sm flex-shrink-0 justify-between">
            <h1 class="text-lg font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
            <div class="flex items-center gap-3 text-sm text-gray-500">
                <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs font-medium">
                    {{ config('school.current_academic_year') }} · Term {{ config('school.current_term') }}
                </span>
            </div>
        </header>
        <main class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>

</div>
@livewireScripts
</body>
</html>