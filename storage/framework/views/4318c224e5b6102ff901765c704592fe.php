<?php $__env->startSection('page_title', $product->exists ? 'Edit Produk' : 'Tambah Produk'); ?>
<?php $__env->startSection('page_description','Upload foto produk, isi harga, deskripsi, dan status. Admin tidak perlu mengatur warna, gradient, atau kode icon.'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $productImagePath = $product->image_path ?? null;
    $productImageUrl = $productImagePath
        ? (\Illuminate\Support\Str::startsWith($productImagePath, ['http://', 'https://', '/']) ? $productImagePath : \Illuminate\Support\Facades\Storage::url($productImagePath))
        : null;
?>
<form method="POST" enctype="multipart/form-data" action="<?php echo e($product->exists ? route('admin.products.update',$product) : route('admin.products.store')); ?>" class="space-y-5"><?php echo csrf_field(); ?> <?php if($product->exists): ?><?php echo method_field('PUT'); ?><?php endif; ?>
    <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="mb-5 flex items-start gap-3 border-b border-slate-100 pb-4 dark:border-slate-800">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-400/10 dark:text-blue-300"><i class="ph ph-package text-2xl"></i></span>
            <div>
                <h2 class="font-extrabold">Informasi Produk</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Data utama yang dilihat customer di katalog.</p>
            </div>
        </div>
        <div class="grid gap-4 lg:grid-cols-2">
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Nama Produk</span><input name="name" value="<?php echo e(old('name',$product->name)); ?>" placeholder="Contoh: Paket Hampers Premium" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40" required></label>
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Kategori</span><select name="category_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40" required><option value="">Pilih kategori</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->id); ?>" <?php if(old('category_id',$product->category_id)==$category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Harga Jual</span><input name="price" type="number" value="<?php echo e(old('price',$product->price)); ?>" placeholder="150000" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40" required><small class="mt-1 block text-xs text-slate-400">Tulis angka saja, tanpa titik/koma.</small></label>
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Harga Sebelum Promo <span class="font-semibold text-slate-400">opsional</span></span><input name="old_price" type="number" value="<?php echo e(old('old_price',$product->old_price)); ?>" placeholder="200000" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40"><small class="mt-1 block text-xs text-slate-400">Kosongkan kalau tidak ada harga coret.</small></label>
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Label Produk <span class="font-semibold text-slate-400">opsional</span></span><input name="badge" value="<?php echo e(old('badge',$product->badge)); ?>" placeholder="Contoh: Best Seller / Promo" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40"></label>
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Posisi Tampil</span><input name="sort_order" type="number" value="<?php echo e(old('sort_order',$product->sort_order ?? 0)); ?>" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40"><small class="mt-1 block text-xs text-slate-400">Angka kecil tampil lebih awal. Boleh dibiarkan 0.</small></label>
        </div>
    </section>

    <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="mb-5 flex items-start gap-3 border-b border-slate-100 pb-4 dark:border-slate-800">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-violet-50 text-violet-600 dark:bg-violet-400/10 dark:text-violet-300"><i class="ph ph-image-square text-2xl"></i></span>
            <div>
                <h2 class="font-extrabold">Foto Produk</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Gunakan foto produk asli agar katalog terlihat lebih profesional. Tidak ada pengaturan warna atau gradient.</p>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-[.9fr_1.1fr] lg:items-stretch">
            <div class="overflow-hidden rounded-[1.35rem] border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/60">
                <div class="relative aspect-[4/3] w-full overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900">
                    <img data-image-preview src="<?php echo e($productImageUrl ?: ''); ?>" alt="Preview foto produk" class="<?php echo e($productImageUrl ? '' : 'hidden'); ?> h-full w-full object-cover">
                    <div data-image-placeholder class="<?php echo e($productImageUrl ? 'hidden' : ''); ?> flex h-full w-full flex-col items-center justify-center p-6 text-center text-slate-400">
                        <i class="ph ph-image text-5xl"></i>
                        <p class="mt-3 text-sm font-bold">Belum ada foto produk</p>
                        <p class="mt-1 text-xs leading-5">Preview foto akan tampil di sini setelah dipilih.</p>
                    </div>
                </div>
                <?php if($productImageUrl): ?>
                    <div class="border-t border-slate-200 p-3 dark:border-slate-700">
                        <label class="flex cursor-pointer items-start gap-2 text-xs font-semibold text-red-600 dark:text-red-300"><input type="checkbox" name="remove_image" value="1" class="mt-0.5"> Hapus foto produk saat disimpan</label>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex flex-col justify-between rounded-[1.35rem] border border-dashed border-slate-300 bg-white p-5 dark:border-slate-700 dark:bg-slate-900/70">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-950 dark:text-white">Upload Foto Produk</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">Pilih gambar JPG, PNG, atau WEBP. Rekomendasi ukuran 800 x 800 px atau rasio kotak agar card produk rapi.</p>
                    <ul class="mt-4 grid gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <li class="flex items-center gap-2"><i class="ph ph-check-circle text-emerald-500"></i> Maksimal 3 MB</li>
                        <li class="flex items-center gap-2"><i class="ph ph-check-circle text-emerald-500"></i> Background foto sebaiknya bersih</li>
                        <li class="flex items-center gap-2"><i class="ph ph-check-circle text-emerald-500"></i> Foto akan dipakai di katalog dan tabel admin</li>
                    </ul>
                </div>
                <label class="mt-5 inline-flex cursor-pointer items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:shadow-md dark:bg-white dark:text-slate-950">
                    <i class="ph ph-upload-simple"></i> Pilih Foto
                    <input data-image-input name="image" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only">
                </label>
            </div>
        </div>
    </section>

    <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="mb-5 flex items-start gap-3 border-b border-slate-100 pb-4 dark:border-slate-800">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-300"><i class="ph ph-text-aa text-2xl"></i></span>
            <div>
                <h2 class="font-extrabold">Konten Produk</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Gunakan kalimat yang jelas agar customer cepat paham manfaat produk.</p>
            </div>
        </div>
        <div class="grid gap-4">
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Ringkasan Singkat</span><textarea name="summary" rows="3" placeholder="Tulis ringkasan singkat yang muncul di card produk." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40" required><?php echo e(old('summary',$product->summary)); ?></textarea><small class="mt-1 block text-xs text-slate-400">Usahakan 1-2 kalimat saja agar card produk tidak terlalu panjang.</small></label>
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Deskripsi Lengkap</span><textarea name="description" rows="7" placeholder="Jelaskan detail produk, manfaat, dan informasi penting untuk customer." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40" required><?php echo e(old('description',$product->description)); ?></textarea></label>
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Poin Fitur / Benefit <span class="font-semibold text-slate-400">opsional</span></span><textarea name="features" rows="5" placeholder="Contoh:&#10;Free konsultasi awal&#10;Revisi 2x&#10;Support setelah pembelian" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-slate-950 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40"><?php echo e(old('features', is_array($product->features) ? implode("\n", $product->features) : '')); ?></textarea><small class="mt-1 block text-xs text-slate-400">Tulis satu poin per baris.</small></label>
        </div>
    </section>

    <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <h2 class="font-extrabold">Status Produk</h2>
        <div class="mt-4 grid gap-3 md:grid-cols-3">
            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 dark:border-slate-800"><input type="checkbox" name="is_latest" value="1" class="mt-1 h-4 w-4" <?php if(old('is_latest',$product->is_latest)): echo 'checked'; endif; ?>><span><b class="block text-sm">Tampilkan di Terbaru</b><small class="text-slate-500 dark:text-slate-400">Muncul di section produk terbaru.</small></span></label>
            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 dark:border-slate-800"><input type="checkbox" name="is_featured" value="1" class="mt-1 h-4 w-4" <?php if(old('is_featured',$product->is_featured)): echo 'checked'; endif; ?>><span><b class="block text-sm">Produk Unggulan</b><small class="text-slate-500 dark:text-slate-400">Tandai sebagai pilihan rekomendasi.</small></span></label>
            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 dark:border-slate-800"><input type="checkbox" name="is_active" value="1" class="mt-1 h-4 w-4" <?php if(old('is_active',$product->is_active ?? true)): echo 'checked'; endif; ?>><span><b class="block text-sm">Aktif</b><small class="text-slate-500 dark:text-slate-400">Produk tampil di halaman customer.</small></span></label>
        </div>
        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <a href="<?php echo e(route('admin.products.index')); ?>" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-6 py-3 text-sm font-extrabold dark:border-slate-700">Batal</a>
            <button class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-950 px-6 py-3 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:bg-white dark:text-slate-950">Simpan Produk <i class="ph ph-check-circle"></i></button>
        </div>
    </section>
</form>
<script>
    document.querySelectorAll('[data-image-input]').forEach((input) => {
        input.addEventListener('change', () => {
            const file = input.files?.[0]
            if (!file) return
            const preview = document.querySelector('[data-image-preview]')
            const placeholder = document.querySelector('[data-image-placeholder]')
            if (preview) {
                preview.src = URL.createObjectURL(file)
                preview.classList.remove('hidden')
            }
            placeholder?.classList.add('hidden')
        })
    })
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/faizal/Projects/laravel/digitalkit/resources/views/admin/products/form.blade.php ENDPATH**/ ?>