<?php $__env->startSection('title', 'Produk - '.($settings['store_name'] ?? config('store.name'))); ?>
<?php $__env->startSection('content'); ?>
<?php if (! $__env->hasRenderedOnce('325e83cb-1e4f-421e-ade6-7338ddf79925')): $__env->markAsRenderedOnce('325e83cb-1e4f-421e-ade6-7338ddf79925'); ?>
    <style>
        html { scroll-behavior: smooth; }
        #produk { scroll-margin-top: 5.5rem; }

        .home-product-grid { align-items: stretch; }
        .home-product-card { min-width: 0; height: 100%; }
        .home-product-card .store-product-card {
            height: 100%;
            border-radius: 1.35rem;
            box-shadow: 0 12px 34px rgba(15, 23, 42, .065);
        }
        .home-product-card .store-product-media { aspect-ratio: 4 / 3; }
        .home-product-card .store-product-photo {
            display: block !important;
            width: 100% !important;
            height: 100% !important;
            max-height: none !important;
            object-fit: cover !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        @media (max-width: 639px) {
            .home-product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: .72rem;
            }

            .home-product-card .store-product-card {
                border-radius: 1.05rem;
                box-shadow: 0 10px 24px rgba(15, 23, 42, .075);
            }

            .home-product-card .store-product-card > a { padding: .55rem .55rem 0 !important; }

            .home-product-card .store-product-media {
                aspect-ratio: 1 / .78;
                min-height: 6.95rem;
                border-radius: .85rem !important;
            }

            .home-product-card .store-product-body { padding: .65rem !important; }
            .home-product-card .store-product-category { font-size: .62rem !important; line-height: .85rem !important; }
            .home-product-card .store-product-title {
                min-height: 2.14rem;
                font-size: .82rem !important;
                line-height: 1.07rem !important;
                letter-spacing: -.01em;
            }
            .home-product-card .store-product-summary {
                min-height: 1rem;
                font-size: .69rem !important;
                line-height: .98rem !important;
                -webkit-line-clamp: 1 !important;
            }
            .home-product-card .store-product-old-price { font-size: .64rem !important; line-height: .8rem !important; }
            .home-product-card .store-product-price { font-size: .9rem !important; line-height: 1.1rem !important; }
            .home-product-card .store-product-footer { padding-top: .62rem !important; }
            .home-product-card .store-product-actions {
                grid-template-columns: minmax(0, 1fr) 2.12rem;
                gap: .42rem !important;
                margin-top: .55rem !important;
            }
            .home-product-card .store-product-add-button,
            .home-product-card .store-product-detail-button {
                height: 2.12rem !important;
                min-height: 2.12rem !important;
                border-radius: .78rem !important;
                font-size: .68rem !important;
                line-height: .9rem !important;
                white-space: nowrap;
            }
            .home-product-card .store-product-add-button { padding-inline: .45rem !important; }
            .home-product-card .store-product-detail-button {
                width: 2.12rem !important;
                min-width: 2.12rem !important;
            }
            .home-product-card .store-product-add-button i,
            .home-product-card .store-product-detail-button i { font-size: .92rem !important; }
        }
    </style>
<?php endif; ?>

