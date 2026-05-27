<?php $__env->startSection('title', ($settings['qna_title'] ?? 'QnA').' - '.($settings['store_name'] ?? config('store.name'))); ?>
<?php $__env->startSection('content'); ?>
<?php
    $title = $settings['qna_title'] ?? 'QnA';
    $subtitle = $settings['qna_subtitle'] ?? 'Temukan jawaban dari pertanyaan yang paling sering ditanyakan sebelum checkout.';
    $whatsappNumber = \App\Models\Setting::whatsappNumber();
?>
<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
    <div class="overflow-hidden rounded-[2rem] bg-slate-950 p-6 text-white shadow-soft sm:p-8 lg:p-10">
        <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-extrabold uppercase tracking-[.18em] text-teal-100 ring-1 ring-white/10"><i class="ph ph-question"></i> Bantuan</span>
                <h1 class="mt-5 text-3xl font-extrabold tracking-tight sm:text-5xl"><?php echo e($title); ?></h1>
                <p class="mt-4 max-w-2xl text-sm leading-7 text-white/70 sm:text-base"><?php echo e($subtitle); ?></p>
            </div>
            <?php if($whatsappNumber): ?>
                <a href="https://wa.me/<?php echo e($whatsappNumber); ?>" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-extrabold text-slate-950 transition hover:-translate-y-0.5 hover:shadow-lg"><i class="ph-fill ph-whatsapp-logo"></i> Tanya Admin</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-6 grid gap-3 sm:mt-8">
        <?php $__empty_1 = true; $__currentLoopData = $qnas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qna): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <details class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition open:border-teal-200 open:bg-teal-50/40 dark:border-white/10 dark:bg-white/5 dark:open:border-teal-400/30 dark:open:bg-teal-400/10 sm:p-6">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-extrabold text-slate-950 dark:text-white">
                    <span><?php echo e($qna->question); ?></span>
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-2xl bg-slate-100 text-slate-600 transition group-open:rotate-45 group-open:bg-teal-700 group-open:text-white dark:bg-white/10 dark:text-slate-300"><i class="ph ph-plus"></i></span>
                </summary>
                <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300 sm:text-base"><?php echo e($qna->answer); ?></p>
            </details>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-8 text-center dark:border-white/15 dark:bg-white/5">
                <i class="ph ph-question text-5xl text-slate-400"></i>
                <h2 class="mt-4 text-xl font-extrabold text-slate-950 dark:text-white">QnA belum tersedia</h2>
                <p class="mt-2 text-sm leading-7 text-slate-500 dark:text-slate-400">Silakan cek kembali nanti atau hubungi admin untuk bantuan langsung.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/pages/qna.blade.php ENDPATH**/ ?>