@extends('admin.layouts.app')
@section('page_title', $banner->exists ? 'Edit Banner Slider' : 'Tambah Banner Slider')
@section('page_description','Atur promosi halaman depan dengan gambar, teks, tombol, urutan, dan status tayang.')
@section('content')
@php
    $bannerImagePath = $banner->image_path ?? null;
    $bannerImageUrl = $bannerImagePath
        ? (\Illuminate\Support\Str::startsWith($bannerImagePath, ['http://', 'https://', '/']) ? $bannerImagePath : \Illuminate\Support\Facades\Storage::url($bannerImagePath))
        : null;
    $mobileImagePath = $banner->mobile_image_path ?? null;
    $mobileImageUrl = $mobileImagePath
        ? (\Illuminate\Support\Str::startsWith($mobileImagePath, ['http://', 'https://', '/']) ? $mobileImagePath : \Illuminate\Support\Facades\Storage::url($mobileImagePath))
        : null;
@endphp
<form method="POST" enctype="multipart/form-data" action="{{ $banner->exists ? route('admin.banners.update',$banner) : route('admin.banners.store') }}" class="space-y-5">@csrf @if($banner->exists)@method('PUT')@endif
    <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="mb-5 flex items-start gap-3 border-b border-slate-100 pb-4 dark:border-slate-800">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-teal-50 text-teal-600 dark:bg-teal-400/10 dark:text-teal-300"><i class="ph ph-images text-2xl"></i></span>
            <div>
                <h2 class="font-extrabold">Konten Slide</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Isi pesan promosi yang tampil di atas gambar banner.</p>
            </div>
        </div>
        <div class="grid gap-4 lg:grid-cols-2">
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Label Kecil <span class="font-semibold text-slate-400">opsional</span></span><input name="label" value="{{ old('label',$banner->label) }}" placeholder="Contoh: Promo Bulan Ini" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800"></label>
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Judul Utama</span><input name="title" value="{{ old('title',$banner->title) }}" placeholder="Contoh: Belanja lebih mudah lewat katalog online" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800" required></label>
            <label class="block lg:col-span-2"><span class="mb-2 block text-sm font-extrabold">Kalimat Pendukung</span><textarea name="subtitle" rows="3" placeholder="Jelaskan promo/manfaat secara singkat." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800">{{ old('subtitle',$banner->subtitle) }}</textarea><small class="mt-1 block text-xs text-slate-400">Usahakan singkat agar nyaman dibaca.</small></label>
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Teks Tombol</span><input name="button_text" value="{{ old('button_text',$banner->button_text) }}" placeholder="Lihat Produk" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800"></label>
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Arah Tombol</span><input name="button_url" value="{{ old('button_url',$banner->button_url) }}" placeholder="#produk atau link halaman" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800"><small class="mt-1 block text-xs text-slate-400">Isi tujuan tombol sesuai halaman yang ingin dituju.</small></label>
        </div>
    </section>

    <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="mb-5 flex items-start gap-3 border-b border-slate-100 pb-4 dark:border-slate-800">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-400/10 dark:text-amber-300"><i class="ph ph-image-square text-2xl"></i></span>
            <div>
                <h2 class="font-extrabold">Gambar Slider</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Upload gambar utama dan gambar khusus tampilan HP jika diperlukan.</p>
            </div>
        </div>

        <div class="mb-5 grid gap-3 lg:grid-cols-2">
            <div class="rounded-2xl border border-teal-100 bg-teal-50/70 p-4 text-sm leading-6 text-teal-900 dark:border-teal-400/20 dark:bg-teal-400/10 dark:text-teal-100">
                <b class="block text-xs uppercase tracking-wide">Ukuran desktop</b>
                Gunakan rasio lebar, rekomendasi <b>1920 × 720 px</b> atau rasio <b>16:6</b>. Pastikan teks utama tidak terlalu dekat tepi gambar.
            </div>
            <div class="rounded-2xl border border-amber-100 bg-amber-50/80 p-4 text-sm leading-6 text-amber-900 dark:border-amber-400/20 dark:bg-amber-400/10 dark:text-amber-100">
                <b class="block text-xs uppercase tracking-wide">Ukuran mobile</b>
                Gunakan gambar khusus HP, rekomendasi <b>1080 × 810 px</b> atau rasio <b>4:3</b> agar banner tetap rapi di layar kecil.
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <div class="rounded-[1.35rem] border border-slate-200 p-4 dark:border-slate-800">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-extrabold">Gambar Utama</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Gunakan gambar lebar dengan visual yang jelas. Rekomendasi 1920 × 720 px.</p>
                    </div>
                    <span class="rounded-full bg-teal-50 px-3 py-1 text-[11px] font-extrabold text-teal-700 dark:bg-teal-400/10 dark:text-teal-300">Utama</span>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 dark:border-slate-700 dark:bg-slate-800">
                    <div class="aspect-[16/6] w-full">
                        <img data-preview="desktop" src="{{ $bannerImageUrl ?: '' }}" alt="Gambar utama" class="{{ $bannerImageUrl ? '' : 'hidden' }} h-full w-full object-cover">
                        <div data-placeholder="desktop" class="{{ $bannerImageUrl ? 'hidden' : '' }} flex h-full w-full flex-col items-center justify-center p-5 text-center text-slate-400">
                            <i class="ph ph-image text-4xl"></i>
                            <p class="mt-2 text-xs font-bold">Belum ada gambar utama</p>
                        </div>
                    </div>
                </div>
                @if($bannerImageUrl)
                    <label class="mt-3 flex cursor-pointer items-start gap-2 text-xs font-semibold text-red-600 dark:text-red-300"><input type="checkbox" name="remove_image" value="1" class="mt-0.5"> Hapus gambar utama saat disimpan</label>
                @endif
                <label class="mt-4 inline-flex cursor-pointer items-center justify-center gap-2 rounded-2xl bg-teal-700 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-teal-800 dark:hover:bg-teal-400 hover:-translate-y-0.5 dark:bg-teal-500 dark:text-white"><i class="ph ph-upload-simple"></i> Pilih Gambar Utama<input data-image-input="desktop" name="image" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only"></label>
            </div>

            <div class="rounded-[1.35rem] border border-slate-200 p-4 dark:border-slate-800">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-extrabold">Gambar Tampilan HP</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Gunakan gambar khusus HP dengan tinggi lebih ringkas agar banner tidak terlalu panjang. Rekomendasi 1080 × 810 px.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-extrabold text-slate-500 dark:bg-slate-800 dark:text-slate-300">Pilihan</span>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 dark:border-slate-700 dark:bg-slate-800">
                    <div class="aspect-[4/3] w-full max-w-[280px]">
                        <img data-preview="mobile" src="{{ $mobileImageUrl ?: '' }}" alt="Gambar tampilan HP" class="{{ $mobileImageUrl ? '' : 'hidden' }} h-full w-full object-cover">
                        <div data-placeholder="mobile" class="{{ $mobileImageUrl ? 'hidden' : '' }} flex h-full w-full flex-col items-center justify-center p-5 text-center text-slate-400">
                            <i class="ph ph-device-mobile text-4xl"></i>
                            <p class="mt-2 text-xs font-bold">Belum ada gambar tampilan HP</p>
                        </div>
                    </div>
                </div>
                @if($mobileImageUrl)
                    <label class="mt-3 flex cursor-pointer items-start gap-2 text-xs font-semibold text-red-600 dark:text-red-300"><input type="checkbox" name="remove_mobile_image" value="1" class="mt-0.5"> Hapus gambar tampilan HP saat disimpan</label>
                @endif
                <label class="mt-4 inline-flex cursor-pointer items-center justify-center gap-2 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-extrabold transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800"><i class="ph ph-upload-simple"></i> Pilih Gambar Tampilan HP<input data-image-input="mobile" name="mobile_image" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only"></label>
            </div>
        </div>
    </section>

    <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="mb-5 flex items-start gap-3 border-b border-slate-100 pb-4 dark:border-slate-800">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-300"><i class="ph ph-sliders-horizontal text-2xl"></i></span>
            <div>
                <h2 class="font-extrabold">Pengaturan Tayang</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Atur urutan slide dan status tampil di halaman depan.</p>
            </div>
        </div>
        <div class="grid gap-4 lg:grid-cols-2">
            <label class="block"><span class="mb-2 block text-sm font-extrabold">Urutan Slide</span><input name="sort_order" type="number" value="{{ old('sort_order',$banner->sort_order ?? 0) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800"><small class="mt-1 block text-xs text-slate-400">Angka kecil tampil lebih awal. Boleh dibiarkan 0.</small></label>
            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 dark:border-slate-800"><input type="checkbox" name="is_active" value="1" class="mt-1 h-4 w-4" @checked(old('is_active',$banner->is_active ?? true))><span><b class="block text-sm">Slide Aktif</b><small class="text-slate-500 dark:text-slate-400">Slide tampil di banner halaman depan customer.</small></span></label>
        </div>
        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end"><a href="{{ route('admin.banners.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-6 py-3 text-sm font-extrabold dark:border-slate-700">Batal</a><button class="inline-flex items-center justify-center gap-2 rounded-2xl bg-teal-700 px-6 py-3 text-sm font-extrabold text-white transition hover:bg-teal-800 dark:hover:bg-teal-400 hover:-translate-y-0.5 dark:bg-teal-500 dark:text-white">Simpan Banner Slider <i class="ph ph-check-circle"></i></button></div>
    </section>
</form>
<script>
    document.querySelectorAll('[data-image-input]').forEach((input) => {
        input.addEventListener('change', () => {
            const type = input.dataset.imageInput
            const file = input.files?.[0]
            if (!file || !type) return
            const preview = document.querySelector(`[data-preview="${type}"]`)
            const placeholder = document.querySelector(`[data-placeholder="${type}"]`)
            if (preview) {
                preview.src = URL.createObjectURL(file)
                preview.classList.remove('hidden')
            }
            placeholder?.classList.add('hidden')
        })
    })
</script>
@endsection
