<?php $__env->startSection('page_title','Order'); ?>
<?php $__env->startSection('page_description','Pantau pesanan masuk, data customer, total pembayaran, dan status follow-up.'); ?>
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
        'processed'=>'bg-sky-50 text-sky-700 dark:bg-sky-400/10 dark:text-sky-300',
        'completed'=>'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300',
        'cancelled'=>'bg-red-50 text-red-700 dark:bg-red-400/10 dark:text-red-300',
    ];
?>
<form method="GET" action="<?php echo e(route('admin.orders.index')); ?>" class="mb-5 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <input type="hidden" name="sort" value="<?php echo e($filters['sort'] ?? 'created_at'); ?>">
    <input type="hidden" name="direction" value="<?php echo e($filters['direction'] ?? 'desc'); ?>">
    <div class="grid gap-3 lg:grid-cols-[1fr_220px_140px_auto]">
        <label class="relative block">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input name="q" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Cari invoice, nama, atau nomor HP..." class="admin-input h-12 pl-11">
        </label>
        <select name="status" class="admin-select h-12">
            <option value="" <?php if(($filters['status'] ?? '') === ''): echo 'selected'; endif; ?>>Semua status</option>
            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(($filters['status'] ?? '') === $status): echo 'selected'; endif; ?>><?php echo e($statusLabels[$status] ?? str_replace('_',' ',$status)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="per_page" class="admin-select h-12">
            <?php $__currentLoopData = [10,25,50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($size); ?>" <?php if(($filters['per_page'] ?? 10) == $size): echo 'selected'; endif; ?>><?php echo e($size); ?> data</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <div class="grid grid-cols-2 gap-2 sm:flex">
            <button class="admin-btn-primary h-12"><i class="ph ph-funnel"></i> Terapkan</button>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="admin-btn-secondary h-12">Reset</a>
        </div>
    </div>
</form>

<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="flex flex-col gap-3 border-b border-slate-100 p-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-extrabold">Daftar Order</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Urutkan dan filter order sesuai kebutuhan follow-up.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300"><?php echo e($orders->total()); ?> order</span>
    </div>

    <div class="grid gap-3 p-4 lg:hidden">
        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-extrabold text-slate-950 dark:text-white"><?php echo e($order->invoice_number); ?></p>
                        <p class="mt-1 text-sm font-semibold text-slate-600 dark:text-slate-300"><?php echo e($order->customer_name); ?></p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400"><?php echo e($order->customer_phone ?: '-'); ?></p>
                    </div>
                    <span class="shrink-0 rounded-full px-3 py-1 text-[11px] font-extrabold <?php echo e($statusClasses[$order->status] ?? 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'); ?>"><?php echo e($statusLabels[$order->status] ?? str_replace('_',' ',$order->status)); ?></span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3 rounded-2xl bg-white p-3 text-sm dark:bg-slate-900">
                    <div><p class="text-xs font-bold text-slate-400">Item</p><p class="mt-1 font-extrabold"><?php echo e($order->items_count); ?> item</p></div>
                    <div><p class="text-xs font-bold text-slate-400">Total</p><p class="mt-1 font-extrabold">Rp <?php echo e(number_format($order->total,0,',','.')); ?></p></div>
                </div>
                <div class="mt-4 flex items-center justify-between gap-3">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400"><?php echo e($order->created_at->format('d M Y H:i')); ?></p>
                    <a href="<?php echo e(route('admin.orders.show',$order)); ?>" class="inline-flex items-center gap-1 rounded-2xl bg-teal-700 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-teal-800 dark:bg-teal-500 dark:hover:bg-teal-400">Detail <i class="ph ph-arrow-right"></i></a>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-center dark:border-slate-700">
                <i class="ph ph-receipt text-4xl text-slate-400"></i>
                <p class="mt-3 font-extrabold">Order belum tersedia</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Order baru akan muncul setelah customer checkout.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="admin-scrollbar hidden overflow-x-auto lg:block">
        <table class="w-full min-w-[980px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4"><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'invoice', 'label' => 'Invoice', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'customer', 'label' => 'Customer', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'items', 'label' => 'Item', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'total', 'label' => 'Total', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'status', 'label' => 'Status', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'created_at', 'label' => 'Tanggal', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th class="pr-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                    <td class="px-5 py-4 font-extrabold text-slate-950 dark:text-white"><?php echo e($order->invoice_number); ?></td>
                    <td><b><?php echo e($order->customer_name); ?></b><br><span class="text-xs text-slate-500"><?php echo e($order->customer_phone ?: '-'); ?></span></td>
                    <td class="font-semibold"><?php echo e($order->items_count); ?> item</td>
                    <td class="font-extrabold">Rp <?php echo e(number_format($order->total,0,',','.')); ?></td>
                    <td><span class="rounded-full px-3 py-1 text-xs font-extrabold <?php echo e($statusClasses[$order->status] ?? 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'); ?>"><?php echo e($statusLabels[$order->status] ?? str_replace('_',' ',$order->status)); ?></span></td>
                    <td class="text-slate-500 dark:text-slate-400"><?php echo e($order->created_at->format('d M Y H:i')); ?></td>
                    <td class="pr-5 text-right"><a href="<?php echo e(route('admin.orders.show',$order)); ?>" class="inline-flex items-center gap-1 rounded-xl bg-teal-700 px-3 py-2 text-xs font-extrabold text-white transition hover:bg-teal-800 dark:bg-teal-500 dark:hover:bg-teal-400">Detail <i class="ph ph-arrow-right"></i></a></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php echo $__env->make('admin.partials.empty-state', ['colspan' => 7, 'icon' => 'ph-receipt', 'title' => 'Order belum tersedia', 'description' => 'Order baru akan muncul setelah customer melakukan checkout.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php echo $__env->make('admin.partials.pagination', ['paginator' => $orders], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/orders/index.blade.php ENDPATH**/ ?>