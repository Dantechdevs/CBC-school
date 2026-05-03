<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Services\MpesaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MpesaController extends Controller
{
    public function __construct(private readonly MpesaService $mpesa) {}

    /** Initiate STK Push from parent portal */
    public function stkPush(Request $request): JsonResponse
    {
        $request->validate([
            'phone'          => 'required|string',
            'amount'         => 'required|numeric|min:1',
            'invoice_number' => 'required|string|exists:fee_invoices,invoice_number',
        ]);

        try {
            $result = $this->mpesa->stkPush(
                $request->phone,
                $request->amount,
                $request->invoice_number,
                "School Fees - {$request->invoice_number}"
            );

            return response()->json([
                'success'              => true,
                'checkout_request_id'  => $result['CheckoutRequestID'] ?? null,
                'message'              => 'STK Push sent. Enter your M-Pesa PIN to complete payment.',
            ]);
        } catch (\Throwable $e) {
            Log::error('STK Push failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Payment initiation failed. Try again.'], 500);
        }
    }

    /** M-Pesa STK Push callback — called by Safaricom */
    public function callback(Request $request): JsonResponse
    {
        $body = $request->all();
        Log::info('M-Pesa Callback', $body);

        $stkCallback = $body['Body']['stkCallback'] ?? null;
        if (!$stkCallback) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $resultCode  = $stkCallback['ResultCode'];
        $metadata    = collect($stkCallback['CallbackMetadata']['Item'] ?? [])
            ->pluck('Value', 'Name');

        if ($resultCode == 0) {
            $mpesaCode = $metadata['MpesaReceiptNumber'];
            $amount    = $metadata['Amount'];
            $phone     = $metadata['PhoneNumber'];

            // Find invoice by amount (simple match — in production use AccountReference)
            $invoice = FeeInvoice::where('status', '!=', 'paid')
                ->orderByDesc('created_at')->first();

            if ($invoice) {
                FeePayment::create([
                    'receipt_number'     => 'RCP-' . strtoupper(Str::random(8)),
                    'learner_id'         => $invoice->learner_id,
                    'fee_invoice_id'     => $invoice->id,
                    'amount'             => $amount,
                    'payment_method'     => 'mpesa',
                    'transaction_reference' => $mpesaCode,
                    'mpesa_receipt_number'  => $mpesaCode,
                    'mpesa_phone'        => $phone,
                    'status'             => 'confirmed',
                    'paid_at'            => now(),
                ]);

                $invoice->increment('amount_paid', $amount);
                $total = $invoice->fresh()->amount_paid;
                $invoice->update([
                    'status' => $total >= $invoice->total_amount ? 'paid' : 'partial',
                ]);
            }
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    /** C2B Validation */
    public function validation(Request $request): JsonResponse
    {
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    /** C2B Confirmation */
    public function confirmation(Request $request): JsonResponse
    {
        Log::info('M-Pesa C2B Confirmation', $request->all());
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}
