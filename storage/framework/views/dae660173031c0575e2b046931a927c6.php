<?php $__env->startSection('page_title','Varian Produk'); ?>
<?php $__env->startSection('page_description','Kelola pilihan produk seperti durasi, paket, atau opsi harga yang bisa dipilih customer.'); ?>
<?php $__env->startSection('page_action'); ?>
<a href="<?php echo e(route('admin.variants.create')); ?>" class="admin-btn-primary w-full sm:w-auto"><i class="ph ph-plus-circle"></i> Tambah Varian</a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<section class="admin-card overflow-hidden">
    <div class="border-b border-slate-200 p-4 dark:border-slate-800 sm:p-5">
        <form method="GET" action="<?php echo e(route('admin.variants.index')); ?>" class="grid gap-3 lg:grid-cols-[1.4fr_1fr_.8fr_.7fr_auto]">
            <label class="block">
                <span class="sr-only">Cari varian</span>
                <div class="relative">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input name="q" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Cari nama varian, produk, SKU..." class="admin-input pl-11">
                </div>
            </label>
            <label class="block">
                <span class="sr-only">Produk</span>
                <select name="product_id" class="admin-select">
                    <option value="">Semua produk</option>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($product->id); ?>" <?php if(($filters['product_id'] ?? '') == $product->id): echo 'selected'; endif; ?>><?php echo e($product->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>
            <label class="block">
                <span class="sr-only">Status</span>
                <select name="status" class="admin-select">
                    <option value="">Semua status</option>
                    <option value="active" <?php if(($filters['status'] ?? '') === 'active'): echo 'selected'; endif; ?>>Aktif</option>
                    <option value="inactive" <?php if(($filters['status'] ?? '') === 'inactive'): echo 'selected'; endif; ?>>Nonaktif</option>
                </select>
            </label>
            <label class="block">
                <span class="sr-only">Jumlah data</span>
                <select name="per_page" class="admin-select">
                    <?php $__currentLoopData = [10,25,50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($size); ?>" <?php if(($filters['per_page'] ?? 10) == $size): echo 'selected'; endif; ?>><?php echo e($size); ?> data</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>
            <div class="grid grid-cols-2 gap-2 sm:flex lg:justify-end">
                <button class="admin-btn-primary"><i class="ph ph-funnel"></i> Terapkan</button>
                <a href="<?php echo e(route('admin.variants.index')); ?>" class="admin-btn-secondary"><i class="ph ph-arrow-counter-clockwise"></i> Reset</a>
            </div>
        </form>
    </div>

    <div class="grid gap-3 p-4 lg:hidden">
        <?php $__empty_1 = true; $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="line-clamp-2 font-extrabold text-slate-950 dark:text-white"><?php echo e($variant->name); ?></p>
                            <p class="mt-1 text-xs font-semibold text-teal-700 dark:text-teal-300"><?php echo e($variant->product?->name ?? '-'); ?></p>
                        </div>
                        <span class="shrink-0 rounded-full px-3 py-1 text-[11px] font-extrabold <?php echo e($variant->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-200 text-slate-500 dark:bg-slate-800 dark:text-slate-400'); ?>"><?php echo e($variant->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/60">
                            <span class="block text-[10px] uppercase tracking-wide">Harga</span>
                            <b class="mt-1 block text-sm text-slate-950 dark:text-white">Rp <?php echo e(number_format($variant->price,0,',','.')); ?></b>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/60">
                            <span class="block text-[10px] uppercase tracking-wide">Stok</span>
                            <b class="mt-1 block text-sm text-slate-950 dark:text-white"><?php echo e($variant->stock === null ? 'Tidak dibatasi' : number_format($variant->stock,0,',','.')); ?></b>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/60">
                            <span class="block text-[10px] uppercase tracking-wide">Durasi</span>
                            <b class="mt-1 block text-sm text-slate-950 dark:text-white"><?php echo e($variant->duration ?: '-'); ?></b>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/60">
                            <span class="block text-[10px] uppercase tracking-wide">Urutan</span>
                            <b class="mt-1 block text-sm text-slate-950 dark:text-white"><?php echo e($variant->sort_order); ?></b>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 border-t border-slate-200 p-3 dark:border-slate-800">
                    <a class="admin-btn-secondary h-11 text-xs" href="<?php echo e(route('admin.variants.edit',$variant)); ?>"><i class="ph ph-pencil-simple"></i> Edit</a>
                    <form method="POST" action="<?php echo e(route('admin.variants.destroy',$variant)); ?>" onsubmit="return confirm('Hapus varian ini?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-2xl border border-red-200 bg-white px-3 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:bg-slate-900 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button>
                    </form>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-center dark:border-slate-700">
                <i class="ph ph-stack text-4xl text-slate-400"></i>
                <p class="mt-3 font-extrabold">Varian belum tersedia</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tambahkan varian untuk produk yang memiliki pilihan paket atau durasi.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="admin-scrollbar hidden overflow-x-auto lg:block">
        <table class="w-full min-w-[1080px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4"><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'name', 'label' => 'Varian', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'product', 'label' => 'Produk', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'price', 'label' => 'Harga', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'stock', 'label' => 'Stok', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th>Durasi</th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'status', 'label' => 'Status', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'sort_order', 'label' => 'Urutan', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th class="pr-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <?php $__empty_1 = true; $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                    <td class="px-5 py-4">
                        <div class="font-extrabold text-slate-950 dark:text-white"><?php echo e($variant->name); ?></div>
                        <div class="mt-1 text-xs text-slate-500 dark:text-slate-400"><?php echo e($variant->sku ?: 'Tanpa SKU'); ?></div>
                    </td>
                    <td><span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-extrabold text-teal-700 dark:bg-teal-400/10 dark:text-teal-300"><?php echo e($variant->product?->name ?? '-'); ?></span></td>
                    <td class="font-extrabold">Rp <?php echo e(number_format($variant->price,0,',','.')); ?></td>
                    <td class="font-semibold"><?php echo e($variant->stock === null ? 'Tidak dibatasi' : number_format($variant->stock,0,',','.')); ?></td>
                    <td class="font-semibold text-slate-500 dark:text-slate-400"><?php echo e($variant->duration ?: '-'); ?></td>
                    <td><span class="rounded-full px-3 py-1 text-xs font-extrabold <?php echo e($variant->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'); ?>"><?php echo e($variant->is_active ? 'Aktif' : 'Nonaktif'); ?></span></td>
                    <td class="font-semibold"><?php echo e($variant->sort_order); ?></td>
                    <td class="pr-5 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a class="admin-btn-secondary px-3 py-2 text-xs" href="<?php echo e(route('admin.variants.edit',$variant)); ?>"><i class="ph ph-pencil-simple"></i> Edit</a>
                            <form class="inline" method="POST" action="<?php echo e(route('admin.variants.destroy',$variant)); ?>" onsubmit="return confirm('Hapus varian ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php echo $__env->make('admin.partials.empty-state', ['colspan' => 8, 'icon' => 'ph-stack', 'title' => 'Varian belum tersedia', 'description' => 'Tambahkan varian untuk produk yang memiliki pilihan paket atau durasi.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php echo $__env->make('admin.partials.pagination', ['paginator' => $variants], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/variants/index.blade.php ENDPATH**/ ?>