<?php $__env->startSection('page_title','Produk'); ?>
<?php $__env->startSection('page_description','Kelola produk yang tampil di katalog. Foto produk, harga, status, dan urutan bisa dicek dari tabel ini.'); ?>
<?php $__env->startSection('page_action'); ?><a href="<?php echo e(route('admin.products.create')); ?>" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white shadow-sm dark:bg-white dark:text-slate-950"><i class="ph ph-plus-circle"></i> Tambah Produk</a><?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900" data-admin-table>
    <div class="border-b border-slate-100 p-4 dark:border-slate-800">
        <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h2 class="font-extrabold">Daftar Produk</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Cari, urutkan, dan cek visual produk sebelum tampil ke customer.</p>
            </div>
            <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_220px] xl:min-w-[560px]">
                <label class="relative block"><i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input data-datatable-search placeholder="Cari produk..." class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-semibold outline-none dark:border-slate-700 dark:bg-slate-800"></label>
                <form method="GET" action="<?php echo e(route('admin.products.index')); ?>">
                    <select name="category_id" onchange="this.form.submit()" class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold outline-none dark:border-slate-700 dark:bg-slate-800">
                        <option value="">Semua kategori</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php if(request('category_id') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </form>
            </div>
        </div>
    </div>
    <div class="admin-scrollbar overflow-x-auto">
        <table class="w-full min-w-[1040px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                <tr>
                    <th data-sortable class="px-5 py-4">Produk <i class="sort-icon ph ph-caret-up-down ml-1"></i></th>
                    <th data-sortable>Kategori <i class="sort-icon ph ph-caret-up-down ml-1"></i></th>
                    <th data-sortable>Harga <i class="sort-icon ph ph-caret-up-down ml-1"></i></th>
                    <th>Label</th>
                    <th>Status</th>
                    <th data-sortable>Urutan <i class="sort-icon ph ph-caret-up-down ml-1"></i></th>
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
                                <?php if (! ($imageUrl)): ?><div class="mt-1 text-[11px] font-bold text-amber-600 dark:text-amber-300">Foto belum diupload</div><?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><?php echo e($product->category?->name ?: '-'); ?></td>
                    <td><b>Rp <?php echo e(number_format($product->price,0,',','.')); ?></b><?php if($product->old_price): ?><br><span class="text-xs text-slate-400 line-through">Rp <?php echo e(number_format($product->old_price,0,',','.')); ?></span><?php endif; ?></td>
                    <td><?php if($product->badge): ?><span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold text-blue-700 dark:bg-blue-400/10 dark:text-blue-300"><?php echo e($product->badge); ?></span><?php else: ?><span class="text-slate-400">-</span><?php endif; ?></td>
                    <td><div class="flex flex-wrap gap-1.5"><span class="rounded-full px-3 py-1 text-xs font-extrabold <?php echo e($product->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'); ?>"><?php echo e($product->is_active ? 'Aktif' : 'Nonaktif'); ?></span><?php if($product->is_featured): ?><span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-extrabold text-violet-700 dark:bg-violet-400/10 dark:text-violet-300">Unggulan</span><?php endif; ?> <?php if($product->is_latest): ?><span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-extrabold text-cyan-700 dark:bg-cyan-400/10 dark:text-cyan-300">Terbaru</span><?php endif; ?></div></td>
                    <td><?php echo e($product->sort_order); ?></td>
                    <td class="pr-5 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-2 text-xs font-extrabold transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800" href="<?php echo e(route('admin.products.edit',$product)); ?>"><i class="ph ph-pencil-simple"></i> Edit</a>
                            <form class="inline" method="POST" action="<?php echo e(route('admin.products.destroy',$product)); ?>" onsubmit="return confirm('Hapus produk ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?> <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr data-empty-row><td colspan="7" class="px-5 py-10 text-center text-slate-500">Belum ada produk.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="flex flex-col gap-3 border-t border-slate-100 p-4 text-sm dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
        <div class="text-slate-500 dark:text-slate-400"><span data-datatable-info></span></div>
        <div class="flex items-center gap-2"><select data-datatable-length class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold dark:border-slate-700 dark:bg-slate-900"><option value="5">5 baris</option><option value="10" selected>10 baris</option><option value="20">20 baris</option></select><button type="button" data-datatable-prev class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold disabled:opacity-40 dark:border-slate-700">Prev</button><button type="button" data-datatable-next class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold disabled:opacity-40 dark:border-slate-700">Next</button></div>
    </div>
</section>
<div class="mt-4"><?php echo e($products->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/products/index.blade.php ENDPATH**/ ?>