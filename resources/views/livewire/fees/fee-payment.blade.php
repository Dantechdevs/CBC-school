<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Fee Payments</h2>
        <button wire:click="$set('showMpesaModal', true)" class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800">
            + Record Payment
        </button>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-xl shadow-sm p-4 mb-5 flex gap-4">
        <input wire:model.live.debounce.300ms="search" type="text"
               placeholder="Search learner by name or admission number..."
               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <select wire:model.live="termFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Terms</option>
            <option value="1">Term 1</option>
            <option value="2">Term 2</option>
            <option value="3">Term 3</option>
        </select>
        <select wire:model.live="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="unpaid">Unpaid</option>
            <option value="partial">Partial</option>
            <option value="paid">Paid</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Learner</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Term</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total (KES)</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Paid (KES)</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Balance (KES)</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($invoices ?? [] as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="text-sm font-semibold text-gray-900">{{ $invoice->learner->full_name }}</div>
                        <div class="text-xs text-gray-500">{{ $invoice->learner->admission_number }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-700">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">Term {{ $invoice->term }} · {{ $invoice->academic_year }}</td>
                    <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">{{ number_format($invoice->total_amount, 2) }}</td>
                    <td class="px-4 py-3 text-sm text-right text-green-700 font-medium">{{ number_format($invoice->amount_paid, 2) }}</td>
                    <td class="px-4 py-3 text-sm text-right {{ $invoice->balance > 0 ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                        {{ number_format($invoice->balance, 2) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' :
                              ($invoice->status === 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 flex gap-2">
                        <button wire:click="payMpesa({{ $invoice->id }})" class="text-green-600 hover:text-green-800 text-xs font-medium">M-Pesa</button>
                        <button wire:click="printReceipt({{ $invoice->id }})" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Receipt</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">No invoices found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if(isset($invoices))
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">{{ $invoices->links() }}</div>
        @endif
    </div>
</div>
