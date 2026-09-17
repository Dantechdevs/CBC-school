<?php

namespace App\Services;

use App\Models\FeePayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReceiptService
{
    public function generate(int $paymentId): string
    {
        $payment = FeePayment::with(['invoice', 'learner'])->findOrFail($paymentId);
        $invoice = $payment->invoice;
        $learner = $payment->learner;

        $pdf = Pdf::loadView('pdf.fee-receipt', [
            'payment' => $payment,
            'invoice' => $invoice,
            'learner' => $learner,
        ])->setPaper('a5', 'portrait');

        $fileName = "receipts/{$invoice->academic_year}/{$payment->receipt_number}.pdf";
        Storage::put("public/{$fileName}", $pdf->output());

        return $fileName;
    }
}
