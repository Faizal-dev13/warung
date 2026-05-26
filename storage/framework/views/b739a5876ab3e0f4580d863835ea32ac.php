<?php $__env->startSection('page_title','Dashboard'); ?>
<?php $__env->startSection('page_description','Ringkasan toko untuk memantau produk, promo, banner, dan order terbaru.'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $statusLabels = [
        'waiting_whatsapp_confirmation'=>'Menunggu WA',
        'confirmed'=>'Dikonfirmasi',
        'processed'=>'Diproses',
        'completed'=>'Selesai',
        'cancelled'=>'Dibatalkan',
    ];
    $statusClasses = [
        'waiting_whatsapp_confirmation'=>'bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300',
        'confirmed'=>'bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-300',
        'processed'=>'bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300',
        'completed'=>'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300',
        'cancelled'=>'bg-red-50 text-red-700 dark:bg-red-400/10 dark:text-red-300',
    ];
?>
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
<?php $__currentLoopData = [
    ['Total Produk',$totalProducts,'ph-package','Produk terdaftar','bg-teal-50 text-teal-600 dark:bg-teal-400/10 dark:text-teal-300'],
    ['Kategori',$totalCategories,'ph-folders','Kelompok katalog','bg-amber-50 text-amber-600 dark:bg-amber-400/10 dark:text-amber-300'],
    ['Total Order',$totalOrders,'ph-receipt','Semua pesanan masuk','bg-amber-50 text-amber-600 dark:bg-amber-400/10 dark:text-amber-300'],
    ['Perlu Follow-up',$pendingOrders,'ph-whatsapp-logo','Menunggu konfirmasi','bg-emerald-50 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-300']
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
    <section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-100 p-5 dark:border-slate-800">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-extrabold">Order Terbaru</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pantau pesanan terakhir dan lanjutkan follow-up customer.</p>
                </div>
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 px-4 py-2.5 text-xs font-extrabold transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800">Lihat Semua <i class="ph ph-arrow-right"></i></a>
            </div>
        </div>
        <div class="admin-scrollbar overflow-x-auto">
            <table class="w-full min-w-[780px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-4">Invoice</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="pr-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php $__empty_1 = true; $__currentLoopData = $latestOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="px-5 py-4 font-extrabold"><a class="text-slate-950 hover:text-teal-600 dark:text-white" href="<?php echo e(route('admin.orders.show',$order)); ?>"><?php echo e($order->invoice_number); ?></a></td>
                            <td><b><?php echo e($order->customer_name); ?></b></td>
                            <td class="font-bold">Rp <?php echo e(number_format($order->total,0,',','.')); ?></td>
                            <td><span class="rounded-full px-3 py-1 text-xs font-extrabold <?php echo e($statusClasses[$order->status] ?? 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'); ?>"><?php echo e($statusLabels[$order->status] ?? str_replace('_',' ',$order->status)); ?></span></td>
                            <td class="text-slate-500 dark:text-slate-400"><?php echo e($order->created_at->format('d M Y H:i')); ?></td>
                            <td class="pr-5 text-right"><a href="<?php echo e(route('admin.orders.show',$order)); ?>" class="inline-flex items-center gap-1 rounded-xl bg-teal-700 px-3 py-2 text-xs font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-teal-800 dark:bg-teal-500 dark:hover:bg-teal-400 dark:text-white">Detail <i class="ph ph-arrow-right"></i></a></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php echo $__env->make('admin.partials.empty-state', ['colspan' => 6, 'icon' => 'ph-receipt', 'title' => 'Belum ada order', 'description' => 'Order terbaru akan muncul di sini.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <aside class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <h2 class="text-lg font-extrabold">Aksi Cepat</h2>
        <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">Gunakan menu ini untuk memperbarui konten toko dengan cepat.</p>
        <div class="mt-5 grid gap-3">
            <a href="<?php echo e(route('admin.products.create')); ?>" class="flex items-center justify-between rounded-2xl border border-slate-200 p-4 text-sm font-extrabold transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"><span><i class="ph ph-plus-circle mr-2"></i>Tambah Produk</span><i class="ph ph-arrow-right"></i></a>
            <a href="<?php echo e(route('admin.vouchers.create')); ?>" class="flex items-center justify-between rounded-2xl border border-slate-200 p-4 text-sm font-extrabold transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"><span><i class="ph ph-ticket mr-2"></i>Buat Voucher</span><i class="ph ph-arrow-right"></i></a>
            <a href="<?php echo e(route('admin.banners.create')); ?>" class="flex items-center justify-between rounded-2xl border border-slate-200 p-4 text-sm font-extrabold transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"><span><i class="ph ph-images mr-2"></i>Atur Banner</span><i class="ph ph-arrow-right"></i></a>
        </div>
        <div class="mt-5 rounded-3xl bg-slate-50 p-4 text-sm leading-6 text-slate-500 dark:bg-slate-800/60 dark:text-slate-400">
            Pastikan foto produk dan banner sudah rapi agar halaman depan terlihat profesional.
        </div>
    </aside>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>