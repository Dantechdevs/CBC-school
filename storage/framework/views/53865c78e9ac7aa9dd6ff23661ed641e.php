<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Send Notification</h2>
        <span class="text-sm text-gray-500">Recipients preview: <strong class="text-green-700"><?php echo e($count); ?></strong></span>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sent): ?>
    <div class="mb-5 bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-sm text-green-800 flex items-center gap-2">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <?php echo e($flash); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Notification Type</label>
                <select wire:model="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="general">📢 General Announcement</option>
                    <option value="fees">💰 Fees Reminder</option>
                    <option value="exam">📝 Exam Notice</option>
                    <option value="report_card">📄 Report Card Ready</option>
                    <option value="attendance">📋 Attendance Alert</option>
                    <option value="emergency">🚨 Emergency</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Channel</label>
                <select wire:model="channel" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="sms">📱 SMS</option>
                    <option value="email">✉️ Email</option>
                    <option value="push">🔔 Push Notification</option>
                    <option value="all">📡 All Channels</option>
                </select>
            </div>
        </div>

        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Target Grade (optional)</label>
                <select wire:model.live="targetGrade" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All Grades</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('school.grade_levels'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $grades): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <optgroup label="<?php echo e(ucwords(str_replace('_',' ',$level))); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($grade); ?>"><?php echo e($grade); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </optgroup>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Target Group</label>
                <select wire:model.live="targetGroup" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="all">All Learners</option>
                    <option value="boarding">Boarding Only</option>
                    <option value="day">Day Scholars Only</option>
                </select>
            </div>
        </div>

        
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Title <span class="text-red-500">*</span></label>
            <input wire:model="title" type="text" maxlength="100"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="e.g. Term 2 Fees Reminder">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">
                Message <span class="text-red-500">*</span>
                <span class="ml-2 text-gray-400 font-normal"><?php echo e(strlen($message)); ?>/480 chars</span>
            </label>
            <textarea wire:model="message" rows="5" maxlength="480"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                      placeholder="Type your message here. Keep SMS under 160 chars for single SMS billing."></textarea>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(strlen($message) > 160): ?>
            <p class="text-xs text-yellow-600 mt-1">⚠️ Message exceeds 160 chars — will be billed as <?php echo e(ceil(strlen($message)/153)); ?> SMS parts per recipient.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="pt-2 border-t flex justify-between items-center">
            <div class="text-sm text-gray-500">
                Will be sent to <strong class="text-gray-800"><?php echo e($count); ?></strong> guardian(s)
            </div>
            <button wire:click="send" wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-not-allowed"
                    class="bg-green-700 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-green-800 flex items-center gap-2">
                <span wire:loading.remove wire:target="send">Send Notification</span>
                <span wire:loading wire:target="send">Sending...</span>
            </button>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\elimums\resources\views/livewire/notifications/send-notification.blade.php ENDPATH**/ ?>