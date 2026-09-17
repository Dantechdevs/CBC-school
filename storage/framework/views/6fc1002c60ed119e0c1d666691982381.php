<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">Settings</h2>
</div>

<div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-lg px-4 py-3 mb-6">
    These settings are currently read from your <code class="bg-blue-100 px-1 rounded">.env</code> file and
    <code class="bg-blue-100 px-1 rounded">config/school.php</code>. Editing them here isn't wired up yet —
    there's no settings table in the database. To change a value, update <code class="bg-blue-100 px-1 rounded">.env</code>
    and run <code class="bg-blue-100 px-1 rounded">php artisan config:clear</code>.
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">School Profile</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">School Name</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.name')); ?></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Motto</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.motto')); ?></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Type</dt><dd class="font-medium text-gray-800 capitalize"><?php echo e(config('school.type')); ?></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Address</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.address') ?: '—'); ?></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Phone</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.phone') ?: '—'); ?></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.email') ?: '—'); ?></dd></div>
        </dl>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Academic Calendar</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Current Academic Year</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.academic_year')); ?></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Current Term</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.terms.'.config('school.current_term'))); ?></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Term Start Date</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.current_term_start') ?: 'Not set (CURRENT_TERM_START in .env)'); ?></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Term End Date</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.current_term_end') ?: 'Not set (CURRENT_TERM_END in .env)'); ?></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Next Term Starts</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.next_term_start') ?: 'Not set (NEXT_TERM_START in .env)'); ?></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Formative Weight</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.assessment_weights.formative')); ?>%</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Summative Weight</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.assessment_weights.summative')); ?>%</dd></div>
        </dl>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Integrations</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between items-center">
                <dt class="text-gray-500">M-Pesa (Daraja)</dt>
                <dd>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?php echo e(config('school.fees.mpesa_enabled') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'); ?>">
                        <?php echo e(config('school.fees.mpesa_enabled') ? 'Configured' : 'Not configured'); ?>

                    </span>
                </dd>
            </div>
            <div class="flex justify-between items-center">
                <dt class="text-gray-500">SMS (Africa's Talking)</dt>
                <dd>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?php echo e(config('school.sms.enabled') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'); ?>">
                        <?php echo e(config('school.sms.enabled') ? 'Configured' : 'Not configured'); ?>

                    </span>
                </dd>
            </div>
            <div class="flex justify-between"><dt class="text-gray-500">SMS Sender ID</dt><dd class="font-medium text-gray-800"><?php echo e(config('school.sms.sender_id')); ?></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Invoice Prefix</dt><dd class="font-medium text-gray-800 font-mono"><?php echo e(config('school.fees.invoice_prefix')); ?></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Receipt Prefix</dt><dd class="font-medium text-gray-800 font-mono"><?php echo e(config('school.fees.receipt_prefix')); ?></dd></div>
        </dl>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Grade Levels Offered</h3>
        <div class="space-y-2 text-sm">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('school.grade_levels'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $band => $grades): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex justify-between">
                <dt class="text-gray-500"><?php echo e(ucwords(str_replace('_', ' ', $band))); ?></dt>
                <dd class="font-medium text-gray-800"><?php echo e(implode(', ', $grades)); ?></dd>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\elimums\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>