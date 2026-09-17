<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Fee Invoices</h2>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg px-4 py-3 mb-5">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by learner name or adm. no..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm">
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
            <option value="overpaid">Overpaid</option>
            <option value="waived">Waived</option>
        </select>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Learner</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Term</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-700 font-mono">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-3">
                        <p class="text-sm font-medium text-gray-800">{{ $invoice->learner->full_name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-400">{{ $invoice->learner->admission_number ?? '' }}</p>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">Term {{ $invoice->term }}, {{ $invoice->academic_year }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">KES {{ number_format($invoice->total_amount) }}</td>
                    <td class="px-4 py-3 text-sm font-semibold {{ $invoice->balance > 0 ? 'text-red-700' : 'text-green-700' }}">
                        KES {{ number_format($invoice->balance) }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ match($invoice->status) {
                                'paid', 'overpaid' => 'bg-green-100 text-green-700',
                                'partial' => 'bg-yellow-100 text-yellow-700',
                                'waived' => 'bg-blue-100 text-blue-700',
                                default => 'bg-red-100 text-red-700',
                            } }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 space-x-2">
                        <button wire:click="view({{ $invoice->id }})" class="text-blue-600 hover:text-blue-800 text-xs font-medium">View</button>
                        @if($invoice->payments->isNotEmpty())
                        <button wire:click="printReceipt({{ $invoice->id }})" class="text-purple-600 hover:text-purple-800 text-xs font-medium">Receipt</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-gray-400">No invoices found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $invoices->links() }}
        </div>
    </div>

    @if($viewingInvoice)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:click.self="closeView">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Invoice {{ $viewingInvoice->invoice_number }}</h3>
                <button wire:click="closeView" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                <div><p class="text-gray-400 text-xs">Learner</p><p class="font-medium text-gray-800">{{ $viewingInvoice->learner->full_name ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Term</p><p class="font-medium text-gray-800">Term {{ $viewingInvoice->term }}, {{ $viewingInvoice->academic_year }}</p></div>
                <div><p class="text-gray-400 text-xs">Total Amount</p><p class="font-medium text-gray-800">KES {{ number_format($viewingInvoice->total_amount) }}</p></div>
                <div><p class="text-gray-400 text-xs">Amount Paid</p><p class="font-medium text-gray-800">KES {{ number_format($viewingInvoice->amount_paid) }}</p></div>
                <div><p class="text-gray-400 text-xs">Balance</p><p class="font-medium {{ $viewingInvoice->balance > 0 ? 'text-red-700' : 'text-green-700' }}">KES {{ number_format($viewingInvoice->balance) }}</p></div>
                <div><p class="text-gray-400 text-xs">Due Date</p><p class="font-medium text-gray-800">{{ $viewingInvoice->due_date?->format('d M Y') ?? '—' }}</p></div>
            </div>
            <div class="border-t border-gray-100 pt-3">
                <p class="text-xs text-gray-400 mb-2">Payment History</p>
                @forelse($viewingInvoice->payments as $payment)
                <div class="flex items-center justify-between text-sm py-1">
                    <span class="text-gray-700">{{ $payment->paid_at->format('d M Y') }} · {{ $payment->payment_method?->label() }}</span>
                    <span class="font-medium text-green-700">KES {{ number_format($payment->amount) }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-400">No payments recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>
