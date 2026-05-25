<?php $__env->startSection('page_title','Dashboard'); ?>
<?php $__env->startSection('page_description','Ringkasan performa toko hari ini. Admin bisa langsung cek produk, promo aktif, dan pesanan terbaru dari halaman ini.'); ?>
<?php $__env->startSection('content'); ?>
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
<?php $__currentLoopData = [
    ['Total Produk',$totalProducts,'ph-package','Produk yang terdaftar','bg-blue-50 text-blue-600 dark:bg-blue-400/10 dark:text-blue-300'],
    ['Kategori',$totalCategories,'ph-folders','Pengelompokan katalog','bg-violet-50 text-violet-600 dark:bg-violet-400/10 dark:text-violet-300'],
    ['Total Order',$totalOrders,'ph-receipt','Semua pesanan masuk','bg-amber-50 text-amber-600 dark:bg-amber-400/10 dark:text-amber-300'],
    ['Perlu Follow-up',$pendingOrders,'ph-whatsapp-logo','Menunggu konfirmasi WA','bg-emerald-50 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-300']
]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$value,$icon,$desc,$tone]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400"><?php echo e($label); ?></p>
                <p class="mt-2 text-3xl font-extrabold tracking-tight"><?php echo e($value); ?></p>
                <p class="mt-1 text-xs font-semibold text-slate-400"><?php echo e($desc); ?></p>
            </div>
            <span class="grid h-12 w-12 place-items-center rounded-2xl <?php echo e($tone); ?>"><i class="ph <?php echo e($icon); ?> text-2xl"></i></span>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="mt-6 grid gap-5 xl:grid-cols-[1fr_320px]">
    <section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900" data-admin-table>
        <div class="border-b border-slate-100 p-5 dark:border-slate-800">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-extrabold">Order terbaru</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pantau pesanan terakhir dan lanjutkan follow-up customer.</p>
                </div>
                <label class="relative block w-full md:max-w-xs">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input data-datatable-search placeholder="Cari di tabel..." class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40">
                </label>
            </div>
        </div>
        <div class="admin-scrollbar overflow-x-auto">
            <table class="w-full min-w-[780px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                    <tr>
                        <th data-sortable class="px-5 py-4">Invoice <i class="sort-icon ph ph-caret-up-down ml-1"></i></th>
                        <th data-sortable>Customer <i class="sort-icon ph ph-caret-up-down ml-1"></i></th>
                        <th data-sortable>Total <i class="sort-icon ph ph-caret-up-down ml-1"></i></th>
                        <th>Status</th>
                        <th data-sortable>Tanggal <i class="sort-icon ph ph-caret-up-down ml-1"></i></th>
                        <th class="pr-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php $__empty_1 = true; $__currentLoopData = $latestOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="px-5 py-4 font-extrabold"><a class="text-slate-950 hover:text-blue-600 dark:text-white" href="<?php echo e(route('admin.orders.show',$order)); ?>"><?php echo e($order->invoice_number); ?></a></td>
                            <td><b><?php echo e($order->customer_name); ?></b></td>
                            <td class="font-bold">Rp <?php echo e(number_format($order->total,0,',','.')); ?></td>
                            <td><span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-extrabold capitalize text-amber-700 dark:bg-amber-400/10 dark:text-amber-300"><?php echo e(str_replace('_',' ',$order->status)); ?></span></td>
                            <td class="text-slate-500 dark:text-slate-400"><?php echo e($order->created_at->format('d M Y H:i')); ?></td>
                            <td class="pr-5 text-right"><a href="<?php echo e(route('admin.orders.show',$order)); ?>" class="inline-flex items-center gap-1 rounded-xl bg-slate-950 px-3 py-2 text-xs font-extrabold text-white dark:bg-white dark:text-slate-950">Detail <i class="ph ph-arrow-right"></i></a></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr data-empty-row><td colspan="6" class="px-5 py-10 text-center text-slate-500">Belum ada order.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="flex flex-col gap-3 border-t border-slate-100 p-4 text-sm dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400"><span data-datatable-info></span></div>
            <div class="flex items-center gap-2">
                <select data-datatable-length class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold dark:border-slate-700 dark:bg-slate-900"><option value="5">5 baris</option><option value="10" selected>10 baris</option></select>
                <button type="button" data-datatable-prev class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold disabled:opacity-40 dark:border-slate-700">Prev</button>
                <button type="button" data-datatable-next class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold disabled:opacity-40 dark:border-slate-700">Next</button>
            </div>
        </div>
    </section>

    <aside class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <h2 class="text-lg font-extrabold">Aksi Cepat</h2>
        <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">Gunakan menu ini untuk update konten toko tanpa masuk ke pengaturan teknis.</p>
        <div class="mt-5 grid gap-3">
            <a href="<?php echo e(route('admin.products.create')); ?>" class="flex items-center justify-between rounded-2xl border border-slate-200 p-4 text-sm font-extrabold transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"><span><i class="ph ph-plus-circle mr-2"></i>Tambah Produk</span><i class="ph ph-arrow-right"></i></a>
            <a href="<?php echo e(route('admin.vouchers.create')); ?>" class="flex items-center justify-between rounded-2xl border border-slate-200 p-4 text-sm font-extrabold transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"><span><i class="ph ph-ticket mr-2"></i>Buat Voucher</span><i class="ph ph-arrow-right"></i></a>
            <a href="<?php echo e(route('admin.banners.create')); ?>" class="flex items-center justify-between rounded-2xl border border-slate-200 p-4 text-sm font-extrabold transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"><span><i class="ph ph-images mr-2"></i>Atur Banner</span><i class="ph ph-arrow-right"></i></a>
        </div>
    </aside>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>