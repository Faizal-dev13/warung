<?php if($paginator->total() > 0): ?>
    <div class="mt-4 flex flex-col gap-3 rounded-[1.25rem] border border-slate-200 bg-white p-4 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:flex-row sm:items-center sm:justify-between">
        <p class="font-semibold text-slate-500 dark:text-slate-400">
            Menampilkan <span class="font-extrabold text-slate-900 dark:text-white"><?php echo e($paginator->firstItem()); ?></span>-<span class="font-extrabold text-slate-900 dark:text-white"><?php echo e($paginator->lastItem()); ?></span> dari <span class="font-extrabold text-slate-900 dark:text-white"><?php echo e($paginator->total()); ?></span> data
        </p>
        <?php if($paginator->hasPages()): ?>
            <div class="flex flex-wrap items-center gap-2">
                <?php if($paginator->onFirstPage()): ?>
                    <span class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-xs font-extrabold text-slate-300 dark:border-slate-800 dark:text-slate-600">Prev</span>
                <?php else: ?>
                    <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-xs font-extrabold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Prev</a>
                <?php endif; ?>

                <?php $__currentLoopData = $paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl bg-slate-950 px-3 text-xs font-extrabold text-white dark:bg-white dark:text-slate-950"><?php echo e($page); ?></span>
                    <?php else: ?>
                        <a href="<?php echo e($url); ?>" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-xs font-extrabold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"><?php echo e($page); ?></a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($paginator->hasMorePages()): ?>
                    <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-xs font-extrabold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Next</a>
                <?php else: ?>
                    <span class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-xs font-extrabold text-slate-300 dark:border-slate-800 dark:text-slate-600">Next</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/partials/pagination.blade.php ENDPATH**/ ?>