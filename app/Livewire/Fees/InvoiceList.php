<?php

namespace App\Livewire\Fees;

use App\Models\FeeInvoice;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceList extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $termFilter   = '';
    public string $statusFilter = '';
    public ?int $viewingId = null;

    protected $queryString = ['search', 'termFilter', 'statusFilter'];

    public function updatingSearch(): void { $this->resetPage(); }

    public function view(int $id): void { $this->viewingId = $id; }
    public function closeView(): void { $this->viewingId = null; }

    public function printReceipt(int $invoiceId)
    {
        $invoice = FeeInvoice::findOrFail($invoiceId);
        $payment = $invoice->payments()->where('status', 'confirmed')->latest('paid_at')->first();

        if (! $payment) {
            session()->flash('error', 'No confirmed payment found for this invoice yet.');
            return;
        }

        $path = app(\App\Services\ReceiptService::class)->generate($payment->id);

        return $this->redirect(\Illuminate\Support\Facades\Storage::url($path));
    }

    public function render()
    {
        $invoices = FeeInvoice::with(['learner', 'payments'])
            ->when($this->search, fn($q) => $q->whereHas('learner', fn($q) =>
                $q->where('first_name', 'like', "%{$this->search}%")
                  ->orWhere('last_name', 'like', "%{$this->search}%")
                  ->orWhere('admission_number', 'like', "%{$this->search}%")))
            ->when($this->termFilter, fn($q) => $q->where('term', $this->termFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.fees.invoice-list', [
            'invoices'       => $invoices,
            'viewingInvoice' => $this->viewingId ? FeeInvoice::with(['learner', 'payments'])->find($this->viewingId) : null,
        ])->layout('layouts.finance', ['header' => 'Invoices']);
    }
}
