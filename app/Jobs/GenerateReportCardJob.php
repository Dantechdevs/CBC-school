<?php

namespace App\Jobs;

use App\Models\Learner;
use App\Services\AfricasTalkingService;
use App\Services\ReportCardService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateReportCardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(
        public readonly int    $learnerId,
        public readonly string $term,
        public readonly string $academicYear,
        public readonly bool   $notifyParent = true,
    ) {}

    public function handle(ReportCardService $service, AfricasTalkingService $sms): void
    {
        try {
            $fileName = $service->generate($this->learnerId, $this->term, $this->academicYear);
            Log::info("Report card generated: {$fileName}");

            if ($this->notifyParent) {
                $learner = Learner::with('guardians')->find($this->learnerId);
                $primary = $learner?->guardians->firstWhere('pivot.is_primary', true)
                         ?? $learner?->guardians->first();

                if ($primary?->phone_number) {
                    $sms->sendSms(
                        $primary->phone_number,
                        "Dear {$primary->first_name}, the Term {$this->term} report card for {$learner->full_name} is ready. Please visit the school or check the parent portal to download it."
                    );
                }
            }
        } catch (\Throwable $e) {
            Log::error('Report card generation failed', [
                'learner_id' => $this->learnerId,
                'error'      => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
