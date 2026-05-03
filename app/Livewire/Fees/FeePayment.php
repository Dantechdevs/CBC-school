<?php

namespace App\Livewire\Fees;

use App\Models\FeeInvoice;
use App\Services\MpesaService;
use Livewire\Component;
use Livewire\WithPagination;

class FeePayment extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $termFilter   = '';
    public string $statusFilter = '';
    public bool   $showMpesaModal = false;
    public ?int   $selectedInvoiceId = null;
    public string $mpesaPhone   = '';

    protected $queryString = ['search', 'termFilter', 'statusFilter'];

    public function updatingSearch(): void { $this->resetPage(); }

    public function payMpesa(int $invoiceId): void
    {
        $this->selectedInvoiceId = $invoiceId;
        $this->showMpesaModal    = true;
    }

    public function initiateStkPush(): void
    {
        $this->validate(['mpesaPhone' => 'required|min:9']);
        $invoice = FeeInvoice::findOrFail($this->selectedInvoiceId);

        try {
            $mpesa  = app(MpesaService::class);
            $result = $mpesa->stkPush($this->mpesaPhone, $invoice->balance, $invoice->invoice_number);
            if ($result['success']) {
                session()->flash('success', 'STK Push sent. Prompt the parent to enter their M-Pesa PIN.');
                $this->showMpesaModal = false;
            } else {
                session()->flash('error', $result['message'] ?? 'STK Push failed.');
            }
        } catch (\Throwable $e) {
            session()->flash('error', 'M-Pesa error: ' . $e->getMessage());
        }
    }

    public function printReceipt(int $invoiceId): void
    {
        $this->redirectRoute('admin.fees.receipt', $invoiceId);
    }

    public function render()
    {
        $invoices = FeeInvoice::with(['learner'])
            ->when($this->search, fn($q) => $q->whereHas('learner', fn($q) =>
                $q->where('first_name', 'like', "%{$this->search}%")
                  ->orWhere('last_name',  'like', "%{$this->search}%")
                  ->orWhere('admission_number', 'like', "%{$this->search}%")))
            ->when($this->termFilter, fn($q) => $q->where('term', $this->termFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.fees.fee-payment', compact('invoices'))
            ->layout('layouts.finance');
    }
}
