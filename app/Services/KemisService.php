<?php

namespace App\Services;

use App\Models\Learner;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KemisService
{
    private string $apiUrl;
    private string $apiKey;
    private string $schoolCode;

    public function __construct()
    {
        $this->apiUrl     = config('services.kemis.api_url', 'https://kemis.education.go.ke/api');
        $this->apiKey     = config('services.kemis.api_key', '');
        $this->schoolCode = config('services.kemis.school_code', '');
    }

    public function ping(): bool
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(5)
                ->get("{$this->apiUrl}/health");
            return $response->successful();
        } catch (\Throwable) {
            return false;
        }
    }

    /** Register/update a single learner in KEMIS */
    public function syncLearner(Learner $learner): array
    {
        $payload = [
            'school_code'   => $this->schoolCode,
            'admission_no'  => $learner->admission_number,
            'first_name'    => $learner->first_name,
            'middle_name'   => $learner->middle_name,
            'last_name'     => $learner->last_name,
            'dob'           => $learner->date_of_birth?->format('Y-m-d'),
            'gender'        => $learner->gender,
            'grade'         => $learner->grade_level?->value,
            'birth_cert_no' => $learner->birth_certificate_number,
        ];

        try {
            $response = Http::withHeaders($this->headers())
                ->post("{$this->apiUrl}/learners/register", $payload);

            if ($response->successful()) {
                $upi = $response->json('upi');
                if ($upi) {
                    $learner->update(['kemis_upi' => $upi]);
                }
                return ['success' => true, 'upi' => $upi, 'response' => $response->json()];
            }

            Log::warning('KEMIS sync failed for learner', [
                'id'     => $learner->id,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return ['success' => false, 'error' => $response->body()];
        } catch (\Throwable $e) {
            Log::error('KEMIS API error', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /** Bulk sync all active learners without a KEMIS UPI */
    public function bulkSync(): array
    {
        $learners = Learner::active()->whereNull('kemis_upi')->get();
        $synced = 0;
        $failed = 0;

        foreach ($learners as $learner) {
            $result = $this->syncLearner($learner);
            $result['success'] ? $synced++ : $failed++;
        }

        return ['synced' => $synced, 'failed' => $failed, 'total' => $learners->count()];
    }

    /** Look up learner by admission number in KEMIS */
    public function lookupLearner(string $admissionNumber): ?array
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->get("{$this->apiUrl}/learners/lookup", [
                    'school_code'  => $this->schoolCode,
                    'admission_no' => $admissionNumber,
                ]);
            return $response->successful() ? $response->json() : null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function headers(): array
    {
        return [
            'Authorization' => "Bearer {$this->apiKey}",
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ];
    }
}
