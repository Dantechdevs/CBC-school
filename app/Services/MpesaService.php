<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\FeePayment;
use App\Models\FeeInvoice;

class MpesaService
{
    private string $baseUrl;
    private string $consumerKey;
    private string $consumerSecret;
    private string $shortcode;
    private string $passkey;

    public function __construct()
    {
        $env = config('services.mpesa.env', 'sandbox');
        $this->baseUrl       = $env === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
        $this->consumerKey    = config('services.mpesa.consumer_key');
        $this->consumerSecret = config('services.mpesa.consumer_secret');
        $this->shortcode      = config('services.mpesa.shortcode');
        $this->passkey        = config('services.mpesa.passkey');
    }

    public function getAccessToken(): string
    {
        return Cache::remember('mpesa_access_token', 3500, function () {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

            if ($response->failed()) {
                throw new \RuntimeException('Failed to get M-Pesa access token');
            }

            return $response->json('access_token');
        });
    }

    public function stkPush(string $phone, float $amount, string $invoiceNumber, string $description = ''): array
    {
        $timestamp = now()->format('YmdHis');
        $password  = base64_encode($this->shortcode . $this->passkey . $timestamp);
        $phone     = $this->formatPhone($phone);

        $response = Http::withToken($this->getAccessToken())
            ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", [
                'BusinessShortCode' => $this->shortcode,
                'Password'          => $password,
                'Timestamp'         => $timestamp,
                'TransactionType'   => 'CustomerPayBillOnline',
                'Amount'            => (int) ceil($amount),
                'PartyA'            => $phone,
                'PartyB'            => $this->shortcode,
                'PhoneNumber'       => $phone,
                'CallBackURL'       => config('services.mpesa.callback_url'),
                'AccountReference'  => $invoiceNumber,
                'TransactionDesc'   => $description ?: "Fee payment {$invoiceNumber}",
            ]);

        Log::info('M-Pesa STK Push', ['phone' => $phone, 'amount' => $amount, 'response' => $response->json()]);

        return $response->json();
    }

    public function handleCallback(array $data): void
    {
        $resultCode = data_get($data, 'Body.stkCallback.ResultCode');
        $metadata   = data_get($data, 'Body.stkCallback.CallbackMetadata.Item', []);

        if ($resultCode !== 0) {
            Log::warning('M-Pesa STK callback failed', $data);
            return;
        }

        $items = collect($metadata)->pluck('Value', 'Name');

        $payment = FeePayment::where('mpesa_transaction_id',
            data_get($data, 'Body.stkCallback.CheckoutRequestID')
        )->first();

        if ($payment) {
            $payment->update([
                'status'               => 'completed',
                'mpesa_receipt_number' => $items->get('MpesaReceiptNumber'),
                'amount'               => $items->get('Amount'),
            ]);

            // Update invoice
            $invoice = $payment->invoice;
            $invoice->increment('amount_paid', $payment->amount);

            if ($invoice->amount_paid >= $invoice->amount_due) {
                $invoice->update(['status' => 'paid']);
            } elseif ($invoice->amount_paid > 0) {
                $invoice->update(['status' => 'partial']);
            }

            Log::info('M-Pesa payment confirmed', ['receipt' => $items->get('MpesaReceiptNumber')]);
        }
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        }
        if (str_starts_with($phone, '+')) {
            $phone = substr($phone, 1);
        }
        return $phone;
    }
}
