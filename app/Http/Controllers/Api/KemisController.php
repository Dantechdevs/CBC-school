<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SyncToKemisJob;
use App\Models\KemisSyncLog;
use App\Services\KemisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KemisController extends Controller
{
    public function __construct(private readonly KemisService $kemis) {}

    public function sync(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:learner,school,bulk_export',
        ]);

        SyncToKemisJob::dispatch($request->type, auth()->user()->staffMember->id);

        return response()->json([
            'success' => true,
            'message' => 'KEMIS sync job queued. You will be notified when complete.',
        ]);
    }

    public function status(): JsonResponse
    {
        $last = \DB::table('kemis_sync_logs')->latest()->first();

        return response()->json([
            'last_sync'   => $last,
            'api_healthy' => $this->kemis->ping(),
        ]);
    }
}
