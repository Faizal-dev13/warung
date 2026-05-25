<?php
    use App\Support\Money;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $productImagePath = $product->image_path ?? null;
    $productImageUrl = null;

    if ($productImagePath) {
        if (Str::startsWith($productImagePath, ['http://', 'https://', '/'])) {
            $productImageUrl = $productImagePath;
        } elseif (Str::startsWith($productImagePath, ['storage/'])) {
            $productImageUrl = asset($productImagePath);
        } else {
            $productImageUrl = Storage::url($productImagePath);
        }
    }
?>

<article class="store-product-card group flex h-full min-w-0 flex-col overflow-hidden rounded-[1.35rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-soft dark:border-white/10 dark:bg-white/5 dark:hover:border-blue-400/30">
    <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="block p-3 pb-0 sm:p-4 sm:pb-0" aria-label="Lihat detail <?php echo e($product->name); ?>">
        <div class="store-product-media aspect-[4/3] relative overflow-hidden rounded-[1.05rem] bg-slate-100 ring-1 ring-slate-100 dark:bg-white/10 dark:ring-white/10 sm:rounded-[1.25rem]">
            <?php if($productImageUrl): ?>
                <img src="<?php echo e($productImageUrl); ?>" alt="<?php echo e($product->name); ?>" class="store-product-photo absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
            <?php else: ?>
                <div class="absolute inset-0 overflow-hidden bg-gradient-to-br <?php echo e($product->accent ?: 'from-slate-200 to-slate-300'); ?> p-4 text-white">
                    <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-white/20 blur-2xl"></div>
                    <div class="relative flex h-full flex-col justify-between">
                        <span class="w-fit rounded-full bg-white/20 px-2.5 py-1 text-[11px] font-extrabold backdrop-blur"><?php echo e($product->badge ?? 'Produk'); ?></span>
                        <i class="ph <?php echo e($product->icon ?: 'ph-package'); ?> text-5xl drop-shadow"></i>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($product->badge): ?>
                <span class="absolute left-2.5 top-2.5 max-w-[calc(100%-1.25rem)] truncate rounded-full bg-white/92 px-2.5 py-1 text-[10px] font-extrabold text-slate-800 shadow-sm ring-1 ring-white/60 backdrop-blur dark:bg-slate-950/80 dark:text-white dark:ring-white/10 sm:left-3 sm:top-3 sm:text-[11px]">
                    <?php echo e($product->badge); ?>

                </span>
            <?php endif; ?>
        </div>
    </a>

    <div class="store-product-body flex min-w-0 flex-1 flex-col p-3 sm:p-4">
        <p class="store-product-category truncate text-[11px] font-extrabold uppercase tracking-wide text-blue-600 dark:text-blue-400 sm:text-xs"><?php echo e($product->category?->name ?? 'Produk'); ?></p>

        <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="store-product-title mt-1.5 line-clamp-2 min-h-[2.25rem] text-sm font-extrabold leading-tight text-slate-950 transition group-hover:text-blue-600 dark:text-white dark:group-hover:text-blue-400 sm:mt-2 sm:min-h-[3rem] sm:text-lg sm:leading-snug">
            <?php echo e($product->name); ?>

        </a>

        <p class="store-product-summary mt-1.5 line-clamp-2 min-h-[2rem] text-xs leading-5 text-slate-500 dark:text-slate-400 sm:mt-2 sm:min-h-[3rem] sm:text-sm sm:leading-6"><?php echo e($product->summary); ?></p>

        <div class="store-product-footer mt-auto pt-3">
            <div class="min-w-0">
                <?php if($product->old_price > $product->price): ?>
                    <p class="store-product-old-price truncate text-[11px] font-semibold text-slate-400 line-through sm:text-sm"><?php echo e(Money::rupiah($product->old_price)); ?></p>
                <?php endif; ?>
                <p class="store-product-price truncate text-sm font-extrabold text-slate-950 dark:text-white sm:text-xl"><?php echo e(Money::rupiah($product->price)); ?></p>
            </div>

            <div class="store-product-actions mt-3 grid grid-cols-[1fr_2.5rem] gap-2 sm:grid-cols-[1fr_3rem]">
                <form action="<?php echo e(route('cart.add', $product->slug)); ?>" method="post" class="min-w-0" data-cart-form data-cart-open="true">
                    <?php echo csrf_field(); ?>
                    <button class="store-product-add-button inline-flex h-10 w-full items-center justify-center gap-1.5 rounded-2xl bg-slate-950 px-3 text-xs font-extrabold text-white transition hover:scale-[1.02] disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white dark:text-slate-950 sm:h-12 sm:text-sm">
                        <i class="ph ph-shopping-cart-simple text-base"></i>
                        <span>Tambah</span>
                    </button>
                </form>

                <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="store-product-detail-button inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 transition hover:border-blue-200 hover:text-blue-600 dark:border-white/10 dark:bg-white/10 dark:text-white sm:h-12 sm:w-12" aria-label="Detail <?php echo e($product->name); ?>" title="Detail produk">
                    <i class="ph ph-arrow-right text-base sm:text-lg"></i>
                </a>
            </div>
        </div>
    </div>
</article>
<?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/products/card.blade.php ENDPATH**/ ?>