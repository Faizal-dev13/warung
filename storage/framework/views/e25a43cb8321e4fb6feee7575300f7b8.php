<?php
    $bottomActive = $bottomActive ?? 'home';
    $isHomeBottom = $bottomActive === 'home';
?>

<?php if (! $__env->hasRenderedOnce('50755cd5-a6ec-4256-83ac-2555109f4a7a')): $__env->markAsRenderedOnce('50755cd5-a6ec-4256-83ac-2555109f4a7a'); ?>
    <style>
        .mobile-bottom-link,
        .mobile-bottom-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .25rem;
            border-radius: 1rem;
            padding: .55rem .5rem;
            font-size: 10px;
            font-weight: 800;
            line-height: 1;
            transition: transform .18s ease, color .18s ease, background .18s ease, box-shadow .18s ease;
        }

        .mobile-bottom-link { color: rgb(100 116 139); }
        .dark .mobile-bottom-link { color: rgb(148 163 184); }
        .mobile-bottom-link:hover { background: rgb(241 245 249); color: rgb(15 23 42); }
        .dark .mobile-bottom-link:hover { background: rgba(255, 255, 255, .1); color: #fff; }

        .mobile-bottom-link.is-active {
            background: rgb(15 23 42);
            color: #fff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .18);
        }

        .dark .mobile-bottom-link.is-active {
            background: rgb(15 118 110);
            color: #fff;
            box-shadow: 0 10px 24px rgba(15, 118, 110, .28);
        }

        .mobile-bottom-action {
            position: relative;
            border: 1px solid rgba(15, 118, 110, .18);
            background: linear-gradient(135deg, rgba(15, 118, 110, .1), rgba(5, 150, 105, .12));
            color: rgb(15 118 110);
        }

        .dark .mobile-bottom-action {
            border-color: rgba(45, 212, 191, .22);
            background: rgba(15, 118, 110, .22);
            color: rgb(153 246 228);
        }

        .mobile-bottom-action.is-active {
            background: rgb(15 118 110);
            color: #fff;
            box-shadow: 0 10px 24px rgba(15, 118, 110, .28);
        }

        .mobile-bottom-action:active { transform: scale(.96); }

        @media (max-width: 767px) {
            body { padding-bottom: 5.75rem; }
            .mobile-bottom-menu { padding-bottom: max(.75rem, env(safe-area-inset-bottom)); }
        }

        @media (max-width: 639px) {
            .mobile-bottom-link,
            .mobile-bottom-action { min-height: 3.35rem; }
        }
    </style>
<?php endif; ?>

<nav class="mobile-bottom-menu fixed inset-x-0 bottom-0 z-50 px-3 md:hidden">
    <div class="mx-auto grid max-w-md grid-cols-4 gap-1 rounded-[1.45rem] border border-slate-200/80 bg-white/95 p-2 shadow-[0_-12px_35px_rgba(15,23,42,.12)] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950 dark:shadow-[0_-16px_40px_rgba(0,0,0,.35)]">
        <a href="<?php echo e($isHomeBottom ? '#home' : route('home')); ?>" <?php if($isHomeBottom): ?> data-bottom-target="home" <?php endif; ?> class="mobile-bottom-link <?php echo e($bottomActive === 'home' ? 'is-active' : ''); ?>">
            <i class="ph ph-house text-lg"></i>
            Home
        </a>
        <a href="<?php echo e(route('products.index')); ?>" class="mobile-bottom-link <?php echo e($bottomActive === 'products' ? 'is-active' : ''); ?>">
            <i class="ph ph-squares-four text-lg"></i>
            Produk
        </a>
        <a href="<?php echo e($isHomeBottom ? '#voucher' : route('home').'#voucher'); ?>" <?php if($isHomeBottom): ?> data-bottom-target="voucher" <?php endif; ?> class="mobile-bottom-link <?php echo e($bottomActive === 'voucher' ? 'is-active' : ''); ?>">
            <i class="ph ph-ticket text-lg"></i>
            Promo
        </a>
        <a href="<?php echo e(route('qna')); ?>" class="mobile-bottom-action <?php echo e($bottomActive === 'qna' ? 'is-active' : ''); ?>">
            <i class="ph ph-question text-lg"></i>
            QnA
        </a>
    </div>
</nav>

<?php if($isHomeBottom): ?>
    <?php if (! $__env->hasRenderedOnce('a33626c3-11fd-4edf-92a2-e8880aac0df9')): $__env->markAsRenderedOnce('a33626c3-11fd-4edf-92a2-e8880aac0df9'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const menuLinks = Array.from(document.querySelectorAll('[data-bottom-target]'));
                const sections = menuLinks
                    .map((link) => document.getElementById(link.dataset.bottomTarget))
                    .filter(Boolean);

                const setActiveMenu = (id) => {
                    menuLinks.forEach((link) => {
                        link.classList.toggle('is-active', link.dataset.bottomTarget === id);
                    });
                };

                const updateActiveMenu = () => {
                    if (!sections.length) return;

                    const offset = window.innerHeight * 0.38;
                    let current = sections[0].id;

                    sections.forEach((section) => {
                        const rect = section.getBoundingClientRect();
                        if (rect.top <= offset) {
                            current = section.id;
                        }
                    });

                    setActiveMenu(current);
                };

                menuLinks.forEach((link) => {
                    link.addEventListener('click', () => {
                        setActiveMenu(link.dataset.bottomTarget);
                    });
                });

                updateActiveMenu();
                window.addEventListener('scroll', updateActiveMenu, { passive: true });
                window.addEventListener('hashchange', updateActiveMenu);
            });
        </script>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/partials/mobile-bottom-menu.blade.php ENDPATH**/ ?>