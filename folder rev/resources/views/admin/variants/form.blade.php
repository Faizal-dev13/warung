@extends('admin.layouts.app')
@section('page_title', $variant->exists ? 'Edit Varian' : 'Tambah Varian')
@section('page_description','Atur pilihan produk seperti durasi, paket, stok, dan harga per varian.')
@section('page_action')
<a href="{{ route('admin.variants.index') }}" class="admin-btn-secondary"><i class="ph ph-arrow-left"></i> Kembali</a>
@endsection
@section('content')
<form method="POST" action="{{ $variant->exists ? route('admin.variants.update',$variant) : route('admin.variants.store') }}" class="space-y-5">
    @csrf
    @if($variant->exists)@method('PUT')@endif

    <section class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="bg-slate-950 p-5 text-white sm:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-3">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-teal-500 text-white shadow-lg shadow-teal-950/30"><i class="ph ph-stack text-2xl"></i></span>
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[.22em] text-teal-200">Varian Produk</p>
                        <h2 class="mt-1 text-xl font-extrabold">{{ $variant->exists ? 'Perbarui Varian' : 'Tambah Varian Baru' }}</h2>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-300">Cocok untuk produk dengan pilihan durasi, paket, jumlah akun, atau harga berbeda.</p>
                    </div>
                </div>
                <label class="flex w-fit cursor-pointer items-center gap-3 rounded-2xl border border-white/10 bg-white/10 px-4 py-3">
                    <input type="checkbox" name="is_active" value="1" class="h-5 w-5 rounded border-white/20 text-teal-500 focus:ring-teal-400" @checked(old('is_active',$variant->is_active ?? true))>
                    <span class="text-sm font-extrabold">Varian Aktif</span>
                </label>
            </div>
        </div>

        <div class="grid gap-6 p-5 sm:p-6 xl:grid-cols-[1fr_340px]">
            <div class="space-y-5">
                <div class="grid gap-4 lg:grid-cols-2">
                    <label class="block lg:col-span-2">
                        <span class="mb-2 block text-sm font-extrabold">Produk</span>
                        <select name="product_id" class="admin-select" required>
                            <option value="">Pilih produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" @selected(old('product_id',$variant->product_id)==$product->id)>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Nama Varian</span>
                        <input name="name" value="{{ old('name',$variant->name) }}" placeholder="Contoh: 1 Bulan" class="admin-input" required>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Durasi / Keterangan <span class="font-semibold text-slate-400">opsional</span></span>
                        <input name="duration" value="{{ old('duration',$variant->duration) }}" placeholder="Contoh: 30 hari / 2 bulan" class="admin-input">
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Harga Varian</span>
                        <input name="price" type="number" min="0" value="{{ old('price',$variant->price) }}" placeholder="35000" class="admin-input" required>
                        <small class="mt-2 block text-xs text-slate-500 dark:text-slate-400">Tulis angka saja, tanpa titik/koma.</small>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Harga Sebelum Promo <span class="font-semibold text-slate-400">opsional</span></span>
                        <input name="old_price" type="number" min="0" value="{{ old('old_price',$variant->old_price) }}" placeholder="50000" class="admin-input">
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Stok <span class="font-semibold text-slate-400">opsional</span></span>
                        <input name="stock" type="number" min="0" value="{{ old('stock',$variant->stock) }}" placeholder="Kosongkan jika tidak dibatasi" class="admin-input">
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">SKU / Kode Internal <span class="font-semibold text-slate-400">opsional</span></span>
                        <input name="sku" value="{{ old('sku',$variant->sku) }}" placeholder="CANVA-1B" class="admin-input">
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Posisi Tampil</span>
                        <input name="sort_order" type="number" min="1" value="{{ old('sort_order', $variant->exists ? max(1, (int) $variant->sort_order) : ($nextSortOrder ?? 1)) }}" class="admin-input">
                        <small class="mt-2 block text-xs leading-5 text-slate-500 dark:text-slate-400">Urutan berlaku per produk. Jika diisi 2, varian di posisi 2 akan otomatis bergeser.</small>
                    </label>
                    <label class="block lg:col-span-2">
                        <span class="mb-2 block text-sm font-extrabold">Catatan Varian <span class="font-semibold text-slate-400">opsional</span></span>
                        <textarea name="description" rows="4" placeholder="Contoh: Garansi 1 bulan, proses aktivasi maksimal 1x24 jam." class="admin-textarea">{{ old('description',$variant->description) }}</textarea>
                    </label>
                </div>
            </div>

            <aside class="space-y-4">
                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/40">
                    <div class="flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-400/10 dark:text-amber-300"><i class="ph ph-lightbulb text-xl"></i></span>
                        <div>
                            <p class="font-extrabold">Contoh Pemakaian</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Canva Pro, Netflix, paket langganan, atau layanan digital.</p>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2 text-sm font-semibold text-slate-600 dark:text-slate-300">
                        <div class="rounded-2xl bg-white p-3 dark:bg-slate-900">1 Bulan — Rp 35.000</div>
                        <div class="rounded-2xl bg-white p-3 dark:bg-slate-900">2 Bulan — Rp 65.000</div>
                        <div class="rounded-2xl bg-white p-3 dark:bg-slate-900">1 Tahun — Rp 250.000</div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-teal-100 bg-teal-50 p-5 text-sm leading-6 text-teal-800 dark:border-teal-500/20 dark:bg-teal-500/10 dark:text-teal-200">
                    Customer akan memilih varian ini sebelum produk masuk ke keranjang.
                </div>
            </aside>
        </div>
    </section>

    <div class="sticky bottom-4 z-20 flex flex-col gap-2 rounded-[1.5rem] border border-slate-200 bg-white/95 p-3 shadow-soft backdrop-blur dark:border-slate-800 dark:bg-slate-900/95 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.variants.index') }}" class="admin-btn-secondary sm:min-w-32">Batal</a>
        <button class="admin-btn-primary sm:min-w-40"><i class="ph ph-floppy-disk"></i> Simpan Varian</button>
    </div>
</form>
@endsection
