<?php
    use App\Support\Money;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $cart = $cart ?? [];
    $items = $cart['items'] ?? [];
?>

<?php if(empty($items)): ?>
    <div class="grid min-h-72 place-items-center rounded-[1.75rem] border border-dashed border-slate-300 bg-white p-8 text-center shadow-sm dark:border-white/15 dark:bg-white/5">
        <div>
            <span class="mx-auto grid h-16 w-16 place-items-center rounded-3xl bg-slate-100 text-slate-400 dark:bg-white/10 dark:text-slate-300">
                <i class="ph ph-shopping-bag-open text-4xl"></i>
            </span>
            <p class="mt-4 font-extrabold text-slate-950 dark:text-white">Keranjang masih kosong</p>
            <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">Pilih produk dulu, nanti ringkasannya muncul di sini.</p>
            <button type="button" data-cart-close class="mt-5 inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white transition hover:-translate-y-0.5 dark:bg-white dark:text-slate-950">
                Lihat Produk <i class="ph ph-arrow-right"></i>
            </button>
        </div>
    </div>
<?php else: ?>
    <div class="space-y-3">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $cartImagePath = $item['image_path'] ?? null;
                $cartImageUrl = null;

                if ($cartImagePath) {
                    if (Str::startsWith($cartImagePath, ['http://', 'https://', '/'])) {
                        $cartImageUrl = $cartImagePath;
                    } elseif (Str::startsWith($cartImagePath, ['storage/'])) {
                        $cartImageUrl = asset($cartImagePath);
                    } else {
                        $cartImageUrl = Storage::url($cartImagePath);
                    }
                }

                $cartKey = $item['key'] ?? (string) ($item['id'] ?? $loop->index);
                $cartInputId = 'cart-qty-'.preg_replace('/[^A-Za-z0-9_-]/', '-', $cartKey);
            ?>

            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-3 shadow-sm transition hover:shadow-md dark:border-white/10 dark:bg-white/5 sm:p-4">
                <div class="flex gap-3">
                    <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-slate-100 ring-1 ring-slate-100 dark:bg-white/10 dark:ring-white/10">
                        <?php if($cartImageUrl): ?>
                            <img src="<?php echo e($cartImageUrl); ?>" alt="<?php echo e($item['name']); ?>" class="h-full w-full object-cover" loading="lazy">
                        <?php else: ?>
                            <div class="grid h-full w-full place-items-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-500 dark:from-white/10 dark:to-white/5 dark:text-slate-300">
                                <i class="ph <?php echo e($item['icon'] ?? 'ph-package'); ?> text-3xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="line-clamp-2 text-sm font-extrabold leading-snug text-slate-950 dark:text-white"><?php echo e($item['product_name'] ?? $item['name']); ?></p>
                                <?php if(!empty($item['variant_name'])): ?>
                                    <p class="mt-1 inline-flex rounded-full bg-teal-50 px-2.5 py-1 text-[11px] font-extrabold text-teal-700 dark:bg-teal-400/10 dark:text-teal-300"><?php echo e($item['variant_name']); ?></p>
                                <?php endif; ?>
                                <?php if(!empty($item['duration'])): ?>
                                    <p class="mt-1 text-xs font-semibold text-slate-500 dark:text-slate-400"><?php echo e($item['duration']); ?></p>
                                <?php endif; ?>
                            </div>
                            <form action="<?php echo e(route('cart.remove', $cartKey)); ?>" method="post" data-cart-form>
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="grid h-9 w-9 place-items-center rounded-2xl bg-rose-50 text-rose-600 transition hover:bg-rose-100 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-rose-500/10 dark:hover:bg-rose-500/15" aria-label="Hapus <?php echo e($item['name']); ?>">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>

                        <div class="mt-3 flex items-end justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Harga</p>
                                <p class="text-sm font-extrabold text-slate-950 dark:text-white"><?php echo e(Money::rupiah($item['price'])); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Subtotal</p>
                                <p class="text-sm font-extrabold text-emerald-700 dark:text-emerald-300"><?php echo e(Money::rupiah($item['price'] * $item['qty'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="<?php echo e(route('cart.update', $cartKey)); ?>" method="post" class="mt-3 flex items-center justify-between gap-3 rounded-2xl bg-slate-50 p-2 dark:bg-white/5" data-cart-form>
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <label class="text-xs font-extrabold uppercase tracking-wide text-slate-500 dark:text-slate-400" for="<?php echo e($cartInputId); ?>">Jumlah</label>
                    <div class="flex items-center gap-2">
                        <input id="<?php echo e($cartInputId); ?>" name="qty" type="number" min="1" max="99" value="<?php echo e($item['qty']); ?>" class="h-10 w-20 rounded-2xl border border-slate-200 bg-white px-3 text-center text-sm font-extrabold outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 dark:border-white/10 dark:bg-white/10">
                        <button class="inline-flex h-10 items-center justify-center rounded-2xl bg-slate-950 px-4 text-xs font-extrabold text-white transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white dark:text-slate-950" data-cart-submit-label="Update">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/partials/cart-items.blade.php ENDPATH**/ ?>