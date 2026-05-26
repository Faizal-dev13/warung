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
                    colors: {
                        brand: {
                            navy: '#0F172A',
                            teal: '#0F766E',
                            emerald: '#059669',
                            amber: '#F59E0B',
                            surface: '#F8FAFC'
                        }
                    },
                    boxShadow: {
                        soft: '0 18px 50px rgba(15,23,42,.08)',
                        card: '0 16px 40px rgba(15,23,42,.07)',
                        sidebar: '20px 0 60px rgba(15,23,42,.16)'
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

        function openAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar')
            const overlay = document.getElementById('adminSidebarOverlay')
            sidebar?.classList.remove('-translate-x-full')
            overlay?.classList.remove('hidden')
            document.body.classList.add('overflow-hidden')
        }

        function closeAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar')
            const overlay = document.getElementById('adminSidebarOverlay')
            sidebar?.classList.add('-translate-x-full')
            overlay?.classList.add('hidden')
            document.body.classList.remove('overflow-hidden')
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .admin-scrollbar::-webkit-scrollbar { height: 8px; width: 8px; }
        .admin-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .admin-scrollbar::-webkit-scrollbar-thumb { background: rgba(148,163,184,.58); border-radius: 999px; }
        .admin-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(100,116,139,.8); }

        .admin-card {
            border-radius: 1.5rem;
            border: 1px solid rgb(226 232 240);
            background: rgba(255,255,255,.94);
            box-shadow: 0 18px 50px rgba(15,23,42,.08);
        }
        .dark .admin-card {
            border-color: rgb(30 41 59);
            background: rgba(15,23,42,.94);
        }

        .admin-table-wrap {
            overflow: hidden;
            border-radius: 1.25rem;
            border: 1px solid rgb(226 232 240);
            background: #fff;
            box-shadow: 0 16px 40px rgba(15,23,42,.06);
        }
        .dark .admin-table-wrap {
            border-color: rgb(30 41 59);
            background: rgb(15 23 42);
        }

        .admin-input,
        .admin-select,
        .admin-textarea {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid rgb(203 213 225);
            background: #fff;
            padding: .75rem 1rem;
            font-size: .875rem;
            font-weight: 600;
            color: rgb(15 23 42);
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
        }
        .admin-textarea { min-height: 120px; }
        .admin-input:focus,
        .admin-select:focus,
        .admin-textarea:focus {
            border-color: #0F766E;
            box-shadow: 0 0 0 4px rgba(15,118,110,.12);
        }
        .dark .admin-input,
        .dark .admin-select,
        .dark .admin-textarea {
            border-color: rgb(51 65 85);
            background: rgb(15 23 42);
            color: #fff;
        }

        .admin-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            border-radius: 1rem;
            background: #0F766E;
            padding: .75rem 1rem;
            font-size: .875rem;
            font-weight: 800;
            color: #fff;
            box-shadow: 0 12px 26px rgba(15,118,110,.22);
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }
        .admin-btn-primary:hover { background: #0B665F; box-shadow: 0 16px 34px rgba(15,118,110,.28); transform: translateY(-1px); }

        .admin-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            border-radius: 1rem;
            border: 1px solid rgb(226 232 240);
            background: #fff;
            padding: .75rem 1rem;
            font-size: .875rem;
            font-weight: 800;
            color: rgb(51 65 85);
            transition: background .18s ease, border-color .18s ease, color .18s ease;
        }
        .admin-btn-secondary:hover { background: rgb(248 250 252); border-color: rgb(203 213 225); color: rgb(15 23 42); }
        .dark .admin-btn-secondary { border-color: rgb(51 65 85); background: rgb(15 23 42); color: rgb(226 232 240); }
        .dark .admin-btn-secondary:hover { background: rgb(30 41 59); color: #fff; }
    </style>
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="min-h-screen bg-brand-surface font-sans text-slate-900 antialiased selection:bg-teal-100 selection:text-teal-900 dark:bg-slate-950 dark:text-white dark:selection:bg-teal-500/30 dark:selection:text-white">
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

<div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
    <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-teal-200/35 blur-3xl dark:bg-teal-500/10"></div>
    <div class="absolute right-0 top-32 h-96 w-96 rounded-full bg-amber-100/45 blur-3xl dark:bg-amber-500/10"></div>
</div>

<div id="adminSidebarOverlay" onclick="closeAdminSidebar()" class="fixed inset-0 z-40 hidden bg-slate-950/50 backdrop-blur-sm lg:hidden"></div>

<aside id="adminSidebar" class="fixed inset-y-0 left-0 z-50 flex w-80 max-w-[86vw] -translate-x-full flex-col border-r border-white/10 bg-slate-950 text-white shadow-sidebar transition-transform duration-300 lg:w-72 lg:translate-x-0">
    <div class="flex items-center justify-between gap-3 border-b border-white/10 p-5">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3">
            <span class="grid h-12 w-12 place-items-center rounded-2xl bg-teal-600 text-white shadow-lg shadow-teal-900/30">
                <i class="ph ph-storefront text-2xl"></i>
            </span>
            <span class="leading-tight">
                <span class="block text-lg font-extrabold tracking-tight">DigitalKit</span>
                <span class="text-xs font-bold uppercase tracking-[.2em] text-teal-200/80">Admin</span>
            </span>
        </a>
        <button type="button" onclick="closeAdminSidebar()" class="grid h-10 w-10 place-items-center rounded-2xl border border-white/10 text-slate-300 transition hover:bg-white/10 hover:text-white lg:hidden" aria-label="Tutup menu">
            <i class="ph ph-x text-lg"></i>
        </button>
    </div>

    <div class="admin-scrollbar flex-1 overflow-y-auto px-4 py-5">
        <div class="rounded-3xl border border-white/10 bg-white/[.06] p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-2xl bg-amber-400 text-slate-950">
                    <i class="ph ph-sparkle text-lg"></i>
                </span>
                <div>
                    <p class="text-sm font-extrabold">Pusat Pengelolaan</p>
                    <p class="mt-0.5 text-xs font-medium text-slate-300">Katalog, promo, dan pesanan</p>
                </div>
            </div>
        </div>

        <nav class="mt-6 grid gap-1.5">
            <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$route,$icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isActive = request()->routeIs($route) || request()->routeIs(str_replace('.index','.*',$route));
                ?>
                <a href="<?php echo e(route($route)); ?>" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition <?php echo e($isActive ? 'bg-teal-600 text-white shadow-lg shadow-teal-950/30' : 'text-slate-300 hover:bg-white/10 hover:text-white'); ?>">
                    <span class="grid h-9 w-9 place-items-center rounded-xl <?php echo e($isActive ? 'bg-white/[.16] text-white' : 'bg-white/[.06] text-slate-300 group-hover:bg-white/10 group-hover:text-white'); ?>">
                        <i class="ph <?php echo e($icon); ?> text-lg"></i>
                    </span>
                    <span><?php echo e($label); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>
    </div>

    <div class="border-t border-white/10 p-4">
        <div class="mb-3 flex items-center justify-between rounded-2xl border border-white/10 bg-white/[.05] px-3 py-3">
            <div>
                <p class="text-xs font-bold text-slate-400">Tampilan</p>
                <p class="text-sm font-extrabold text-white">Mode layar</p>
            </div>
            <button type="button" onclick="toggleTheme()" class="grid h-10 w-10 place-items-center rounded-2xl bg-white text-slate-900 transition hover:bg-teal-50" aria-label="Ganti mode tampilan">
                <i class="ph ph-moon-stars text-lg"></i>
            </button>
        </div>

        <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
            <?php echo csrf_field(); ?>
            <button class="flex w-full items-center justify-center gap-2 rounded-2xl border border-red-400/25 bg-red-500/10 px-4 py-3 text-sm font-extrabold text-red-100 transition hover:bg-red-500 hover:text-white">
                <i class="ph ph-sign-out text-lg"></i> Logout
            </button>
        </form>
    </div>
</aside>

<div class="min-h-screen lg:pl-72">
    <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-brand-surface/90 backdrop-blur-xl dark:border-slate-800/80 dark:bg-slate-950/88 lg:hidden">
        <div class="flex items-center justify-between gap-3 px-4 py-3">
            <button type="button" onclick="openAdminSidebar()" class="grid h-11 w-11 place-items-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" aria-label="Buka menu">
                <i class="ph ph-list text-xl"></i>
            </button>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-2 font-extrabold">
                <span class="grid h-10 w-10 place-items-center rounded-2xl bg-teal-700 text-white"><i class="ph ph-storefront text-lg"></i></span>
                <span>DigitalKit</span>
            </a>
            <button type="button" onclick="toggleTheme()" class="grid h-11 w-11 place-items-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" aria-label="Ganti mode tampilan">
                <i class="ph ph-moon-stars text-lg"></i>
            </button>
        </div>
    </header>

    <main class="mx-auto w-full max-w-7xl px-4 py-5 sm:px-6 sm:py-7 lg:px-8 lg:py-8">
        <div class="mb-6 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white/92 p-5 shadow-soft backdrop-blur dark:border-slate-800 dark:bg-slate-900/85 sm:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0">
                    <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-teal-100 bg-teal-50 px-3 py-1 text-xs font-extrabold uppercase tracking-[.16em] text-teal-700 dark:border-teal-500/20 dark:bg-teal-500/10 dark:text-teal-200">
                        <span class="h-2 w-2 rounded-full bg-teal-600 dark:bg-teal-300"></span>
                        Panel Admin
                    </div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-3xl lg:text-4xl"><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></h1>
                    <?php if (! empty(trim($__env->yieldContent('page_description')))): ?>
                        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500 dark:text-slate-400"><?php echo $__env->yieldContent('page_description'); ?></p>
                    <?php else: ?>
                        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500 dark:text-slate-400">Pantau dan kelola data utama dengan tampilan yang ringkas, rapi, dan mudah digunakan.</p>
                    <?php endif; ?>
                </div>
                <div class="flex shrink-0 flex-wrap items-center gap-2">
                    <?php echo $__env->yieldContent('page_action'); ?>
                </div>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="mb-4 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 shadow-sm dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                <i class="ph ph-check-circle mt-0.5 text-xl"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-4 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700 shadow-sm dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-200">
                <i class="ph ph-warning-circle mt-0.5 text-xl"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-200">
                <div class="flex items-center gap-2 font-extrabold">
                    <i class="ph ph-warning-circle text-lg"></i>
                    <span>Periksa kembali data berikut:</span>
                </div>
                <ul class="mt-2 list-disc space-y-1 pl-6 font-semibold">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <section class="pb-8">
            <?php echo $__env->yieldContent('content'); ?>
        </section>
    </main>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/layouts/app.blade.php ENDPATH**/ ?>