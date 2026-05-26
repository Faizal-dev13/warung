<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?> - DigitalKit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    boxShadow: {
                        soft: '0 18px 50px rgba(15,23,42,.08)',
                        card: '0 16px 40px rgba(15,23,42,.07)'
                    }
                }
            }
        }
    </script>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        }
        function toggleTheme() {
            document.documentElement.classList.toggle('dark')
            localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light'
        }
    </script>
    <style>
        .admin-scrollbar::-webkit-scrollbar { height: 8px; width: 8px; }
        .admin-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .admin-scrollbar::-webkit-scrollbar-thumb { background: rgba(148,163,184,.55); border-radius: 999px; }
        .admin-card { border-radius: 1.5rem; border: 1px solid rgb(226 232 240); background: #fff; box-shadow: 0 18px 50px rgba(15,23,42,.08); }
        .dark .admin-card { border-color: rgb(30 41 59); background: rgb(15 23 42); }
    </style>
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="bg-[#F8FAFC] font-sans text-slate-900 antialiased dark:bg-slate-950 dark:text-white">
<div class="min-h-screen lg:flex">
    <aside class="border-b border-slate-200 bg-white/95 p-4 backdrop-blur dark:border-slate-800 dark:bg-slate-900/90 lg:fixed lg:inset-y-0 lg:w-72 lg:border-b-0 lg:border-r">
        <div class="flex items-center justify-between gap-3">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 font-extrabold">
                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-teal-700 text-white shadow-sm dark:bg-teal-500 dark:text-white"><i class="ph ph-storefront text-xl"></i></span>
                <span class="leading-tight">DigitalKit<br><span class="text-xs font-bold text-slate-400">Back Office</span></span>
            </a>
            <button type="button" onclick="toggleTheme()" class="grid h-10 w-10 place-items-center rounded-2xl border border-slate-200 text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800" aria-label="Ganti mode tampilan"><i class="ph ph-moon-stars"></i></button>
        </div>
        <?php
            $menus = [
                ['Dashboard','admin.dashboard','ph-gauge'],
                ['Kategori','admin.categories.index','ph-folders'],
                ['Produk','admin.products.index','ph-package'],
                ['Voucher','admin.vouchers.index','ph-ticket'],
                ['Banner','admin.banners.index','ph-images'],
                ['Order','admin.orders.index','ph-receipt'],
            ];
        ?>
        <nav class="mt-7 grid gap-2">
            <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$route,$icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route($route)); ?>" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition <?php echo e(request()->routeIs($route) || request()->routeIs(str_replace('.index','.*',$route)) ? 'bg-teal-700 text-white shadow-lg shadow-teal-700/20 dark:bg-teal-500 dark:text-white' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800'); ?>">
                    <i class="ph <?php echo e($icon); ?> text-lg"></i><?php echo e($label); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>
        <form method="POST" action="<?php echo e(route('admin.logout')); ?>" class="mt-7"><?php echo csrf_field(); ?>
            <button class="flex w-full items-center justify-center gap-2 rounded-2xl border border-red-200 px-4 py-3 text-sm font-bold text-red-600 transition hover:bg-red-50 dark:border-red-900/50 dark:hover:bg-red-950/30"><i class="ph ph-sign-out"></i> Logout</button>
        </form>
    </aside>

    <main class="w-full p-4 sm:p-6 lg:ml-72 lg:p-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400">Kelola katalog, promo, banner, dan order</p>
                <h1 class="mt-1 text-2xl font-extrabold tracking-tight sm:text-3xl"><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></h1>
                <?php if (! empty(trim($__env->yieldContent('page_description')))): ?>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500 dark:text-slate-400"><?php echo $__env->yieldContent('page_description'); ?></p>
                <?php endif; ?>
            </div>
            <?php echo $__env->yieldContent('page_action'); ?>
        </div>

        <?php if(session('success')): ?>
            <div class="mb-4 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300"><i class="ph ph-check-circle mt-0.5 text-lg"></i><span><?php echo e(session('success')); ?></span></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="mb-4 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"><i class="ph ph-warning-circle mt-0.5 text-lg"></i><span><?php echo e(session('error')); ?></span></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
                <b>Periksa kembali data berikut:</b>
                <ul class="mt-2 list-disc pl-5"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/layouts/app.blade.php ENDPATH**/ ?>