<div>
    <h2 class="text-xl font-bold text-gray-800 mb-6">Fee Statements</h2>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3 mb-5">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg px-4 py-3 mb-5">{{ session('error') }}</div>
    @endif

    @if($invoices->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
        No fee invoices found for your children yet.
    </div>
    @else
    <div class="space-y-3">
        @foreach($invoices as $invoice)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-800">{{ $invoice->learner->full_name ?? 'Unknown' }}</p>
                <p class="text-xs text-gray-400">{{ $invoice->invoice_number }} · Term {{ $invoice->term }}, {{ $invoice->academic_year }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold {{ $invoice->balance > 0 ? 'text-red-700' : 'text-green-700' }}">
                    KES {{ number_format($invoice->balance) }} due
                </p>
                <p class="text-xs text-gray-400">of KES {{ number_format($invoice->total_amount) }}</p>
            </div>
            @if($invoice->balance > 0)
            <button wire:click="payMpesa({{ $invoice->id }})" class="ml-4 bg-green-700 text-white text-xs font-medium px-3 py-2 rounded-lg hover:bg-green-800">
                Pay with M-Pesa
            </button>
            @else
            <span class="ml-4 px-3 py-2 bg-green-50 text-green-700 text-xs font-medium rounded-lg">Paid</span>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    @if($showMpesaModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:click.self="closeMpesaModal">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Pay with M-Pesa</h3>
                <button wire:click="closeMpesaModal" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <label class="block text-xs font-medium text-gray-600 mb-1">M-Pesa Phone Number</label>
            <input wire:model="mpesaPhone" type="text" placeholder="07XXXXXXXX" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            @error('mpesaPhone') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            <div class="mt-5 flex justify-end gap-2">
                <button wire:click="closeMpesaModal" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                <button wire:click="initiateStkPush" wire:loading.attr="disabled" class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800 disabled:opacity-50">
                    <span wire:loading.remove wire:target="initiateStkPush">Send STK Push</span>
                    <span wire:loading wire:target="initiateStkPush">Sending...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
