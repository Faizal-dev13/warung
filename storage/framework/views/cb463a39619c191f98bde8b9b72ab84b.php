<?php $__env->startSection('page_title','Produk'); ?>
<?php $__env->startSection('page_description','Kelola foto, harga, status, dan urutan produk yang tampil di katalog.'); ?>
<?php $__env->startSection('page_action'); ?>
<a href="<?php echo e(route('admin.products.create')); ?>" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:bg-white dark:text-slate-950"><i class="ph ph-plus-circle"></i> Tambah Produk</a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<form method="GET" action="<?php echo e(route('admin.products.index')); ?>" class="mb-4 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <input type="hidden" name="sort" value="<?php echo e($filters['sort'] ?? 'created_at'); ?>">
    <input type="hidden" name="direction" value="<?php echo e($filters['direction'] ?? 'desc'); ?>">
    <div class="grid gap-3 xl:grid-cols-[1fr_220px_170px_140px_auto]">
        <label class="relative block">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input name="q" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Cari produk..." class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40">
        </label>
        <select name="category_id" class="h-11 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800">
            <option value="">Semua kategori</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php if(($filters['category_id'] ?? '') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="status" class="h-11 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800">
            <option value="" <?php if(($filters['status'] ?? '') === ''): echo 'selected'; endif; ?>>Semua status</option>
            <option value="active" <?php if(($filters['status'] ?? '') === 'active'): echo 'selected'; endif; ?>>Aktif</option>
            <option value="inactive" <?php if(($filters['status'] ?? '') === 'inactive'): echo 'selected'; endif; ?>>Nonaktif</option>
        </select>
        <select name="per_page" class="h-11 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800">
            <?php $__currentLoopData = [10,25,50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($size); ?>" <?php if(($filters['per_page'] ?? 10) == $size): echo 'selected'; endif; ?>><?php echo e($size); ?> data</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <div class="flex gap-2">
            <button class="inline-flex h-11 flex-1 items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 text-sm font-extrabold text-white transition hover:-translate-y-0.5 dark:bg-white dark:text-slate-950"><i class="ph ph-funnel"></i> Terapkan</button>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 px-4 text-sm font-extrabold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Reset</a>
        </div>
    </div>
</form>

<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="border-b border-slate-100 p-5 dark:border-slate-800">
        <h2 class="font-extrabold">Daftar Produk</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Cek produk, kategori, harga, dan status tayang dari satu tampilan.</p>
    </div>
    <div class="admin-scrollbar overflow-x-auto">
        <table class="w-full min-w-[1040px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4"><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'name', 'label' => 'Produk', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'category', 'label' => 'Kategori', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'price', 'label' => 'Harga', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th>Label</th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'status', 'label' => 'Status', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'sort_order', 'label' => 'Urutan', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th class="pr-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $imagePath = $product->image_path ?? null;
                    $imageUrl = $imagePath
                        ? (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://', '/']) ? $imagePath : \Illuminate\Support\Facades\Storage::url($imagePath))
                        : null;
                ?>
                <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                                <?php if($imageUrl): ?>
                                    <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($product->name); ?>" class="h-full w-full object-cover">
                                <?php else: ?>
                                    <div class="grid h-full w-full place-items-center text-slate-400"><i class="ph ph-image text-2xl"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <div class="max-w-[280px] truncate font-extrabold text-slate-950 dark:text-white"><?php echo e($product->name); ?></div>
                                <div class="mt-1 max-w-[360px] truncate text-xs text-slate-500 dark:text-slate-400"><?php echo e($product->summary); ?></div>
                                <?php if (! ($imageUrl)): ?><div class="mt-1 text-[11px] font-bold text-amber-600 dark:text-amber-300">Foto belum tersedia</div><?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold text-blue-700 dark:bg-blue-400/10 dark:text-blue-300"><?php echo e($product->category?->name ?? '-'); ?></span></td>
                    <td class="font-extrabold">Rp <?php echo e(number_format($product->price,0,',','.')); ?></td>
                    <td><div class="flex flex-wrap gap-1.5"><?php if($product->badge): ?><span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-extrabold text-violet-700 dark:bg-violet-400/10 dark:text-violet-300"><?php echo e($product->badge); ?></span><?php endif; ?> <?php if($product->is_featured): ?><span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-extrabold text-amber-700 dark:bg-amber-400/10 dark:text-amber-300">Unggulan</span><?php endif; ?> <?php if($product->is_latest): ?><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-extrabold text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300">Terbaru</span><?php endif; ?> <?php if(!$product->badge && !$product->is_featured && !$product->is_latest): ?><span class="text-slate-400">-</span><?php endif; ?></div></td>
                    <td><span class="rounded-full px-3 py-1 text-xs font-extrabold <?php echo e($product->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'); ?>"><?php echo e($product->is_active ? 'Aktif' : 'Nonaktif'); ?></span></td>
                    <td class="font-semibold"><?php echo e($product->sort_order); ?></td>
                    <td class="pr-5 text-right"><div class="inline-flex items-center gap-2"><a class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-2 text-xs font-extrabold transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800" href="<?php echo e(route('admin.products.edit',$product)); ?>"><i class="ph ph-pencil-simple"></i> Edit</a><form class="inline" method="POST" action="<?php echo e(route('admin.products.destroy',$product)); ?>" onsubmit="return confirm('Hapus produk ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?> <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form></div></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php echo $__env->make('admin.partials.empty-state', ['colspan' => 7, 'icon' => 'ph-package', 'title' => 'Produk belum tersedia', 'description' => 'Tambahkan produk agar katalog bisa mulai digunakan.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php echo $__env->make('admin.partials.pagination', ['paginator' => $products], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/products/index.blade.php ENDPATH**/ ?>