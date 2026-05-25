<?php
    use App\Support\Money;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $cart = $cart ?? [];
    $items = $cart['items'] ?? [];
?>

<?php if(empty($items)): ?>
    <div class="grid min-h-72 place-items-center rounded-3xl border border-dashed border-slate-300 bg-slate-50/60 p-8 text-center dark:border-white/15 dark:bg-white/5">
        <div>
            <span class="mx-auto grid h-16 w-16 place-items-center rounded-3xl bg-white text-slate-400 shadow-sm dark:bg-white/10">
                <i class="ph ph-shopping-bag-open text-4xl"></i>
            </span>
            <p class="mt-4 font-extrabold text-slate-950 dark:text-white">Keranjang masih kosong</p>
            <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">Pilih produk dulu, lalu lanjut checkout via WhatsApp.</p>
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
            ?>

            <div class="rounded-3xl border border-slate-200 bg-white p-3.5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-4">
                <div class="flex gap-3">
                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl bg-slate-100 ring-1 ring-slate-100 dark:bg-white/10 dark:ring-white/10">
                        <?php if($cartImageUrl): ?>
                            <img src="<?php echo e($cartImageUrl); ?>" alt="<?php echo e($item['name']); ?>" class="h-full w-full object-cover" loading="lazy">
                        <?php else: ?>
                            <div class="grid h-full w-full place-items-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-500 dark:from-white/10 dark:to-white/5 dark:text-slate-300">
                                <i class="ph <?php echo e($item['icon'] ?? 'ph-package'); ?> text-2xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="line-clamp-2 text-sm font-extrabold leading-snug text-slate-950 dark:text-white"><?php echo e($item['name']); ?></p>
                        <p class="mt-1 text-xs font-semibold text-slate-500 dark:text-slate-400"><?php echo e(Money::rupiah($item['price'])); ?></p>
                        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Subtotal item: <?php echo e(Money::rupiah($item['price'] * $item['qty'])); ?></p>
                    </div>
                </div>

                <div class="mt-3 flex items-center justify-between gap-2">
                    <form action="<?php echo e(route('cart.update', $item['id'])); ?>" method="post" class="flex min-w-0 flex-1 items-center gap-2" data-cart-form>
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <label class="sr-only" for="cart-qty-<?php echo e($item['id']); ?>">Jumlah <?php echo e($item['name']); ?></label>
                        <input id="cart-qty-<?php echo e($item['id']); ?>" name="qty" type="number" min="1" max="99" value="<?php echo e($item['qty']); ?>" class="h-10 w-20 rounded-2xl border border-slate-200 bg-slate-50 px-3 text-sm font-bold outline-none transition focus:border-slate-950 focus:bg-white dark:border-white/10 dark:bg-white/10 dark:focus:border-white/40">
                        <button class="inline-flex h-10 items-center justify-center rounded-2xl bg-slate-100 px-3 text-xs font-extrabold text-slate-700 transition hover:bg-slate-200 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white/10 dark:text-white dark:hover:bg-white/20" data-cart-submit-label="Update">
                            Update
                        </button>
                    </form>

                    <form action="<?php echo e(route('cart.remove', $item['id'])); ?>" method="post" data-cart-form>
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="inline-flex h-10 items-center justify-center rounded-2xl bg-rose-50 px-3 text-xs font-extrabold text-rose-600 transition hover:bg-rose-100 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-rose-500/10 dark:hover:bg-rose-500/15" data-cart-submit-label="Hapus">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/partials/cart-items.blade.php ENDPATH**/ ?>