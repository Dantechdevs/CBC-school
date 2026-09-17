<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flashMessage): ?>
    <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3 mb-5">
        <?php echo e($flashMessage); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($view === 'list'): ?>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Homework</h2>
            <button wire:click="openCreate" class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800">
                + Assign Homework
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Learning Area</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submissions</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $homework; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-800"><?php echo e($hw->title); ?></p>
                            <p class="text-xs text-gray-400">by <?php echo e($hw->teacher->full_name ?? 'Unknown'); ?></p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($hw->schoolClass->name ?? '—'); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($hw->learningArea->name ?? '—'); ?></td>
                        <td class="px-4 py-3 text-sm">
                            <span class="<?php echo e($hw->is_overdue ? 'text-red-600 font-medium' : 'text-gray-700'); ?>">
                                <?php echo e($hw->due_at->format('d M Y, H:i')); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($hw->submissions_count); ?></td>
                        <td class="px-4 py-3">
                            <button wire:click="openGrading(<?php echo e($hw->id); ?>)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                View / Grade
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                            <p class="text-sm">No homework assigned this term yet.</p>
                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showCreateModal): ?>
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:click.self="closeCreate">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Assign Homework</h3>
                    <button wire:click="closeCreate" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Class</label>
                        <select wire:model="classId" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select class...</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['classId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Learning Area</label>
                        <select wire:model.live="learningAreaId" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select learning area...</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $learningAreas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($area->id); ?>"><?php echo e($area->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['learningAreaId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subStrands->isNotEmpty()): ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Sub-Strand (optional)</label>
                        <select wire:model="subStrandId" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">None</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subStrands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sub->id); ?>"><?php echo e($sub->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                        <input wire:model="title" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="e.g. Fractions worksheet">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Description / Instructions</label>
                        <textarea wire:model="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Due Date</label>
                            <input wire:model="dueDate" type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Due Time</label>
                            <input wire:model="dueTime" type="time" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input wire:model="allowLate" type="checkbox" class="rounded border-gray-300">
                        Allow late submissions
                    </label>
                </div>
                <div class="mt-5 flex justify-end gap-2">
                    <button wire:click="closeCreate" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                    <button wire:click="save" class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800">Assign</button>
                </div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php elseif($view === 'grade' && $gradingHomework): ?>
        <div class="flex items-center gap-3 mb-6">
            <button wire:click="backToList" class="text-gray-500 hover:text-gray-700">&larr; Back</button>
            <div>
                <h2 class="text-xl font-bold text-gray-800"><?php echo e($gradingHomework->title); ?></h2>
                <p class="text-xs text-gray-400"><?php echo e($gradingHomework->schoolClass->name ?? ''); ?> — due <?php echo e($gradingHomework->due_at->format('d M Y, H:i')); ?></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Learner</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score (/100)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rubric Level</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Feedback</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $gradingLearners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $learner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800"><?php echo e($learner->full_name); ?></td>
                        <td class="px-4 py-3">
                            <input wire:model="grades.<?php echo e($learner->id); ?>.score" type="number" min="0" max="100"
                                   class="w-20 border border-gray-300 rounded-lg px-2 py-1 text-sm">
                        </td>
                        <td class="px-4 py-3">
                            <select wire:model="grades.<?php echo e($learner->id); ?>.rubric_level" class="border border-gray-300 rounded-lg px-2 py-1 text-sm">
                                <option value="">—</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rubricLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($level->value); ?>"><?php echo e($level->value); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            <input wire:model="grades.<?php echo e($learner->id); ?>.feedback" type="text"
                                   class="w-full border border-gray-300 rounded-lg px-2 py-1 text-sm" placeholder="Optional remark">
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                <?php echo e(($grades[$learner->id]['status'] ?? 'pending') === 'graded' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'); ?>">
                                <?php echo e(ucfirst($grades[$learner->id]['status'] ?? 'pending')); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-end">
            <button wire:click="saveGrades" class="bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-800">
                Save Grades
            </button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\elimums\resources\views/livewire/homework/homework-manager.blade.php ENDPATH**/ ?>