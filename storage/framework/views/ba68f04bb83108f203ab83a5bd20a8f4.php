<?php
    $activeSort = request('sort', $defaultSort ?? '');
    $activeDirection = request('direction', $defaultDirection ?? 'asc') === 'desc' ? 'desc' : 'asc';
    $isActive = $activeSort === $sort;
    $nextDirection = $isActive && $activeDirection === 'asc' ? 'desc' : 'asc';
    $params = array_merge(request()->except(['page', 'sort', 'direction']), [
        'sort' => $sort,
        'direction' => $nextDirection,
    ]);
    $url = url()->current().'?'.http_build_query($params);
?>
<a href="<?php echo e($url); ?>" class="inline-flex items-center gap-1 rounded-xl px-2 py-1 font-extrabold transition <?php echo e($isActive ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white'); ?>">
    <?php echo e($label); ?>

    <?php if($isActive): ?>
        <i class="ph <?php echo e($activeDirection === 'asc' ? 'ph-caret-up' : 'ph-caret-down'); ?> text-xs"></i>
    <?php else: ?>
        <i class="ph ph-caret-up-down text-xs opacity-60"></i>
    <?php endif; ?>
</a>
<?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/partials/sort-link.blade.php ENDPATH**/ ?>