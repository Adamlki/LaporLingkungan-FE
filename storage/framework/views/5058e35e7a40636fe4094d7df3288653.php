<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'LAPOR LINGKUNGAN')); ?></title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
        
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    
    
    <body class="font-sans antialiased"> 
        <div class="min-h-screen bg-gray-50">
            
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php if(isset($header)): ?>
                <header class="bg-white border-b border-gray-100 shadow-md"> 
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <main>
                <?php echo e($slot); ?>

            </main>
            
            <footer class="bg-white border-t border-gray-100 shadow-inner mt-12 py-6 text-center text-sm text-gray-500"> 
                <div class="max-w-7xl mx-auto">
                    &copy; <?php echo e(date('Y')); ?> Lapor Lingkungan | Proyek Praktikum Web.
                </div>
            </footer>
        </div>
    </body>
</html><?php /**PATH C:\laragon\www\ProjectWEB\LaporLingkungan-FE\resources\views/layouts/app.blade.php ENDPATH**/ ?>