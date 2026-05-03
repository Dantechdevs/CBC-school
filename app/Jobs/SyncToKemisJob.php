<?php

namespace App\Jobs;

use App\Services\KemisService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncToKemisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 2;
    public int $timeout = 300;

    public function __construct(
        public readonly string $syncType,
        public readonly int    $initiatedBy,
    ) {}

    public function handle(KemisService $kemis): void
    {
        $logId = DB::table('kemis_sync_logs')->insertGetId([
            'sync_type'    => $this->syncType,
            'status'       => 'running',
            'initiated_by' => $this->initiatedBy,
            'started_at'   => now(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        try {
            $result = match($this->syncType) {
                'bulk_export', 'learner' => $kemis->bulkSync(),
                default                  => ['synced' => 0, 'failed' => 0, 'total' => 0],
            };

            DB::table('kemis_sync_logs')->where('id', $logId)->update([
                'records_synced' => $result['synced'],
                'records_failed' => $result['failed'],
                'status'         => $result['failed'] > 0 && $result['synced'] === 0 ? 'failed' : 'completed',
                'completed_at'   => now(),
                'updated_at'     => now(),
            ]);

            Log::info('KEMIS sync completed', $result);
        } catch (\Throwable $e) {
            DB::table('kemis_sync_logs')->where('id', $logId)->update([
                'status'       => 'failed',
                'error_log'    => $e->getMessage(),
                'completed_at' => now(),
                'updated_at'   => now(),
            ]);
            Log::error('KEMIS sync job failed', ['error' => $e->getMessage()]);
        }
    }
}
