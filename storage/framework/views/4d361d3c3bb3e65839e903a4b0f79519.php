<?php $__env->startSection('page_title','Banner'); ?>
<?php $__env->startSection('page_description','Kelola slide promosi yang tampil di halaman depan.'); ?>
<?php $__env->startSection('page_action'); ?>
<a href="<?php echo e(route('admin.banners.create')); ?>" class="admin-btn-primary"><i class="ph ph-plus-circle"></i> Tambah Banner</a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<form method="GET" action="<?php echo e(route('admin.banners.index')); ?>" class="mb-5 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <input type="hidden" name="sort" value="<?php echo e($filters['sort'] ?? 'created_at'); ?>">
    <input type="hidden" name="direction" value="<?php echo e($filters['direction'] ?? 'desc'); ?>">
    <div class="grid gap-3 lg:grid-cols-[1fr_180px_140px_auto]">
        <label class="relative block"><i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input name="q" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Cari banner..." class="admin-input h-12 pl-11"></label>
        <select name="status" class="admin-select h-12"><option value="" <?php if(($filters['status'] ?? '') === ''): echo 'selected'; endif; ?>>Semua status</option><option value="active" <?php if(($filters['status'] ?? '') === 'active'): echo 'selected'; endif; ?>>Aktif</option><option value="inactive" <?php if(($filters['status'] ?? '') === 'inactive'): echo 'selected'; endif; ?>>Nonaktif</option></select>
        <select name="per_page" class="admin-select h-12"><?php $__currentLoopData = [10,25,50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($size); ?>" <?php if(($filters['per_page'] ?? 10) == $size): echo 'selected'; endif; ?>><?php echo e($size); ?> data</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <div class="grid grid-cols-2 gap-2 sm:flex"><button class="admin-btn-primary h-12"><i class="ph ph-funnel"></i> Terapkan</button><a href="<?php echo e(route('admin.banners.index')); ?>" class="admin-btn-secondary h-12">Reset</a></div>
    </div>
</form>

<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="flex flex-col gap-3 border-b border-slate-100 p-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
        <div><h2 class="font-extrabold">Daftar Banner</h2><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Cek gambar, teks, tombol, status, dan urutan banner.</p></div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300"><?php echo e($banners->total()); ?> banner</span>
    </div>

    <div class="grid gap-3 p-4 xl:hidden">
        <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $imagePath = $banner->image_path ?? null;
                $imageUrl = $imagePath ? (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://', '/']) ? $imagePath : \Illuminate\Support\Facades\Storage::url($imagePath)) : null;
                $mobileImagePath = $banner->mobile_image_path ?? null;
                $mobileImageUrl = $mobileImagePath ? (\Illuminate\Support\Str::startsWith($mobileImagePath, ['http://', 'https://', '/']) ? $mobileImagePath : \Illuminate\Support\Facades\Storage::url($mobileImagePath)) : null;
            ?>
            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950/40">
                <div class="aspect-[16/7] bg-slate-100 dark:bg-slate-800">
                    <?php if($imageUrl): ?><img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($banner->title); ?>" class="h-full w-full object-cover"><?php else: ?><div class="grid h-full w-full place-items-center text-slate-400"><i class="ph ph-image text-4xl"></i></div><?php endif; ?>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <?php if($banner->label): ?><p class="text-xs font-extrabold text-teal-700 dark:text-teal-300"><?php echo e($banner->label); ?></p><?php endif; ?>
                            <p class="mt-1 line-clamp-2 font-extrabold text-slate-950 dark:text-white"><?php echo e($banner->title); ?></p>
                            <?php if($banner->subtitle): ?><p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500 dark:text-slate-400"><?php echo e($banner->subtitle); ?></p><?php endif; ?>
                        </div>
                        <span class="shrink-0 rounded-full px-3 py-1 text-[11px] font-extrabold <?php echo e($banner->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-200 text-slate-500 dark:bg-slate-800 dark:text-slate-400'); ?>"><?php echo e($banner->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-2 text-xs font-bold text-slate-500 dark:text-slate-400">
                        <span class="rounded-full bg-white px-3 py-1 dark:bg-slate-900">Urutan <?php echo e($banner->sort_order); ?></span>
                        <?php if($mobileImageUrl): ?><span class="rounded-full bg-white px-3 py-1 dark:bg-slate-900">Gambar HP tersedia</span><?php endif; ?>
                        <?php if($banner->button_text): ?><span class="rounded-full bg-white px-3 py-1 dark:bg-slate-900"><?php echo e($banner->button_text); ?></span><?php endif; ?>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <a class="admin-btn-secondary h-11 text-xs" href="<?php echo e(route('admin.banners.edit',$banner)); ?>"><i class="ph ph-pencil-simple"></i> Edit</a>
                        <form method="POST" action="<?php echo e(route('admin.banners.destroy',$banner)); ?>" onsubmit="return confirm('Hapus banner ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-2xl border border-red-200 bg-white px-3 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:bg-slate-900 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form>
                    </div>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-center dark:border-slate-700"><i class="ph ph-images text-4xl text-slate-400"></i><p class="mt-3 font-extrabold">Banner belum tersedia</p><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tambahkan banner untuk menampilkan promosi.</p></div>
        <?php endif; ?>
    </div>

    <div class="admin-scrollbar hidden overflow-x-auto xl:block"><table class="w-full min-w-[1020px] text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400"><tr><th class="px-5 py-4"><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'title', 'label' => 'Banner', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th><th>Gambar</th><th>Tombol</th><th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'sort_order', 'label' => 'Urutan', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th><th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'status', 'label' => 'Status', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th><th class="pr-5 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100 dark:divide-slate-800">
    <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $imagePath = $banner->image_path ?? null;
            $imageUrl = $imagePath ? (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://', '/']) ? $imagePath : \Illuminate\Support\Facades\Storage::url($imagePath)) : null;
            $mobileImagePath = $banner->mobile_image_path ?? null;
            $mobileImageUrl = $mobileImagePath ? (\Illuminate\Support\Str::startsWith($mobileImagePath, ['http://', 'https://', '/']) ? $mobileImagePath : \Illuminate\Support\Facades\Storage::url($mobileImagePath)) : null;
        ?>
        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
            <td class="px-5 py-4"><div class="max-w-[340px]"><p class="font-extrabold text-slate-950 dark:text-white"><?php echo e($banner->title); ?></p><?php if($banner->label): ?><p class="mt-1 text-xs font-bold text-teal-600 dark:text-teal-300"><?php echo e($banner->label); ?></p><?php endif; ?> <?php if($banner->subtitle): ?><p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500 dark:text-slate-400"><?php echo e($banner->subtitle); ?></p><?php endif; ?></div></td>
            <td><div class="flex items-center gap-2"><div class="h-14 w-28 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 dark:border-slate-700 dark:bg-slate-800"><?php if($imageUrl): ?><img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($banner->title); ?>" class="h-full w-full object-cover"><?php else: ?><div class="grid h-full w-full place-items-center text-slate-400"><i class="ph ph-image text-2xl"></i></div><?php endif; ?></div><?php if($mobileImageUrl): ?><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">Mobile</span><?php endif; ?></div></td>
            <td><div class="max-w-[180px] truncate font-semibold"><?php echo e($banner->button_text ?: '-'); ?></div></td>
            <td class="font-semibold"><?php echo e($banner->sort_order); ?></td>
            <td><span class="rounded-full px-3 py-1 text-xs font-extrabold <?php echo e($banner->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'); ?>"><?php echo e($banner->is_active ? 'Aktif' : 'Nonaktif'); ?></span></td>
            <td class="pr-5 text-right"><div class="inline-flex items-center gap-2"><a class="admin-btn-secondary px-3 py-2 text-xs" href="<?php echo e(route('admin.banners.edit',$banner)); ?>"><i class="ph ph-pencil-simple"></i> Edit</a><form class="inline" method="POST" action="<?php echo e(route('admin.banners.destroy',$banner)); ?>" onsubmit="return confirm('Hapus banner ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?> <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form></div></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <?php echo $__env->make('admin.partials.empty-state', ['colspan' => 6, 'icon' => 'ph-images', 'title' => 'Banner belum tersedia', 'description' => 'Tambahkan banner untuk menampilkan promosi di halaman depan.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
    </tbody></table></div>
</section>
<?php echo $__env->make('admin.partials.pagination', ['paginator' => $banners], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/banners/index.blade.php ENDPATH**/ ?>