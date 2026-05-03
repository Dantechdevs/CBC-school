<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('school.name')); ?> — <?php echo e($title ?? config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-green-800 shadow-lg transform transition-transform duration-300" id="sidebar">
            <div class="flex items-center justify-between h-16 px-6 bg-green-900">
                <span class="text-white font-bold text-lg truncate"><?php echo e(config('school.name')); ?></span>
            </div>
            <nav class="mt-4 px-3">
                <?php echo e($navigation ?? ''); ?>

            </nav>
        </aside>

        
        <div class="pl-64">
            <header class="bg-white shadow h-16 flex items-center px-6 justify-between">
                <h1 class="text-xl font-semibold text-gray-800"><?php echo e($header ?? 'Dashboard'); ?></h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500"><?php echo e(config('school.name')); ?></span>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-sm text-red-600 hover:underline">Logout</button>
                    </form>
                </div>
            </header>
            <main class="p-6">
                <?php echo e($slot); ?>

            </main>
        </div>
    </div>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\CBC school\resources\views/layouts/app.blade.php ENDPATH**/ ?>