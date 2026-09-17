<div>
    <h2 class="text-xl font-bold text-gray-800 mb-6">Finance Reports</h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
        {{-- Collection trend --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Collection Trend (Last 6 Months)</h3>
            <div class="flex items-end gap-2 h-40">
                @php $max = max(1, $monthlyTrend->max('amount')); @endphp
                @foreach($monthlyTrend as $month)
                <div class="flex-1 flex flex-col items-center justify-end h-full">
                    <div class="w-full bg-green-500 rounded-t" style="height: {{ $month['amount'] > 0 ? max(4, ($month['amount'] / $max) * 100) : 2 }}%"></div>
                    <p class="text-xs text-gray-400 mt-2 text-center">{{ $month['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Payment method breakdown --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Payment Methods This Term</h3>
            @if($methodBreakdown->isEmpty())
            <p class="text-sm text-gray-400 text-center py-8">No payments recorded yet this term.</p>
            @else
            <div class="space-y-3">
                @foreach($methodBreakdown as $row)
                @php
                    $methodValue = $row->payment_method?->value ?? (string) $row->payment_method;
                    $methodLabel = $row->payment_method?->label() ?? ucfirst($methodValue);
                    $pct = $totalCollected > 0 ? round(($row->total / $totalCollected) * 100) : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700">{{ $methodLabel }} ({{ $row->count }})</span>
                        <span class="font-medium text-gray-800">KES {{ number_format($row->total) }}</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Arrears by class --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Arrears by Class (This Term)</h3>
        @if($arrearsByClass->isEmpty())
        <p class="text-sm text-gray-400 text-center py-8">No outstanding arrears this term. 🎉</p>
        @else
        <div class="space-y-2">
            @foreach($arrearsByClass as $row)
            <div class="flex items-center justify-between border-b border-gray-50 py-2 last:border-0">
                <span class="text-sm text-gray-700">{{ $row['class'] }}</span>
                <span class="text-sm font-semibold text-red-700">KES {{ number_format($row['arrears']) }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
