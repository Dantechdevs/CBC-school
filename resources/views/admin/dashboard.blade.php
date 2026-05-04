@extends('layouts.admin')
@section('header', 'Admin Dashboard')
@section('content')

@php
    $totalLearners = \App\Models\Learner::count();
    $totalStaff = \App\Models\StaffMember::count();
    $feesCollected = \App\Models\FeePayment::whereHas('invoice', fn($q) => $q->whereTerm(config('school.current_term'))->whereAcademicYear(config('school.current_academic_year')))->sum('amount');
    $feeArrears = \App\Models\FeeInvoice::whereTerm(config('school.current_term'))->whereAcademicYear(config('school.current_academic_year'))->sum('balance');
    $totalClasses = \App\Models\SchoolClass::whereAcademicYear(config('school.current_academic_year'))->count();
    $recentPayments = \App\Models\FeePayment::with('invoice.learner')->latest()->take(5)->get();
    $recentLearners = \App\Models\Learner::latest()->take(5)->get();
@endphp

{{-- Top Stats Row --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

    {{-- Total Learners --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Learners</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalLearners) }}</p>
            <p class="text-xs text-green-600 mt-0.5">{{ $totalClasses }} active classes</p>
        </div>
    </div>

    {{-- Staff Members --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Staff Members</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalStaff) }}</p>
            <p class="text-xs text-blue-600 mt-0.5">Teaching &amp; non-teaching</p>
        </div>
    </div>

    {{-- Fees Collected --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Fees Collected</p>
            <p class="text-2xl font-bold text-gray-900">KES {{ number_format($feesCollected) }}</p>
            <p class="text-xs text-yellow-600 mt-0.5">Term {{ config('school.current_term') }}, {{ config('school.current_academic_year') }}</p>
        </div>
    </div>

    {{-- Fee Arrears --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Fee Arrears</p>
            <p class="text-2xl font-bold text-gray-900">KES {{ number_format($feeArrears) }}</p>
            <p class="text-xs text-red-600 mt-0.5">Outstanding balance</p>
        </div>
    </div>

</div>

{{-- Second Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- CBC Assessment Overview --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">CBC Assessment Levels</h3>
            <span class="text-xs text-gray-400">Current Term</span>
        </div>
        <div class="grid grid-cols-2 gap-3">
            @foreach([
                'EE' => ['Exceeds Expectation', 'green', '0'],
                'ME' => ['Meets Expectation', 'blue', '0'],
                'AE' => ['Approaches Expectation', 'yellow', '0'],
                'BE' => ['Below Expectation', 'red', '0'],
            ] as $code => [$label, $color, $count])
            <div class="p-3 bg-{{ $color }}-50 rounded-lg border border-{{ $color }}-100">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-bold text-{{ $color }}-700 bg-{{ $color }}-200 px-2 py-0.5 rounded">{{ $code }}</span>
                    <span class="text-lg font-bold text-{{ $color }}-800">{{ $count }}</span>
                </div>
                <p class="text-xs text-{{ $color }}-600 leading-tight">{{ $label }}</p>
            </div>
            @endforeach
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100">
            <a href="#" class="text-xs text-green-700 font-medium hover:underline">View full assessment report →</a>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="#" class="flex flex-col items-center gap-2 p-3 rounded-lg bg-green-50 hover:bg-green-100 transition-colors text-center">
                <div class="w-9 h-9 rounded-lg bg-green-200 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <span class="text-xs font-medium text-green-800">Enroll Learner</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-2 p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition-colors text-center">
                <div class="w-9 h-9 rounded-lg bg-blue-200 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-xs font-medium text-blue-800">Enter Assessment</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-2 p-3 rounded-lg bg-yellow-50 hover:bg-yellow-100 transition-colors text-center">
                <div class="w-9 h-9 rounded-lg bg-yellow-200 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-xs font-medium text-yellow-800">Record Payment</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-2 p-3 rounded-lg bg-purple-50 hover:bg-purple-100 transition-colors text-center">
                <div class="w-9 h-9 rounded-lg bg-purple-200 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span class="text-xs font-medium text-purple-800">Generate Report</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-2 p-3 rounded-lg bg-red-50 hover:bg-red-100 transition-colors text-center">
                <div class="w-9 h-9 rounded-lg bg-red-200 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <span class="text-xs font-medium text-red-800">Send SMS</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-2 p-3 rounded-lg bg-teal-50 hover:bg-teal-100 transition-colors text-center">
                <div class="w-9 h-9 rounded-lg bg-teal-200 flex items-center justify-center">
                    <svg class="w-5 h-5 text-teal-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <span class="text-xs font-medium text-teal-800">KEMIS Sync</span>
            </a>
        </div>
    </div>

    {{-- School Info & Term Status --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">School Overview</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                <span class="text-sm text-gray-500">Academic Year</span>
                <span class="text-sm font-semibold text-gray-800">{{ config('school.current_academic_year') }}</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                <span class="text-sm text-gray-500">Current Term</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Term {{ config('school.current_term') }}</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                <span class="text-sm text-gray-500">School Type</span>
                <span class="text-sm font-semibold text-gray-800 capitalize">{{ config('school.type', 'Primary') }}</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                <span class="text-sm text-gray-500">Active Classes</span>
                <span class="text-sm font-semibold text-gray-800">{{ $totalClasses }}</span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-sm text-gray-500">System Status</span>
                <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    Online
                </span>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Last updated: {{ now()->format('d M Y, H:i') }}
            </div>
        </div>
    </div>

</div>

{{-- Third Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    {{-- Recent Fee Payments --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Recent Fee Payments</h3>
            <a href="#" class="text-xs text-green-700 font-medium hover:underline">View all</a>
        </div>
        @if($recentPayments->isEmpty())
        <div class="text-center py-8">
            <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <p class="text-sm text-gray-400">No payments recorded yet</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($recentPayments as $payment)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-xs font-bold text-green-700">
                        {{ strtoupper(substr($payment->invoice->learner->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $payment->invoice->learner->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-400">{{ $payment->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-green-700">KES {{ number_format($payment->amount) }}</p>
                    <p class="text-xs text-gray-400">{{ $payment->payment_method ?? 'Cash' }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Recently Enrolled Learners --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Recently Enrolled Learners</h3>
            <a href="#" class="text-xs text-green-700 font-medium hover:underline">View all</a>
        </div>
        @if($recentLearners->isEmpty())
        <div class="text-center py-8">
            <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <p class="text-sm text-gray-400">No learners enrolled yet</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($recentLearners as $learner)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700">
                        {{ strtoupper(substr($learner->name ?? 'L', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $learner->name }}</p>
                        <p class="text-xs text-gray-400">{{ $learner->admission_number ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $learner->current_grade ?? 'N/A' }}
                    </span>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $learner->created_at->format('d M') }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- Fourth Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- CBC Grade Distribution --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">CBC Grade Levels</h3>
        <div class="space-y-2">
            @foreach([
                'Pre-Primary (PP1–PP2)' => 'green',
                'Lower Primary (Gr 1–3)' => 'blue',
                'Upper Primary (Gr 4–6)' => 'yellow',
                'Junior Secondary (Gr 7–9)' => 'purple',
            ] as $level => $color)
            <div class="flex items-center justify-between py-1.5">
                <span class="text-xs text-gray-600">{{ $level }}</span>
                <span class="text-xs font-semibold text-{{ $color }}-700 bg-{{ $color }}-50 px-2 py-0.5 rounded">0</span>
            </div>
            @endforeach
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100">
            <a href="#" class="text-xs text-green-700 font-medium hover:underline">View class breakdown →</a>
        </div>
    </div>

    {{-- Fee Collection Summary --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Fee Collection Summary</h3>
        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Collected</span>
                    <span>KES {{ number_format($feesCollected) }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    @php $total = $feesCollected + $feeArrears; $pct = $total > 0 ? ($feesCollected / $total) * 100 : 0; @endphp
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Outstanding</span>
                    <span>KES {{ number_format($feeArrears) }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-red-400 h-2 rounded-full" style="width: {{ 100 - $pct }}%"></div>
                </div>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-3">
            <div class="bg-green-50 rounded-lg p-3 text-center">
                <p class="text-lg font-bold text-green-700">{{ number_format($pct, 1) }}%</p>
                <p class="text-xs text-green-600">Collection Rate</p>
            </div>
            <div class="bg-red-50 rounded-lg p-3 text-center">
                <p class="text-lg font-bold text-red-700">{{ number_format(100 - $pct, 1) }}%</p>
                <p class="text-xs text-red-600">Outstanding</p>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <a href="#" class="text-xs text-green-700 font-medium hover:underline">View fee report →</a>
        </div>
    </div>

    {{-- Notifications & Alerts --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Alerts & Notifications</h3>
            <span class="w-5 h-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center font-bold">3</span>
        </div>
        <div class="space-y-3">
            <div class="flex items-start gap-3 p-2.5 bg-yellow-50 rounded-lg border border-yellow-100">
                <svg class="w-4 h-4 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <p class="text-xs font-medium text-yellow-800">Fee Reminders Due</p>
                    <p class="text-xs text-yellow-600">{{ $feeArrears > 0 ? 'Learners with outstanding balances' : 'No arrears' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3 p-2.5 bg-blue-50 rounded-lg border border-blue-100">
                <svg class="w-4 h-4 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-xs font-medium text-blue-800">KEMIS Sync Pending</p>
                    <p class="text-xs text-blue-600">Last sync: Never</p>
                </div>
            </div>
            <div class="flex items-start gap-3 p-2.5 bg-green-50 rounded-lg border border-green-100">
                <svg class="w-4 h-4 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-xs font-medium text-green-800">System Healthy</p>
                    <p class="text-xs text-green-600">All modules operational</p>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100">
            <a href="#" class="text-xs text-green-700 font-medium hover:underline">View all notifications →</a>
        </div>
    </div>

</div>

@endsection