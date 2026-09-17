<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Learning Notes & Resources</h2>
        <button wire:click="$set('showModal', true)"
                class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800">
            + Upload Resource
        </button>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-sm text-green-800"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-3">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search resources..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <select wire:model.live="gradeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Grades</option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('school.grade_levels'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $grades): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <optgroup label="<?php echo e(ucwords(str_replace('_',' ',$level))); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($grade); ?>"><?php echo e($grade); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </optgroup>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </select>
        <select wire:model.live="areaFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Learning Areas</option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $learningAreas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($area->id); ?>"><?php echo e($area->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </select>
        <select wire:model.live="termFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Terms</option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('school.terms'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($n); ?>"><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </select>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $icons = ['pdf'=>'📄','video'=>'🎬','image'=>'🖼️','document'=>'📝','link'=>'🔗','other'=>'📎'];
            $icon = $icons[$note->resource_type] ?? '📎';
        ?>
        <div class="bg-white rounded-xl shadow-sm p-5 flex flex-col gap-3 border border-gray-100 hover:border-green-200 transition-colors">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-2xl"><?php echo e($icon); ?></span>
                    <div>
                        <div class="text-sm font-semibold text-gray-900 leading-tight"><?php echo e($note->title); ?></div>
                        <div class="text-xs text-gray-500 mt-0.5"><?php echo e($note->grade_level); ?> · <?php echo e($note->learningArea->name); ?></div>
                    </div>
                </div>
                <span class="text-xs px-2 py-1 rounded-full <?php echo e($note->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'); ?>">
                    <?php echo e($note->is_published ? 'Published' : 'Draft'); ?>

                </span>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($note->description): ?>
            <p class="text-xs text-gray-500 line-clamp-2"><?php echo e($note->description); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-100">
                <div class="text-xs text-gray-400">
                    Term <?php echo e($note->term); ?> · <?php echo e($note->download_count); ?> downloads
                </div>
                <div class="flex gap-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($note->file_path): ?>
                    <a href="<?php echo e(Storage::url($note->file_path)); ?>" target="_blank"
                       class="text-xs text-blue-600 hover:text-blue-800 font-medium">Download</a>
                    <?php elseif($note->external_url): ?>
                    <a href="<?php echo e($note->external_url); ?>" target="_blank"
                       class="text-xs text-blue-600 hover:text-blue-800 font-medium">Open Link</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button wire:click="togglePublish(<?php echo e($note->id); ?>)"
                            class="text-xs text-yellow-600 hover:text-yellow-800 font-medium">
                        <?php echo e($note->is_published ? 'Unpublish' : 'Publish'); ?>

                    </button>
                    <button wire:click="delete(<?php echo e($note->id); ?>)"
                            wire:confirm="Delete this resource?"
                            class="text-xs text-red-500 hover:text-red-700 font-medium">Delete</button>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-3 py-16 text-center text-gray-400">
            <div class="text-5xl mb-3">📚</div>
            <p class="text-sm">No resources found. Upload the first learning note!</p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <div class="mt-4"><?php echo e($notes->links()); ?></div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModal): ?>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Upload Learning Resource</h3>
                <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Grade <span class="text-red-500">*</span></label>
                        <select wire:model="grade" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select grade...</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('school.grade_levels'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $grades): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <optgroup label="<?php echo e(ucwords(str_replace('_',' ',$level))); ?>">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($g); ?>"><?php echo e($g); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </optgroup>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Term <span class="text-red-500">*</span></label>
                        <select wire:model="term" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select term...</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('school.terms'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($n); ?>"><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Learning Area <span class="text-red-500">*</span></label>
                    <select wire:model="learningAreaId" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Select learning area...</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $learningAreas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($area->id); ?>"><?php echo e($area->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Title <span class="text-red-500">*</span></label>
                    <input wire:model="title" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Description</label>
                    <textarea wire:model="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Resource Type</label>
                        <select wire:model="resourceType" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="pdf">📄 PDF</option>
                            <option value="video">🎬 Video Link</option>
                            <option value="image">🖼️ Image</option>
                            <option value="document">📝 Document</option>
                            <option value="link">🔗 External Link</option>
                        </select>
                    </div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($resourceType === 'link' || $resourceType === 'video'): ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">External URL</label>
                    <input wire:model="externalUrl" type="url" placeholder="https://..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <?php else: ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Upload File (max 50MB)</label>
                    <input wire:model="uploadedFile" type="file" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <div wire:loading wire:target="uploadedFile" class="text-xs text-gray-500 mt-1">Uploading...</div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="flex justify-end gap-3 p-5 border-t">
                <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button wire:click="upload" class="px-4 py-2 text-sm bg-green-700 text-white rounded-lg hover:bg-green-800">Upload Resource</button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\elimums\resources\views/livewire/notes/learning-notes-list.blade.php ENDPATH**/ ?>