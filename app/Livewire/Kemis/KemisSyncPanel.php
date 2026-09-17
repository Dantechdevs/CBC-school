<?php

namespace App\Livewire\Kemis;

use App\Jobs\SyncToKemisJob;
use App\Models\KemisSyncLog;
use App\Models\Learner;
use Livewire\Component;

class KemisSyncPanel extends Component
{
    public string $syncType = 'learner';
    public ?string $flashMessage = null;
    public ?string $errorMessage = null;

    public function triggerSync(): void
    {
        $this->validate(['syncType' => 'required|in:learner,bulk_export']);

        $staffId = auth()->user()->staffMember?->id;

        if (! $staffId) {
            $this->errorMessage = 'Your account has no linked staff record, so a sync cannot be attributed to you. Ask a super-admin to link your user to a staff profile first.';
            return;
        }

        $this->errorMessage = null;
        SyncToKemisJob::dispatch($this->syncType, $staffId);
        $this->flashMessage = 'KEMIS sync has been queued. Refresh this page shortly to see the result in the log below.';
    }

    public function render()
    {
        $missingUpi = Learner::active()->whereNull('kemis_upi')->count();
        $duplicateUpis = Learner::active()
            ->whereNotNull('kemis_upi')
            ->select('kemis_upi')
            ->groupBy('kemis_upi')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('kemis_upi');

        return view('livewire.kemis.kemis-sync-panel', [
            'logs'          => KemisSyncLog::latest('started_at')->take(15)->get(),
            'lastSync'      => KemisSyncLog::latest('started_at')->first(),
            'missingUpi'    => $missingUpi,
            'duplicateUpis' => $duplicateUpis,
            'totalLearners' => Learner::active()->count(),
        ])->layout('layouts.admin', ['header' => 'KEMIS Integration']);
    }
}
