<?php

namespace App\Jobs;

use App\Models\Guardian;
use App\Models\NotificationLog;
use App\Models\SchoolNotification;
use App\Services\AfricasTalkingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly int    $notificationId,
        public readonly string $targetGrade = '',
        public readonly string $targetGroup = 'all',
    ) {}

    public function handle(AfricasTalkingService $sms): void
    {
        $notification = SchoolNotification::findOrFail($this->notificationId);
        $notification->update(['status' => 'queued', 'sent_at' => now()]);

        // Build recipients query
        $query = Guardian::query()->whereNotNull('phone_number');

        if ($this->targetGrade) {
            $query->whereHas('learners', fn($q) => $q->where('grade_level', $this->targetGrade)->where('is_active', true));
        }
        if ($this->targetGroup === 'boarding') {
            $query->whereHas('learners', fn($q) => $q->where('boarding_status', 'boarding'));
        } elseif ($this->targetGroup === 'day') {
            $query->whereHas('learners', fn($q) => $q->where('boarding_status', 'day'));
        }

        $guardians   = $query->distinct()->get(['id', 'phone_number']);
        $sent        = 0;
        $failed      = 0;
        $message     = "{$notification->title}\n\n{$notification->message}";

        // Send in batches of 50
        foreach ($guardians->chunk(50) as $batch) {
            $phones = $batch->pluck('phone_number')->toArray();

            try {
                $result = $sms->sendSms($phones, $message);
                $sent  += count($phones);

                foreach ($batch as $guardian) {
                    \DB::table('notification_logs')->insert([
                        'notification_id'   => $notification->id,
                        'recipient_phone'   => $guardian->phone_number,
                        'channel'           => 'sms',
                        'status'            => 'sent',
                        'sent_at'           => now(),
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);
                }
            } catch (\Throwable $e) {
                $failed += count($phones);
                Log::error('SMS batch failed', ['error' => $e->getMessage(), 'phones' => $phones]);
            }
        }

        $notification->update([
            'status'          => $failed > 0 && $sent === 0 ? 'failed' : ($failed > 0 ? 'partial' : 'sent'),
            'sent_count'      => $sent,
            'failed_count'    => $failed,
            'total_recipients'=> $guardians->count(),
        ]);
    }
}
