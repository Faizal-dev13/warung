<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - <?php echo e(\App\Models\Setting::getValue('store_name', config('store.name'))); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Poppins','sans-serif']},boxShadow:{soft:'0 24px 80px rgba(15,23,42,.28)'}}}}</script>
</head>
<body class="min-h-screen bg-slate-950 font-sans text-white">
    <main class="grid min-h-screen overflow-hidden lg:grid-cols-[1fr_480px]">
        <section class="relative hidden p-10 lg:flex lg:flex-col lg:justify-between">
            <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full bg-teal-500/20 blur-3xl"></div>
            <div class="pointer-events-none absolute bottom-16 right-10 h-96 w-96 rounded-full bg-amber-400/10 blur-3xl"></div>
            <a href="<?php echo e(route('home')); ?>" class="relative flex w-fit items-center gap-3">
                <span class="grid h-12 w-12 place-items-center rounded-2xl bg-teal-600 shadow-lg shadow-teal-900/30"><i class="ph ph-storefront text-2xl"></i></span>
                <span><b class="block text-lg"><?php echo e(\App\Models\Setting::getValue('store_name', config('store.name'))); ?></b><small class="text-slate-400">Admin Panel</small></span>
            </a>
            <div class="relative max-w-2xl">
                <span class="inline-flex rounded-full border border-white/10 bg-white/10 px-4 py-2 text-xs font-extrabold uppercase tracking-[.2em] text-teal-100">Back Office</span>
                <h1 class="mt-6 text-5xl font-extrabold leading-tight">Kelola katalog dan pesanan dari satu tempat.</h1>
                <p class="mt-5 max-w-xl text-base leading-8 text-slate-300">Masuk untuk mengatur produk, promo, banner, dan order customer dengan tampilan yang rapi dan mudah digunakan.</p>
            </div>
            <p class="relative text-sm font-semibold text-slate-500">© <?php echo e(date('Y')); ?> <?php echo e(\App\Models\Setting::getValue('store_name', config('store.name'))); ?></p>
        </section>

        <section class="relative grid min-h-screen place-items-center p-4 sm:p-6">
            <div class="pointer-events-none fixed -right-24 -top-24 h-72 w-72 rounded-full bg-teal-500/20 blur-3xl lg:hidden"></div>
            <form method="POST" action="<?php echo e(route('admin.login.submit')); ?>" class="relative w-full max-w-md rounded-[2rem] border border-white/10 bg-white/[.08] p-5 shadow-soft backdrop-blur sm:p-7">
                <?php echo csrf_field(); ?>
                <div class="mb-7 text-center">
                    <div class="mx-auto grid h-16 w-16 place-items-center rounded-3xl bg-teal-600 text-white shadow-lg shadow-teal-900/30"><i class="ph ph-lock-key text-3xl"></i></div>
                    <h1 class="mt-4 text-2xl font-extrabold">Login Admin</h1>
                    <p class="mt-1 text-sm leading-6 text-white/60">Masuk untuk mengelola katalog dan pesanan.</p>
                </div>

                <?php if(session('error')): ?>
                    <div class="mb-4 rounded-2xl border border-red-300/20 bg-red-500/20 px-4 py-3 text-sm font-bold text-red-100"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                <?php if(session('success')): ?>
                    <div class="mb-4 rounded-2xl border border-emerald-300/20 bg-emerald-500/20 px-4 py-3 text-sm font-bold text-emerald-100"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <div class="grid gap-4">
                    <label><span class="mb-2 block text-sm font-bold">Email Admin</span><input name="email" type="email" value="<?php echo e(old('email')); ?>" placeholder="admin@email.com" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3.5 text-sm font-semibold outline-none transition placeholder:text-white/35 focus:border-teal-300/70 focus:bg-white/15" required autofocus></label>
                    <label><span class="mb-2 block text-sm font-bold">Password</span><input name="password" type="password" placeholder="Masukkan password" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3.5 text-sm font-semibold outline-none transition placeholder:text-white/35 focus:border-teal-300/70 focus:bg-white/15" required></label>
                    <button class="mt-2 inline-flex items-center justify-center gap-2 rounded-2xl bg-teal-500 px-5 py-3.5 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-teal-400 hover:shadow-xl">Masuk Dashboard <i class="ph ph-arrow-right"></i></button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
<?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/auth/login.blade.php ENDPATH**/ ?>