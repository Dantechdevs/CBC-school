<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AfricasTalkingService
{
    private string $apiKey;
    private string $username;
    private string $senderId;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey   = config('services.africastalking.api_key');
        $this->username = config('services.africastalking.username', 'sandbox');
        $this->senderId = config('services.africastalking.sender_id', 'SCHOOL');
        $this->baseUrl  = $this->username === 'sandbox'
            ? 'https://api.sandbox.africastalking.com/version1'
            : 'https://api.africastalking.com/version1';
    }

    /**
     * Send SMS to one or more recipients.
     *
     * @param  string|array  $recipients  Phone number(s) in format +2547XXXXXXXX
     */
    public function sendSms(string|array $recipients, string $message): array
    {
        $phones = is_array($recipients)
            ? implode(',', array_map([$this, 'formatPhone'], $recipients))
            : $this->formatPhone($recipients);

        $response = Http::withHeaders([
            'apiKey' => $this->apiKey,
            'Accept' => 'application/json',
        ])->asForm()->post("{$this->baseUrl}/messaging", [
            'username' => $this->username,
            'to'       => $phones,
            'message'  => $message,
            'from'     => $this->senderId,
        ]);

        $result = $response->json();
        Log::info('Africa\'s Talking SMS', ['to' => $phones, 'result' => $result]);

        return $result;
    }

    public function sendBulkSms(array $recipients, string $message): array
    {
        // Split into batches of 50 to avoid API limits
        $batches = array_chunk($recipients, 50);
        $results = [];

        foreach ($batches as $batch) {
            $results[] = $this->sendSms($batch, $message);
        }

        return $results;
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            return '+254' . substr($phone, 1);
        }
        if (!str_starts_with($phone, '+')) {
            return '+' . $phone;
        }
        return $phone;
    }
}
