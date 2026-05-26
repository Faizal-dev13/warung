<?php $__env->startSection('page_title','Kategori'); ?>
<?php $__env->startSection('page_description','Atur kelompok produk agar katalog lebih mudah dipahami oleh customer.'); ?>
<?php $__env->startSection('page_action'); ?>
<a href="<?php echo e(route('admin.categories.create')); ?>" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:bg-white dark:text-slate-950"><i class="ph ph-plus-circle"></i> Tambah Kategori</a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<form method="GET" action="<?php echo e(route('admin.categories.index')); ?>" class="mb-4 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <input type="hidden" name="sort" value="<?php echo e($filters['sort'] ?? 'created_at'); ?>">
    <input type="hidden" name="direction" value="<?php echo e($filters['direction'] ?? 'desc'); ?>">
    <div class="grid gap-3 lg:grid-cols-[1fr_180px_140px_auto]">
        <label class="relative block">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input name="q" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Cari kategori..." class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40">
        </label>
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
            <a href="<?php echo e(route('admin.categories.index')); ?>" class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 px-4 text-sm font-extrabold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Reset</a>
        </div>
    </div>
</form>

<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="border-b border-slate-100 p-5 dark:border-slate-800">
        <h2 class="font-extrabold">Daftar Kategori</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Data ditampilkan sesuai filter dan urutan yang dipilih.</p>
    </div>
    <div class="admin-scrollbar overflow-x-auto">
        <table class="w-full min-w-[820px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4"><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'name', 'label' => 'Kategori', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th>Icon</th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'products', 'label' => 'Produk', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'sort_order', 'label' => 'Urutan', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'status', 'label' => 'Status', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th>
                    <th class="pr-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                        <td class="px-5 py-4 font-extrabold text-slate-950 dark:text-white"><?php echo e($category->name); ?></td>
                        <td><span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300"><i class="ph <?php echo e($category->icon ?: 'ph-tag'); ?> text-base"></i> <?php echo e($category->icon ? 'Dipilih' : 'Default'); ?></span></td>
                        <td class="font-semibold"><?php echo e($category->products_count); ?> produk</td>
                        <td class="font-semibold"><?php echo e($category->sort_order); ?></td>
                        <td><span class="rounded-full px-3 py-1 text-xs font-extrabold <?php echo e($category->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'); ?>"><?php echo e($category->is_active ? 'Aktif' : 'Nonaktif'); ?></span></td>
                        <td class="pr-5 text-right"><div class="inline-flex items-center gap-2"><a class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-2 text-xs font-extrabold transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800" href="<?php echo e(route('admin.categories.edit',$category)); ?>"><i class="ph ph-pencil-simple"></i> Edit</a><form class="inline" method="POST" action="<?php echo e(route('admin.categories.destroy',$category)); ?>" onsubmit="return confirm('Hapus kategori ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?> <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form></div></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php echo $__env->make('admin.partials.empty-state', ['colspan' => 6, 'icon' => 'ph-folders', 'title' => 'Kategori belum tersedia', 'description' => 'Tambahkan kategori agar produk lebih mudah dikelompokkan.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php echo $__env->make('admin.partials.pagination', ['paginator' => $categories], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/categories/index.blade.php ENDPATH**/ ?>