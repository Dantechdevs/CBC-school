<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">KEMIS Integration</h2>
    </div>

    @if($flashMessage)
    <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3 mb-5">
        {{ $flashMessage }}
    </div>
    @endif
    @if($errorMessage)
    <div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg px-4 py-3 mb-5">
        {{ $errorMessage }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Learners with UPI</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalLearners - $missingUpi }} / {{ $totalLearners }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Missing UPI Numbers</p>
            <p class="text-2xl font-bold {{ $missingUpi > 0 ? 'text-red-700' : 'text-green-700' }}">{{ $missingUpi }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Duplicate UPI Numbers</p>
            <p class="text-2xl font-bold {{ $duplicateUpis->count() > 0 ? 'text-red-700' : 'text-green-700' }}">{{ $duplicateUpis->count() }}</p>
        </div>
    </div>

    @if($missingUpi > 0 || $duplicateUpis->isNotEmpty())
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-3 mb-6 text-sm text-yellow-800">
        <p class="font-medium mb-1">⚠ Pre-submission validation found issues</p>
        <p>{{ $missingUpi }} active learner(s) are missing a KEMIS UPI, and {{ $duplicateUpis->count() }} UPI number(s) appear more than once. Resolve these in the Learners register before syncing to avoid a rejected export.</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Trigger sync --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 lg:col-span-1">
            <h3 class="font-semibold text-gray-800 mb-4">Trigger Sync</h3>
            <div class="space-y-3">
                <select wire:model="syncType" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="learner">Learner UPI Sync</option>
                    <option value="bulk_export">Full Bulk Export</option>
                </select>
                <button wire:click="triggerSync" wire:loading.attr="disabled"
                        class="w-full bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800 disabled:opacity-50">
                    <span wire:loading.remove wire:target="triggerSync">Run Sync Now</span>
                    <span wire:loading wire:target="triggerSync">Queuing...</span>
                </button>
            </div>
            @if($lastSync)
            <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
                Last run: {{ $lastSync->started_at->diffForHumans() }} —
                <span class="font-medium {{ $lastSync->status === 'completed' ? 'text-green-700' : ($lastSync->status === 'failed' ? 'text-red-700' : 'text-blue-700') }}">
                    {{ ucfirst($lastSync->status) }}
                </span>
            </div>
            @endif
        </div>

        {{-- Sync history --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 lg:col-span-2">
            <h3 class="font-semibold text-gray-800 mb-4">Sync History</h3>
            @if($logs->isEmpty())
            <p class="text-sm text-gray-400 text-center py-8">No sync jobs have run yet.</p>
            @else
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Synced</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Failed</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Started</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($logs as $log)
                    <tr>
                        <td class="px-3 py-2 text-sm text-gray-700 capitalize">{{ str_replace('_',' ', $log->sync_type) }}</td>
                        <td class="px-3 py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $log->status === 'completed' ? 'bg-green-100 text-green-700' : ($log->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-sm text-gray-700">{{ $log->records_synced }}</td>
                        <td class="px-3 py-2 text-sm text-gray-700">{{ $log->records_failed }}</td>
                        <td class="px-3 py-2 text-sm text-gray-500">{{ $log->started_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
