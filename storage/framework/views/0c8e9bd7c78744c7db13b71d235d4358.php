<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">KEMIS Integration</h2>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flashMessage): ?>
    <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3 mb-5">
        <?php echo e($flashMessage); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errorMessage): ?>
    <div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg px-4 py-3 mb-5">
        <?php echo e($errorMessage); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Learners with UPI</p>
            <p class="text-2xl font-bold text-gray-900"><?php echo e($totalLearners - $missingUpi); ?> / <?php echo e($totalLearners); ?></p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Missing UPI Numbers</p>
            <p class="text-2xl font-bold <?php echo e($missingUpi > 0 ? 'text-red-700' : 'text-green-700'); ?>"><?php echo e($missingUpi); ?></p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Duplicate UPI Numbers</p>
            <p class="text-2xl font-bold <?php echo e($duplicateUpis->count() > 0 ? 'text-red-700' : 'text-green-700'); ?>"><?php echo e($duplicateUpis->count()); ?></p>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($missingUpi > 0 || $duplicateUpis->isNotEmpty()): ?>
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-3 mb-6 text-sm text-yellow-800">
        <p class="font-medium mb-1">⚠ Pre-submission validation found issues</p>
        <p><?php echo e($missingUpi); ?> active learner(s) are missing a KEMIS UPI, and <?php echo e($duplicateUpis->count()); ?> UPI number(s) appear more than once. Resolve these in the Learners register before syncing to avoid a rejected export.</p>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastSync): ?>
            <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
                Last run: <?php echo e($lastSync->started_at->diffForHumans()); ?> —
                <span class="font-medium <?php echo e($lastSync->status === 'completed' ? 'text-green-700' : ($lastSync->status === 'failed' ? 'text-red-700' : 'text-blue-700')); ?>">
                    <?php echo e(ucfirst($lastSync->status)); ?>

                </span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 lg:col-span-2">
            <h3 class="font-semibold text-gray-800 mb-4">Sync History</h3>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logs->isEmpty()): ?>
            <p class="text-sm text-gray-400 text-center py-8">No sync jobs have run yet.</p>
            <?php else: ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-3 py-2 text-sm text-gray-700 capitalize"><?php echo e(str_replace('_',' ', $log->sync_type)); ?></td>
                        <td class="px-3 py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                <?php echo e($log->status === 'completed' ? 'bg-green-100 text-green-700' : ($log->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700')); ?>">
                                <?php echo e(ucfirst($log->status)); ?>

                            </span>
                        </td>
                        <td class="px-3 py-2 text-sm text-gray-700"><?php echo e($log->records_synced); ?></td>
                        <td class="px-3 py-2 text-sm text-gray-700"><?php echo e($log->records_failed); ?></td>
                        <td class="px-3 py-2 text-sm text-gray-500"><?php echo e($log->started_at->diffForHumans()); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\elimums\resources\views/livewire/kemis/kemis-sync-panel.blade.php ENDPATH**/ ?>