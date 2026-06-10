@extends('admin.layouts.app')
@section('page_title', $category->exists ? 'Edit Kategori' : 'Tambah Kategori')
@section('page_description','Atur nama, icon, urutan, dan status kategori yang tampil di katalog.')
@section('page_action')
<a href="{{ route('admin.categories.index') }}" class="admin-btn-secondary"><i class="ph ph-arrow-left"></i> Kembali</a>
@endsection
@section('content')
@php
    $selectedIcon = old('icon', $category->icon ?: 'ph-tag');
@endphp
<form method="POST" action="{{ $category->exists ? route('admin.categories.update',$category) : route('admin.categories.store') }}" class="space-y-5">
    @csrf
    @if($category->exists)@method('PUT')@endif

    <section class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="bg-slate-950 p-5 text-white sm:p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-3">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-teal-500 text-white shadow-lg shadow-teal-950/30">
                        <i class="ph {{ $selectedIcon }} text-2xl"></i>
                    </span>
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[.22em] text-teal-200">Kategori Produk</p>
                        <h2 class="mt-1 text-xl font-extrabold">{{ $category->exists ? 'Perbarui Kategori' : 'Tambah Kategori Baru' }}</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-300">Buat kategori mudah dikenali agar katalog lebih nyaman dipilih customer.</p>
                    </div>
                </div>
                <span class="inline-flex w-fit items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-extrabold text-teal-100">
                    <i class="ph ph-sparkle"></i> Tampilan katalog
                </span>
            </div>
        </div>

        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[1fr_340px]">
            <div class="space-y-5">
                <label class="block">
                    <span class="mb-2 block text-sm font-extrabold">Nama Kategori</span>
                    <input name="name" value="{{ old('name',$category->name) }}" placeholder="Contoh: Aksesoris Gadget" class="admin-input" required>
                </label>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block">
                        <span class="mb-2 flex items-center justify-between gap-3 text-sm font-extrabold">
                            <span>Nama Icon</span>
                            <a href="https://phosphoricons.com/" target="_blank" rel="noopener" class="inline-flex items-center gap-1 rounded-full bg-teal-50 px-3 py-1 text-[11px] font-extrabold text-teal-700 transition hover:bg-teal-100 dark:bg-teal-400/10 dark:text-teal-300 dark:hover:bg-teal-400/20">
                                Cari Icon <i class="ph ph-arrow-square-out"></i>
                            </a>
                        </span>
                        <input id="categoryIconInput" name="icon" value="{{ $selectedIcon }}" placeholder="Contoh: ph-shopping-bag-open" class="admin-input">
                        <small class="mt-2 block text-xs leading-5 text-slate-500 dark:text-slate-400">Salin nama icon dari Phosphor, misalnya <b>ph-package</b>, <b>ph-device-mobile</b>, atau <b>ph-t-shirt</b>.</small>
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Posisi Tampil</span>
                        <input name="sort_order" type="number" min="1" value="{{ old('sort_order', $category->exists ? max(1, (int) $category->sort_order) : ($nextSortOrder ?? 1)) }}" class="admin-input">
                        <small class="mt-2 block text-xs leading-5 text-slate-500 dark:text-slate-400">Jika posisi diubah, kategori lain akan otomatis digeser agar urutannya tetap rapi.</small>
                    </label>
                </div>

                <label class="flex cursor-pointer items-start gap-3 rounded-3xl border border-slate-200 bg-slate-50 p-4 transition hover:border-teal-200 hover:bg-teal-50/40 dark:border-slate-800 dark:bg-slate-950/40 dark:hover:border-teal-500/40 dark:hover:bg-teal-500/5">
                    <input type="checkbox" name="is_active" value="1" class="mt-1 h-5 w-5 rounded border-slate-300 text-teal-700 focus:ring-teal-600" @checked(old('is_active',$category->is_active ?? true))>
                    <span>
                        <b class="block text-sm text-slate-950 dark:text-white">Kategori Aktif</b>
                        <small class="mt-1 block text-slate-500 dark:text-slate-400">Kategori tampil di katalog dan dapat dipilih untuk produk.</small>
                    </span>
                </label>
            </div>

            <aside class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/40">
                <p class="text-sm font-extrabold text-slate-950 dark:text-white">Preview Kategori</p>
                <div class="mt-4 rounded-[1.35rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center gap-3">
                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-300">
                            <i id="categoryIconPreview" class="ph {{ $selectedIcon }} text-3xl"></i>
                        </span>
                        <div>
                            <p class="text-xs font-bold text-slate-400">Kategori</p>
                            <p class="font-extrabold text-slate-950 dark:text-white">{{ old('name',$category->name) ?: 'Nama kategori' }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 rounded-2xl bg-amber-50 p-4 text-xs font-semibold leading-5 text-amber-700 dark:bg-amber-400/10 dark:text-amber-200">
                    Pilih icon yang mudah dikenali supaya admin dan customer cepat memahami isi kategori.
                </div>
            </aside>
        </div>
    </section>

    <div class="sticky bottom-4 z-10 flex flex-col-reverse gap-3 rounded-3xl border border-slate-200 bg-white/90 p-3 shadow-soft backdrop-blur dark:border-slate-800 dark:bg-slate-900/90 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ route('admin.categories.index') }}" class="admin-btn-secondary">Batal</a>
        <button class="admin-btn-primary"><i class="ph ph-check-circle"></i> Simpan Kategori</button>
    </div>
</form>

@push('scripts')
<script>
    const iconInput = document.getElementById('categoryIconInput')
    const iconPreview = document.getElementById('categoryIconPreview')
    iconInput?.addEventListener('input', () => {
        const value = iconInput.value.trim() || 'ph-tag'
        iconPreview.className = `ph ${value} text-3xl`
    })
</script>
@endpush
@endsection
