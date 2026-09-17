<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Inventory Management</h2>
        <div class="flex gap-2">
            <button class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">📥 Receive Stock</button>
            <button class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800">+ Add Item</button>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-sm text-green-800"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search items..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <select wire:model.live="typeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Items</option>
            <option value="textbook">Textbooks Only</option>
            <option value="low_stock">⚠️ Low Stock</option>
        </select>
        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input wire:model.live="lowStock" type="checkbox" class="rounded"> Show low stock only
        </label>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">In Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issued</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min Level</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $isLow = $item->quantity_in_stock <= $item->minimum_stock_level; ?>
                <tr class="hover:bg-gray-50 <?php echo e($isLow ? 'bg-red-50' : ''); ?>">
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-900"><?php echo e($item->name); ?></div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->is_textbook): ?> <span class="text-xs text-blue-600">📚 Textbook</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->grade_level): ?> <span class="text-xs text-gray-400">· <?php echo e($item->grade_level); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-600"><?php echo e($item->code); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($item->category->name ?? '—'); ?></td>
                    <td class="px-4 py-3">
                        <span class="text-sm font-bold <?php echo e($isLow ? 'text-red-700' : 'text-gray-900'); ?>">
                            <?php echo e($item->quantity_in_stock); ?> <?php echo e($item->unit); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($item->quantity_issued); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($item->minimum_stock_level); ?></td>
                    <td class="px-4 py-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->quantity_in_stock == 0): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Out of Stock</span>
                        <?php elseif($isLow): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">⚠️ Low Stock</span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">In Stock</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="px-4 py-3 flex gap-2">
                        <button wire:click="openIssueModal(<?php echo e($item->id); ?>)"
                                class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                                <?php if($item->quantity_in_stock == 0): ?> disabled <?php endif; ?>>Issue</button>
                        <button class="text-xs text-green-600 hover:text-green-800 font-medium">Receive</button>
                        <button class="text-xs text-gray-500 hover:text-gray-700 font-medium">History</button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="px-4 py-10 text-center text-gray-400 text-sm">No inventory items found.</td></tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <div class="px-4 py-3 border-t bg-gray-50"><?php echo e($items->links()); ?></div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showIssueModal): ?>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Issue Item</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Quantity</label>
                    <input wire:model="issueQty" type="number" min="1"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['issueQty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Issue To</label>
                    <select wire:model="issueType" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="learner">Learner</option>
                        <option value="staff">Staff</option>
                        <option value="class">Class</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Remarks</label>
                    <input wire:model="issueRemarks" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button wire:click="closeIssueModal" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button wire:click="issueItem" class="px-4 py-2 text-sm bg-green-700 text-white rounded-lg hover:bg-green-800">Confirm Issue</button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\elimums\resources\views/livewire/inventory/inventory-list.blade.php ENDPATH**/ ?>