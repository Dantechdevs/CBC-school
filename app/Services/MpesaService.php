<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    private string $env;
    private string $consumerKey;
    private string $consumerSecret;
    private string $shortcode;
    private string $passkey;
    private string $baseUrl;

    public function __construct()
    {
        $this->env            = config('services.mpesa.env', 'sandbox');
        $this->consumerKey    = config('services.mpesa.consumer_key', '');
        $this->consumerSecret = config('services.mpesa.consumer_secret', '');
        $this->shortcode      = config('services.mpesa.shortcode', '');
        $this->passkey        = config('services.mpesa.passkey', '');
        $this->baseUrl        = $this->env === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    /** Get OAuth access token */
    public function getAccessToken(): string
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get("{$this->baseUrl}/oauth/v1/generate", ['grant_type' => 'client_credentials']);

        if (!$response->successful()) {
            throw new \RuntimeException('M-Pesa token request failed: ' . $response->body());
        }

        return $response->json('access_token');
    }

    /** Initiate Lipa na M-Pesa STK Push */
    public function stkPush(string $phone, float $amount, string $accountRef, string $description = 'Payment'): array
    {
        $token     = $this->getAccessToken();
        $timestamp = now()->format('YmdHis');
        $password  = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", [
                'BusinessShortCode' => $this->shortcode,
                'Password'          => $password,
                'Timestamp'         => $timestamp,
                'TransactionType'   => 'CustomerPayBillOnline',
                'Amount'            => (int) ceil($amount),
                'PartyA'            => $this->formatPhone($phone),
                'PartyB'            => $this->shortcode,
                'PhoneNumber'       => $this->formatPhone($phone),
                'CallBackURL'       => config('services.mpesa.callback_url'),
                'AccountReference'  => $accountRef,
                'TransactionDesc'   => $description,
            ]);

        $result = $response->json();
        Log::info('M-Pesa STK Push', ['phone' => $phone, 'amount' => $amount, 'result' => $result]);

        return $result;
    }

    /** Query STK Push transaction status */
    public function stkQuery(string $checkoutRequestId): array
    {
        $token     = $this->getAccessToken();
        $timestamp = now()->format('YmdHis');
        $password  = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/mpesa/stkpushquery/v1/query", [
                'BusinessShortCode' => $this->shortcode,
                'Password'          => $password,
                'Timestamp'         => $timestamp,
                'CheckoutRequestID' => $checkoutRequestId,
            ]);

        return $response->json();
    }

    /** Register C2B URLs with Safaricom */
    public function registerC2BUrls(): array
    {
        $token    = $this->getAccessToken();
        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/mpesa/c2b/v1/registerurl", [
                'ShortCode'       => $this->shortcode,
                'ResponseType'    => 'Completed',
                'ConfirmationURL' => config('services.mpesa.confirmation_url'),
                'ValidationURL'   => config('services.mpesa.validation_url'),
            ]);

        return $response->json();
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            return '254' . substr($phone, 1);
        }
        if (str_starts_with($phone, '+')) {
            return substr($phone, 1);
        }
        return $phone;
    }
}
