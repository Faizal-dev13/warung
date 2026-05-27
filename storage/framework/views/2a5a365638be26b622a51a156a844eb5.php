<?php $__env->startSection('page_title','QnA'); ?>
<?php $__env->startSection('page_description','Kelola pertanyaan yang sering ditanyakan customer sebelum checkout.'); ?>
<?php $__env->startSection('page_action'); ?>
<a href="<?php echo e(route('admin.qnas.create')); ?>" class="admin-btn-primary"><i class="ph ph-plus-circle"></i> Tambah QnA</a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<form method="GET" action="<?php echo e(route('admin.qnas.index')); ?>" class="mb-5 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <input type="hidden" name="sort" value="<?php echo e($filters['sort'] ?? 'sort_order'); ?>">
    <input type="hidden" name="direction" value="<?php echo e($filters['direction'] ?? 'asc'); ?>">
    <div class="grid gap-3 xl:grid-cols-[1fr_170px_140px_auto]">
        <label class="relative block"><i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input name="q" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Cari pertanyaan atau jawaban..." class="admin-input h-12 pl-11"></label>
        <select name="status" class="admin-select h-12"><option value="" <?php if(($filters['status'] ?? '') === ''): echo 'selected'; endif; ?>>Semua status</option><option value="active" <?php if(($filters['status'] ?? '') === 'active'): echo 'selected'; endif; ?>>Aktif</option><option value="inactive" <?php if(($filters['status'] ?? '') === 'inactive'): echo 'selected'; endif; ?>>Nonaktif</option></select>
        <select name="per_page" class="admin-select h-12"><?php $__currentLoopData = [10,25,50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($size); ?>" <?php if(($filters['per_page'] ?? 10) == $size): echo 'selected'; endif; ?>><?php echo e($size); ?> data</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <div class="grid grid-cols-2 gap-2 sm:flex"><button class="admin-btn-primary h-12"><i class="ph ph-funnel"></i> Terapkan</button><a href="<?php echo e(route('admin.qnas.index')); ?>" class="admin-btn-secondary h-12">Reset</a></div>
    </div>
</form>

<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="flex flex-col gap-3 border-b border-slate-100 p-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
        <div><h2 class="font-extrabold">Daftar QnA</h2><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Jawaban yang tampil di halaman QnA customer.</p></div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300"><?php echo e($qnas->total()); ?> QnA</span>
    </div>

    <div class="grid gap-3 p-4 lg:hidden">
        <?php $__empty_1 = true; $__currentLoopData = $qnas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qna): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-extrabold text-slate-950 dark:text-white"><?php echo e($qna->question); ?></p>
                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600 dark:text-slate-300"><?php echo e($qna->answer); ?></p>
                    </div>
                    <span class="shrink-0 rounded-full px-3 py-1 text-[11px] font-extrabold <?php echo e($qna->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-200 text-slate-500 dark:bg-slate-800 dark:text-slate-400'); ?>"><?php echo e($qna->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <a class="admin-btn-secondary h-11 text-xs" href="<?php echo e(route('admin.qnas.edit',$qna)); ?>"><i class="ph ph-pencil-simple"></i> Edit</a>
                    <form method="POST" action="<?php echo e(route('admin.qnas.destroy',$qna)); ?>" onsubmit="return confirm('Hapus QnA ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-2xl border border-red-200 bg-white px-3 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:bg-slate-900 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-center dark:border-slate-700"><i class="ph ph-question text-4xl text-slate-400"></i><p class="mt-3 font-extrabold">QnA belum tersedia</p><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tambahkan pertanyaan yang sering ditanyakan customer.</p></div>
        <?php endif; ?>
    </div>

    <div class="admin-scrollbar hidden overflow-x-auto lg:block"><table class="w-full min-w-[920px] text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400"><tr><th class="px-5 py-4"><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'question', 'label' => 'Pertanyaan', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th><th>Jawaban</th><th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'status', 'label' => 'Status', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th><th><?php echo $__env->make('admin.partials.sort-link', ['sort' => 'sort_order', 'label' => 'Urutan', 'defaultSort' => $filters['sort'] ?? 'sort_order'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></th><th class="pr-5 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100 dark:divide-slate-800">
    <?php $__empty_1 = true; $__currentLoopData = $qnas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qna): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
            <td class="px-5 py-4 font-extrabold text-slate-950 dark:text-white"><div class="max-w-[280px]"><?php echo e($qna->question); ?></div></td>
            <td><p class="line-clamp-2 max-w-[360px] text-sm leading-6 text-slate-600 dark:text-slate-300"><?php echo e($qna->answer); ?></p></td>
            <td><span class="rounded-full px-3 py-1 text-xs font-extrabold <?php echo e($qna->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'); ?>"><?php echo e($qna->is_active ? 'Aktif' : 'Nonaktif'); ?></span></td>
            <td class="font-semibold"><?php echo e($qna->sort_order); ?></td>
            <td class="pr-5 text-right"><div class="inline-flex items-center gap-2"><a class="admin-btn-secondary px-3 py-2 text-xs" href="<?php echo e(route('admin.qnas.edit',$qna)); ?>"><i class="ph ph-pencil-simple"></i> Edit</a><form class="inline" method="POST" action="<?php echo e(route('admin.qnas.destroy',$qna)); ?>" onsubmit="return confirm('Hapus QnA ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?> <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form></div></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <?php echo $__env->make('admin.partials.empty-state', ['colspan' => 5, 'icon' => 'ph-question', 'title' => 'QnA belum tersedia', 'description' => 'Tambahkan pertanyaan yang sering ditanyakan customer.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
    </tbody></table></div>
</section>
<?php echo $__env->make('admin.partials.pagination', ['paginator' => $qnas], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/qnas/index.blade.php ENDPATH**/ ?>