<section id="produk" class="mx-auto max-w-7xl px-4 py-7 pb-24 sm:px-6 sm:py-10 md:pb-12 lg:px-8">
    <div class="mb-6 flex flex-col items-center gap-4 text-center md:mb-8">
        <div class="mx-auto max-w-3xl">
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-5xl">Katalog Produk</h1>
            <p class="mt-2 text-base font-extrabold text-slate-700 dark:text-slate-200 sm:text-lg">Pilih produk yang paling sesuai</p>
            <p class="mx-auto mt-2 max-w-2xl text-xs leading-6 text-slate-500 dark:text-slate-400 sm:text-sm sm:leading-7">Cari produk favorit, cek pilihan yang tersedia, lalu lanjutkan pesanan dengan alur yang simpel.</p>
        </div>
        <button data-cart-toggle type="button" class="hidden items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:bg-white dark:text-slate-950 md:inline-flex">
            Cek Keranjang <i class="ph ph-shopping-cart-simple"></i>
        </button>
    </div>

    <form method="get" action="<?php echo e(route('products.index')); ?>" class="mb-6 rounded-[1.5rem] border border-slate-200 bg-white p-3 shadow-sm dark:border-white/10 dark:bg-white/5 sm:mb-8 sm:rounded-[2rem] sm:p-4">
        <div class="grid gap-3 md:grid-cols-[1fr_.65fr_.55fr_auto]">
            <label class="relative block">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input name="q" value="<?php echo e($filters['query']); ?>" placeholder="Cari produk..." class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-medium outline-none transition placeholder:text-slate-400 focus:border-slate-950 focus:bg-white dark:border-white/10 dark:bg-white/10 dark:focus:border-white/40">
            </label>

            <select name="category" class="h-12 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-medium outline-none transition focus:border-slate-950 focus:bg-white dark:border-white/10 dark:bg-white/10 dark:focus:border-white/40">
                <option value="all" <?php if($filters['category'] === 'all'): echo 'selected'; endif; ?>>Semua Kategori</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->slug); ?>" <?php if($filters['category'] === $category->slug): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="sort" class="h-12 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-medium outline-none transition focus:border-slate-950 focus:bg-white dark:border-white/10 dark:bg-white/10 dark:focus:border-white/40">
                <option value="popular" <?php if($filters['sort'] === 'popular'): echo 'selected'; endif; ?>>Paling Populer</option>
                <option value="highest" <?php if($filters['sort'] === 'highest'): echo 'selected'; endif; ?>>Harga Tertinggi</option>
                <option value="lowest" <?php if($filters['sort'] === 'lowest'): echo 'selected'; endif; ?>>Harga Terendah</option>
                <option value="discount" <?php if($filters['sort'] === 'discount'): echo 'selected'; endif; ?>>Promo Terbaik</option>
            </select>

            <button class="h-12 rounded-2xl bg-slate-950 px-6 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:bg-white dark:text-slate-950">Terapkan</button>
        </div>
    </form>

    <div class="mb-5 flex items-center justify-between gap-3 text-sm text-slate-500 dark:text-slate-400">
        <p>
            <?php if($products->total() > 0): ?>
                Menampilkan <?php echo e($products->firstItem()); ?>-<?php echo e($products->lastItem()); ?> dari <?php echo e($products->total()); ?> produk
            <?php else: ?>
                Belum ada produk yang sesuai
            <?php endif; ?>
        </p>
        <?php if($filters['query'] !== '' || $filters['category'] !== 'all' || $filters['sort'] !== 'popular'): ?>
            <a href="<?php echo e(route('products.index')); ?>" class="font-extrabold text-teal-700 hover:text-teal-800 dark:text-teal-300">Reset</a>
        <?php endif; ?>
    </div>

    <div class="home-product-grid grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="home-product-card min-w-0">
                <?php echo $__env->make('products.card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full rounded-[1.5rem] border border-dashed border-slate-300 bg-white/60 p-8 text-center dark:border-white/15 dark:bg-white/5 sm:rounded-[2rem] sm:p-10">
                <i class="ph ph-magnifying-glass text-5xl text-slate-400"></i>
                <h3 class="mt-4 text-lg font-extrabold text-slate-950 dark:text-white sm:text-xl">Produk tidak ditemukan</h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 sm:text-base">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if($products->hasPages()): ?>
        <div class="mt-8 flex flex-col gap-3 rounded-[1.5rem] border border-slate-200 bg-white p-3 shadow-sm dark:border-white/10 dark:bg-white/5 sm:flex-row sm:items-center sm:justify-between sm:rounded-[2rem] sm:p-4">
            <p class="px-2 text-xs font-semibold text-slate-500 dark:text-slate-400 sm:text-sm">Halaman <?php echo e($products->currentPage()); ?> dari <?php echo e($products->lastPage()); ?></p>
            <div class="grid grid-cols-2 gap-2 sm:flex sm:items-center">
                <?php if($products->onFirstPage()): ?>
                    <span class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 px-4 text-sm font-extrabold text-slate-300 dark:border-white/10 dark:text-slate-600">Sebelumnya</span>
                <?php else: ?>
                    <a href="<?php echo e($products->previousPageUrl()); ?>" class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 text-sm font-extrabold text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:bg-white/10 dark:text-white dark:hover:bg-white/15">Sebelumnya</a>
                <?php endif; ?>

                <?php if($products->hasMorePages()): ?>
                    <a href="<?php echo e($products->nextPageUrl()); ?>" class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-950 px-4 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:shadow-md dark:bg-white dark:text-slate-950">Berikutnya</a>
                <?php else: ?>
                    <span class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 px-4 text-sm font-extrabold text-slate-300 dark:border-white/10 dark:text-slate-600">Berikutnya</span>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php echo $__env->make('partials.mobile-bottom-menu', ['bottomActive' => 'products'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/pages/products.blade.php ENDPATH**/ ?>