<?php $__env->startSection('page_title','Detail Order '.$order->invoice_number); ?>
<?php $__env->startSection('page_description','Cek ringkasan pesanan, data customer, lalu update status agar proses follow-up lebih jelas.'); ?>
<?php $__env->startSection('page_action'); ?><a href="<?php echo e(route('admin.orders.index')); ?>" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-extrabold dark:border-slate-700"><i class="ph ph-arrow-left"></i> Kembali</a><?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php
    $statusLabels = [
        'waiting_whatsapp_confirmation' => 'Menunggu WA',
        'confirmed' => 'Dikonfirmasi',
        'processed' => 'Diproses',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];
?>
<div class="grid gap-5 lg:grid-cols-[1fr_380px]">
    <section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-100 p-5 dark:border-slate-800"><h2 class="text-lg font-extrabold">Item Pesanan</h2><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Produk yang dipilih customer.</p></div>
        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-start justify-between gap-4 p-5">
                    <div><p class="font-extrabold text-slate-950 dark:text-white"><?php echo e($item->product_name); ?></p><p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><?php echo e($item->quantity); ?> x Rp <?php echo e(number_format($item->price,0,',','.')); ?></p></div>
                    <b class="text-right">Rp <?php echo e(number_format($item->subtotal,0,',','.')); ?></b>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="border-t border-slate-100 p-5 text-sm dark:border-slate-800">
            <div class="ml-auto max-w-sm space-y-3">
                <div class="flex justify-between gap-5"><span class="text-slate-500 dark:text-slate-400">Subtotal</span><b>Rp <?php echo e(number_format($order->subtotal,0,',','.')); ?></b></div>
                <div class="flex justify-between gap-5"><span class="text-slate-500 dark:text-slate-400">Diskon</span><b class="text-red-500">- Rp <?php echo e(number_format($order->discount,0,',','.')); ?></b></div>
                <div class="flex justify-between gap-5 rounded-2xl bg-slate-950 p-4 text-base text-white dark:bg-white dark:text-slate-950"><span>Total</span><b>Rp <?php echo e(number_format($order->total,0,',','.')); ?></b></div>
            </div>
        </div>
    </section>

    <aside class="space-y-5">
        <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-lg font-extrabold">Data Customer</h2>
            <div class="mt-4 grid gap-3 text-sm">
                <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60"><span class="text-xs font-bold text-slate-400">Nama</span><p class="mt-1 font-extrabold"><?php echo e($order->customer_name); ?></p></div>
                <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60"><span class="text-xs font-bold text-slate-400">No HP</span><p class="mt-1 font-extrabold"><?php echo e($order->customer_phone ?: '-'); ?></p></div>
                <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60"><span class="text-xs font-bold text-slate-400">Voucher</span><p class="mt-1 font-extrabold"><?php echo e($order->voucher_code ?: '-'); ?></p></div>
                <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60"><span class="text-xs font-bold text-slate-400">Catatan</span><p class="mt-1 leading-6"><?php echo e($order->note ?: '-'); ?></p></div>
            </div>
            <a target="_blank" href="https://wa.me/<?php echo e(preg_replace('/\D+/', '', $order->customer_phone ?? '')); ?>" class="mt-4 flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-emerald-700"><i class="ph-fill ph-whatsapp-logo"></i> Chat Customer</a>
        </section>

        <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-lg font-extrabold">Update Status</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih status yang paling sesuai dengan kondisi pesanan.</p>
            <form method="POST" action="<?php echo e(route('admin.orders.status',$order)); ?>" class="mt-4"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <select name="status" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none dark:border-slate-700 dark:bg-slate-800"><?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if($order->status===$status): echo 'selected'; endif; ?>><?php echo e($statusLabels[$status] ?? str_replace('_',' ',$status)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
                <button class="mt-3 w-full rounded-2xl bg-teal-700 px-5 py-3 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-teal-800 dark:bg-teal-500 dark:hover:bg-teal-400 dark:text-white">Simpan Status</button>
            </form>
        </section>
    </aside>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/orders/show.blade.php ENDPATH**/ ?>