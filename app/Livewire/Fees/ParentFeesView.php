<?php

namespace App\Livewire\Fees;

use App\Models\FeeInvoice;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ParentFeesView extends Component
{
    public bool $showMpesaModal = false;
    public ?int $selectedInvoiceId = null;
    public string $mpesaPhone = '';

    public function payMpesa(int $invoiceId): void
    {
        $this->selectedInvoiceId = $invoiceId;
        $this->mpesaPhone = auth()->user()->guardian?->phone_number ?? '';
        $this->showMpesaModal = true;
    }

    public function closeMpesaModal(): void
    {
        $this->showMpesaModal = false;
    }

    protected function childIds(): array
    {
        $guardian = Auth::user()->guardian;
        return $guardian ? $guardian->learners()->pluck('learners.id')->toArray() : [];
    }

    public function initiateStkPush(): void
    {
        $this->validate(['mpesaPhone' => 'required|min:9']);

        $invoice = FeeInvoice::whereIn('learner_id', $this->childIds())->findOrFail($this->selectedInvoiceId);

        try {
            $mpesa  = app(MpesaService::class);
            $result = $mpesa->stkPush($this->mpesaPhone, $invoice->balance, $invoice->invoice_number);

            if ($result['success']) {
                session()->flash('success', 'STK Push sent. Please enter your M-Pesa PIN on your phone to complete payment.');
                $this->showMpesaModal = false;
            } else {
                session()->flash('error', $result['message'] ?? 'STK Push failed. Please try again.');
            }
        } catch (\Throwable $e) {
            session()->flash('error', 'M-Pesa error: '.$e->getMessage());
        }
    }

    public function render()
    {
        $childIds = $this->childIds();

        $invoices = FeeInvoice::with('learner')
            ->whereIn('learner_id', $childIds)
            ->orderByDesc('academic_year')
            ->orderByDesc('term')
            ->get();

        return view('livewire.fees.parent-fees-view', [
            'invoices' => $invoices,
        ])->layout('layouts.parent', ['header' => 'Fees']);
    }
}
