<?php $__env->startSection('page_title', $category->exists ? 'Edit Kategori' : 'Tambah Kategori'); ?>
<?php $__env->startSection('page_description','Kategori membantu customer menemukan produk lebih cepat dan membuat katalog lebih rapi.'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $iconOptions = [
        'ph-tag' => 'Tag umum',
        'ph-folders' => 'Kumpulan produk',
        'ph-shopping-bag-open' => 'Belanja',
        'ph-package' => 'Produk fisik',
        'ph-device-mobile' => 'Digital',
        'ph-bowl-food' => 'Makanan',
        'ph-t-shirt' => 'Fashion',
        'ph-sparkle' => 'Spesial',
    ];
?>
<form method="POST" action="<?php echo e($category->exists ? route('admin.categories.update',$category) : route('admin.categories.store')); ?>" class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900"><?php echo csrf_field(); ?> <?php if($category->exists): ?><?php echo method_field('PUT'); ?><?php endif; ?>
    <div class="grid gap-4 lg:grid-cols-2">
        <label class="block"><span class="mb-2 block text-sm font-extrabold">Nama Kategori</span><input name="name" value="<?php echo e(old('name',$category->name)); ?>" placeholder="Contoh: Paket Website" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40" required></label>
        <label class="block"><span class="mb-2 block text-sm font-extrabold">Tampilan Kategori</span><select name="icon" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40"><?php $__currentLoopData = $iconOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if(old('icon',$category->icon ?: 'ph-tag') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
        <label class="block"><span class="mb-2 block text-sm font-extrabold">Posisi Tampil</span><input name="sort_order" type="number" value="<?php echo e(old('sort_order',$category->sort_order ?? 0)); ?>" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40"><small class="mt-1 block text-xs text-slate-400">Angka kecil tampil lebih dulu. Boleh dibiarkan 0.</small></label>
    </div>
    <label class="mt-5 flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 dark:border-slate-800"><input type="checkbox" name="is_active" value="1" class="mt-1 h-4 w-4" <?php if(old('is_active',$category->is_active ?? true)): echo 'checked'; endif; ?>><span><b class="block text-sm">Kategori Aktif</b><small class="text-slate-500 dark:text-slate-400">Kategori tampil dan bisa dipakai di katalog customer.</small></span></label>
    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end"><a href="<?php echo e(route('admin.categories.index')); ?>" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-6 py-3 text-sm font-extrabold dark:border-slate-700">Batal</a><button class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-950 px-6 py-3 text-sm font-extrabold text-white dark:bg-white dark:text-slate-950">Simpan Kategori <i class="ph ph-check-circle"></i></button></div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/categories/form.blade.php ENDPATH**/ ?>