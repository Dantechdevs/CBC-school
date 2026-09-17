@extends('layouts.finance')

@section('header', 'Finance Dashboard')

@section('content')
@php
    $term = config('school.current_term');
    $year = config('school.academic_year');

    $todayCollections = \App\Models\FeePayment::confirmed()->whereDate('paid_at', now())->sum('amount');
    $termCollected = \App\Models\FeePayment::confirmed()
        ->whereHas('invoice', fn($q) => $q->where('term', $term)->where('academic_year', $year))
        ->sum('amount');
    $termArrears = \App\Models\FeeInvoice::where('term', $term)->where('academic_year', $year)
        ->selectRaw('SUM(total_amount - amount_paid) as arrears')->value('arrears') ?? 0;
    $collectionRate = ($termCollected + $termArrears) > 0 ? round(($termCollected / ($termCollected + $termArrears)) * 100, 1) : 0;

    $recentPayments = \App\Models\FeePayment::confirmed()->with('learner')->latest('paid_at')->take(6)->get();
    $lowStockCount = \App\Models\InventoryItem::whereColumn('quantity_in_stock', '<=', 'minimum_stock_level')->count();
    $overdueInvoices = \App\Models\FeeInvoice::where('term', $term)->where('academic_year', $year)
        ->whereColumn('amount_paid', '<', 'total_amount')
        ->where('due_date', '<', now())
        ->count();
@endphp

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Collected Today</p>
        <p class="text-2xl font-bold text-green-700">KES {{ number_format($todayCollections) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Term Collection Rate</p>
        <p class="text-2xl font-bold text-gray-900">{{ $collectionRate }}%</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Term Arrears</p>
        <p class="text-2xl font-bold text-red-700">KES {{ number_format($termArrears) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Overdue Invoices</p>
        <p class="text-2xl font-bold text-orange-600">{{ $overdueInvoices }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Recent Payments</h3>
            <a href="{{ route('finance.payments.index') }}" class="text-xs text-indigo-700 hover:underline">View all →</a>
        </div>
        @if($recentPayments->isEmpty())
        <p class="text-sm text-gray-400 text-center py-6">No payments recorded yet.</p>
        @else
        <div class="space-y-2">
            @foreach($recentPayments as $payment)
            <div class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $payment->learner->full_name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-400">{{ $payment->paid_at->format('d M Y, H:i') }} · {{ $payment->payment_method?->label() }}</p>
                </div>
                <span class="text-sm font-semibold text-green-700">KES {{ number_format($payment->amount) }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Quick Links</h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('finance.payments.index') }}" class="bg-green-50 hover:bg-green-100 rounded-lg p-4 text-center">
                <p class="text-sm font-medium text-green-800">Record Payment</p>
            </a>
            <a href="{{ route('finance.invoices.index') }}" class="bg-blue-50 hover:bg-blue-100 rounded-lg p-4 text-center">
                <p class="text-sm font-medium text-blue-800">View Invoices</p>
            </a>
            <a href="{{ route('finance.inventory.index') }}" class="bg-yellow-50 hover:bg-yellow-100 rounded-lg p-4 text-center relative">
                <p class="text-sm font-medium text-yellow-800">Inventory</p>
                @if($lowStockCount > 0)
                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $lowStockCount }}</span>
                @endif
            </a>
            <a href="{{ route('finance.reports.index') }}" class="bg-purple-50 hover:bg-purple-100 rounded-lg p-4 text-center">
                <p class="text-sm font-medium text-purple-800">Reports</p>
            </a>
        </div>
        @if($lowStockCount > 0)
        <p class="text-xs text-red-600 mt-3">⚠ {{ $lowStockCount }} inventory item(s) are at or below their reorder level.</p>
        @endif
    </div>
</div>
@endsection
