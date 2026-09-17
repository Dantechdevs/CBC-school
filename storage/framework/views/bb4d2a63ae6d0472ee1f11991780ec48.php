<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Exams Management</h2>
        <button wire:click="$set('showCreateModal', true)" class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800">
            + Create Exam
        </button>
    </div>
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exam Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Learning Area</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Term</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900"><?php echo e($exam->name); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($exam->grade_level); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($exam->learningArea->name ?? '—'); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700 capitalize"><?php echo e(str_replace('_',' ',$exam->exam_type)); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700">Term <?php echo e($exam->term); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($exam->exam_date?->format('d M Y') ?? '—'); ?></td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($exam->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'); ?>">
                            <?php echo e(ucfirst($exam->status)); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 flex gap-2">
                        <button wire:click="loadMarkEntry(<?php echo e($exam->id); ?>)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Enter Marks</button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">No exams created yet.</td></tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <div class="px-4 py-3 border-t bg-gray-50"><?php echo e($exams->links()); ?></div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\elimums\resources\views/livewire/exams/exam-manager.blade.php ENDPATH**/ ?>