@extends('admin.layouts.app')
@section('page_title', $product->exists ? 'Edit Produk' : 'Tambah Produk')
@section('page_description','Lengkapi foto, harga, deskripsi, dan status produk sebelum ditampilkan di katalog.')
@section('page_action')
<a href="{{ route('admin.products.index') }}" class="admin-btn-secondary"><i class="ph ph-arrow-left"></i> Kembali</a>
@endsection
@section('content')
@php
    $productImagePath = $product->image_path ?? null;
    $productImageUrl = $productImagePath
        ? (\Illuminate\Support\Str::startsWith($productImagePath, ['http://', 'https://', '/']) ? $productImagePath : \Illuminate\Support\Facades\Storage::url($productImagePath))
        : null;
@endphp
<form method="POST" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update',$product) : route('admin.products.store') }}" class="space-y-5">
    @csrf
    @if($product->exists)@method('PUT')@endif

    <section class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="bg-slate-950 p-5 text-white sm:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-3">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-teal-500 text-white shadow-lg shadow-teal-950/30"><i class="ph ph-package text-2xl"></i></span>
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[.22em] text-teal-200">Produk Katalog</p>
                        <h2 class="mt-1 text-xl font-extrabold">{{ $product->exists ? 'Perbarui Produk' : 'Tambah Produk Baru' }}</h2>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-300">Isi data produk yang jelas agar customer mudah memahami produk dan cepat mengambil keputusan.</p>
                    </div>
                </div>
                <label class="flex w-fit cursor-pointer items-center gap-3 rounded-2xl border border-white/10 bg-white/10 px-4 py-3">
                    <input type="checkbox" name="is_active" value="1" class="h-5 w-5 rounded border-white/20 text-teal-500 focus:ring-teal-400" @checked(old('is_active',$product->is_active ?? true))>
                    <span class="text-sm font-extrabold">Produk Aktif</span>
                </label>
            </div>
        </div>

        <div class="grid gap-6 p-5 sm:p-6 xl:grid-cols-[1fr_360px]">
            <div class="space-y-5">
                <div class="grid gap-4 lg:grid-cols-2">
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Nama Produk</span>
                        <input name="name" value="{{ old('name',$product->name) }}" placeholder="Contoh: Earphone Full Bass" class="admin-input" required>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Kategori</span>
                        <select name="category_id" class="admin-select" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id',$product->category_id)==$category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Harga Jual</span>
                        <input name="price" type="number" min="0" value="{{ old('price',$product->price) }}" placeholder="150000" class="admin-input" required>
                        <small class="mt-2 block text-xs text-slate-500 dark:text-slate-400">Tulis angka saja, tanpa titik/koma.</small>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Harga Sebelum Promo <span class="font-semibold text-slate-400">opsional</span></span>
                        <input name="old_price" type="number" min="0" value="{{ old('old_price',$product->old_price) }}" placeholder="200000" class="admin-input">
                        <small class="mt-2 block text-xs text-slate-500 dark:text-slate-400">Kosongkan kalau tidak ada harga coret.</small>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Label Produk <span class="font-semibold text-slate-400">opsional</span></span>
                        <input name="badge" value="{{ old('badge',$product->badge) }}" placeholder="Contoh: Promo / Best Seller" class="admin-input">
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Posisi Tampil</span>
                        <input name="sort_order" type="number" min="0" value="{{ old('sort_order',$product->sort_order ?? 0) }}" class="admin-input">
                        <small class="mt-2 block text-xs text-slate-500 dark:text-slate-400">Angka kecil tampil lebih awal. Boleh dibiarkan 0.</small>
                    </label>
                </div>

                <div class="grid gap-4">
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Ringkasan Singkat</span>
                        <textarea name="summary" rows="3" placeholder="Tulis ringkasan singkat yang muncul di katalog." class="admin-textarea" required>{{ old('summary',$product->summary) }}</textarea>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Deskripsi Lengkap</span>
                        <textarea name="description" rows="6" placeholder="Jelaskan manfaat, detail, dan informasi penting produk." class="admin-textarea" required>{{ old('description',$product->description) }}</textarea>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Poin Kelebihan <span class="font-semibold text-slate-400">opsional</span></span>
                        <textarea name="features" rows="5" placeholder="Satu poin per baris" class="admin-textarea">{{ old('features', collect($product->features ?? [])->implode("\n")) }}</textarea>
                        <small class="mt-2 block text-xs leading-5 text-slate-500 dark:text-slate-400">Contoh: Garansi resmi, Stok siap kirim, Cocok untuk hadiah.</small>
                    </label>
                </div>
            </div>

            <aside class="space-y-4">
                <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950/40">
                    <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100 dark:bg-slate-800">
                        <img data-image-preview src="{{ $productImageUrl ?: '' }}" alt="Foto produk" class="{{ $productImageUrl ? '' : 'hidden' }} h-full w-full object-cover">
                        <div data-image-placeholder class="{{ $productImageUrl ? 'hidden' : '' }} flex h-full w-full flex-col items-center justify-center p-6 text-center text-slate-400">
                            <i class="ph ph-image text-5xl"></i>
                            <p class="mt-3 text-sm font-extrabold">Belum ada foto produk</p>
                            <p class="mt-1 text-xs leading-5">Foto yang dipilih akan tampil di sini.</p>
                        </div>
                    </div>
                    <div class="space-y-3 p-4">
                        <label class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-2xl bg-teal-700 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-teal-800 dark:bg-teal-500 dark:hover:bg-teal-400">
                            <i class="ph ph-upload-simple"></i> Pilih Foto Produk
                            <input data-image-input name="image" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only">
                        </label>
                        @if($productImageUrl)
                            <label class="flex cursor-pointer items-start gap-2 rounded-2xl bg-red-50 p-3 text-xs font-semibold text-red-600 dark:bg-red-400/10 dark:text-red-300"><input type="checkbox" name="remove_image" value="1" class="mt-0.5"> Hapus foto produk saat disimpan</label>
                        @endif
                    </div>
                </div>


                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-extrabold">Varian Produk</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">Tambahkan pilihan seperti 1 bulan, 2 bulan, atau paket lain dengan harga masing-masing.</p>
                        </div>
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-300"><i class="ph ph-stack text-xl"></i></span>
                    </div>
                    @if($product->exists)
                        @php
                            $variants = $product->variants ?? collect();
                        @endphp
                        <div class="mt-4 grid gap-2">
                            @forelse($variants->take(3) as $variant)
                                <div class="flex items-center justify-between gap-3 rounded-2xl bg-slate-50 p-3 text-sm dark:bg-slate-800/60">
                                    <div class="min-w-0">
                                        <p class="truncate font-extrabold text-slate-950 dark:text-white">{{ $variant->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Rp {{ number_format($variant->price,0,',','.') }}</p>
                                    </div>
                                    <span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold {{ $variant->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-200 text-slate-500 dark:bg-slate-700 dark:text-slate-300' }}">{{ $variant->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-300 p-4 text-center text-sm font-semibold text-slate-500 dark:border-slate-700 dark:text-slate-400">Belum ada varian.</div>
                            @endforelse
                        </div>
                        <div class="mt-4 grid gap-2">
                            <a href="{{ route('admin.variants.create', ['product_id' => $product->id]) }}" class="admin-btn-primary w-full"><i class="ph ph-plus-circle"></i> Tambah Varian</a>
                            <a href="{{ route('admin.variants.index', ['product_id' => $product->id]) }}" class="admin-btn-secondary w-full"><i class="ph ph-list-bullets"></i> Kelola Varian</a>
                        </div>
                    @else
                        <div class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm leading-6 text-slate-500 dark:bg-slate-800/60 dark:text-slate-400">Simpan produk terlebih dahulu, lalu tambahkan varian dari menu Varian.</div>
                    @endif
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-400/10 dark:text-amber-300"><i class="ph ph-lightbulb text-xl"></i></span>
                        <div>
                            <p class="font-extrabold">Tips Foto</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">JPG, PNG, atau WEBP maksimal 3 MB.</p>
                        </div>
                    </div>
                    <ul class="mt-4 grid gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <li class="flex items-center gap-2"><i class="ph ph-check-circle text-emerald-500"></i> Gunakan foto jelas dan terang</li>
                        <li class="flex items-center gap-2"><i class="ph ph-check-circle text-emerald-500"></i> Pastikan produk terlihat penuh</li>
                        <li class="flex items-center gap-2"><i class="ph ph-check-circle text-emerald-500"></i> Background bersih terlihat lebih profesional</li>
                    </ul>
                </div>
            </aside>
        </div>
    </section>

    <div class="sticky bottom-4 z-10 flex flex-col-reverse gap-3 rounded-3xl border border-slate-200 bg-white/90 p-3 shadow-soft backdrop-blur dark:border-slate-800 dark:bg-slate-900/90 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ route('admin.products.index') }}" class="admin-btn-secondary">Batal</a>
        <button class="admin-btn-primary"><i class="ph ph-check-circle"></i> Simpan Produk</button>
    </div>
</form>

@push('scripts')
<script>
    document.querySelector('[data-image-input]')?.addEventListener('change', (event) => {
        const file = event.target.files?.[0]
        if (!file) return
        const preview = document.querySelector('[data-image-preview]')
        const placeholder = document.querySelector('[data-image-placeholder]')
        if (preview) {
            preview.src = URL.createObjectURL(file)
            preview.classList.remove('hidden')
        }
        placeholder?.classList.add('hidden')
    })
</script>
@endpush
@endsection
