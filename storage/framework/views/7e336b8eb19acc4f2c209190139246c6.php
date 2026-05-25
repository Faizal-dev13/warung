<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Poppins','sans-serif']}}}}</script>
</head>
<body class="min-h-screen bg-slate-950 font-sans text-white">
    <main class="grid min-h-screen place-items-center overflow-hidden p-4">
        <div class="pointer-events-none fixed -left-24 -top-24 h-72 w-72 rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="pointer-events-none fixed -bottom-24 -right-24 h-72 w-72 rounded-full bg-emerald-500/20 blur-3xl"></div>

        <form method="POST" action="<?php echo e(route('admin.login.submit')); ?>" class="relative w-full max-w-md rounded-[2rem] border border-white/10 bg-white/10 p-6 shadow-2xl backdrop-blur"><?php echo csrf_field(); ?>
            <div class="mb-6 flex items-center gap-3">
                <div class="grid h-14 w-14 place-items-center rounded-2xl bg-white text-slate-950 shadow-lg"><i class="ph ph-lock-key text-2xl"></i></div>
                <div>
                    <h1 class="text-2xl font-extrabold">Login Admin</h1>
                    <p class="mt-1 text-sm text-white/60">Masuk untuk mengelola katalog dan pesanan.</p>
                </div>
            </div>

            <?php if(session('error')): ?>
                <div class="mb-4 rounded-2xl border border-red-300/20 bg-red-500/20 px-4 py-3 text-sm font-bold text-red-100"><?php echo e(session('error')); ?></div>
            <?php endif; ?>
            <?php if(session('success')): ?>
                <div class="mb-4 rounded-2xl border border-emerald-300/20 bg-emerald-500/20 px-4 py-3 text-sm font-bold text-emerald-100"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <div class="grid gap-4">
                <label><span class="mb-2 block text-sm font-bold">Email Admin</span><input name="email" type="email" value="<?php echo e(old('email')); ?>" placeholder="admin@email.com" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm font-semibold outline-none transition placeholder:text-white/35 focus:border-white/40 focus:bg-white/15" required autofocus></label>
                <label><span class="mb-2 block text-sm font-bold">Password</span><input name="password" type="password" placeholder="Masukkan password" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm font-semibold outline-none transition placeholder:text-white/35 focus:border-white/40 focus:bg-white/15" required></label>
                <button class="mt-2 inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3.5 text-sm font-extrabold text-slate-950 transition hover:-translate-y-0.5 hover:shadow-xl">Masuk Dashboard <i class="ph ph-arrow-right"></i></button>
            </div>
        </form>
    </main>
</body>
</html>
<?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/auth/login.blade.php ENDPATH**/ ?>