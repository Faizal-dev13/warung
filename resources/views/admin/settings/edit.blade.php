@extends('admin.layouts.app')
@section('page_title','Settings')
@section('page_description','Atur identitas toko, logo, kontak, WhatsApp, panduan, dan halaman QnA agar tampilan depan selalu mengikuti data terbaru.')
@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('PUT')

    <section class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="bg-slate-950 p-5 text-white sm:p-6">
            <div class="flex items-start gap-3">
                <span class="grid h-12 w-12 shrink-0 place-items-center overflow-hidden rounded-2xl bg-teal-600 text-white shadow-lg shadow-teal-950/30">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Logo" class="h-full w-full object-cover">
                    @else
                        <i class="ph ph-storefront text-2xl"></i>
                    @endif
                </span>
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[.22em] text-teal-200">Identitas Toko</p>
                    <h2 class="mt-1 text-xl font-extrabold">Informasi utama</h2>
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-300">Data ini dipakai di header, judul halaman, checkout WhatsApp, panduan, dan QnA.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-2">
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Nama Toko</span>
                <input name="store_name" value="{{ old('store_name', $settings['store_name'] ?? '') }}" class="admin-input" required>
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Tagline</span>
                <input name="store_tagline" value="{{ old('store_tagline', $settings['store_tagline'] ?? '') }}" class="admin-input" required>
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Teks Kecil Header</span>
                <input name="header_subtitle" value="{{ old('header_subtitle', $settings['header_subtitle'] ?? '') }}" class="admin-input" placeholder="Produk digital siap checkout WA">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Nomor WhatsApp Admin</span>
                <input name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" class="admin-input" placeholder="6281234567890" required>
                <small class="mt-2 block text-xs text-slate-500 dark:text-slate-400">Gunakan format 62 agar link WhatsApp langsung terbuka dengan benar.</small>
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Email Support <span class="font-semibold text-slate-400">opsional</span></span>
                <input name="support_email" type="email" value="{{ old('support_email', $settings['support_email'] ?? '') }}" class="admin-input" placeholder="support@domain.com">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Alamat / Info Bisnis <span class="font-semibold text-slate-400">opsional</span></span>
                <input name="business_address" value="{{ old('business_address', $settings['business_address'] ?? '') }}" class="admin-input" placeholder="Kota / alamat bisnis">
            </label>
        </div>
    </section>

    <section class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900 sm:p-6">
        <div class="mb-5 flex items-start gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300"><i class="ph ph-image-square text-xl"></i></span>
            <div>
                <h2 class="font-extrabold text-slate-950 dark:text-white">Logo</h2>
                <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">Logo akan dipakai di header frontend dan admin panel.</p>
            </div>
        </div>
        <div class="grid gap-5 lg:grid-cols-[220px_1fr] lg:items-start">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-800 dark:bg-slate-950/40">
                <div class="mx-auto grid h-28 w-28 place-items-center overflow-hidden rounded-3xl bg-white shadow-sm dark:bg-slate-900">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Logo" class="h-full w-full object-cover">
                    @else
                        <i class="ph ph-storefront text-4xl text-slate-400"></i>
                    @endif
                </div>
                @if($logoUrl)
                    <label class="mt-4 flex cursor-pointer items-center justify-center gap-2 rounded-2xl border border-red-200 bg-white px-3 py-2 text-xs font-extrabold text-red-600 dark:border-red-900/60 dark:bg-slate-900">
                        <input type="checkbox" name="remove_logo" value="1" class="rounded border-red-300 text-red-600 focus:ring-red-500">
                        Hapus logo
                    </label>
                @endif
            </div>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Upload Logo Baru</span>
                <input name="logo" type="file" accept="image/*" class="admin-input file:mr-4 file:rounded-xl file:border-0 file:bg-teal-700 file:px-4 file:py-2 file:text-sm file:font-extrabold file:text-white">
                <small class="mt-2 block text-xs leading-5 text-slate-500 dark:text-slate-400">Rekomendasi ukuran persegi, format PNG/JPG/WebP/SVG, maksimal 2MB.</small>
            </label>
        </div>
    </section>

    <section class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900 sm:p-6">
        <div class="mb-5 flex items-start gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-300"><i class="ph ph-book-open-text text-xl"></i></span>
            <div>
                <h2 class="font-extrabold text-slate-950 dark:text-white">Panduan & QnA</h2>
                <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">Ubah judul dan deskripsi halaman bantuan tanpa edit file.</p>
            </div>
        </div>
        <div class="grid gap-5 lg:grid-cols-2">
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Judul Panduan</span>
                <input name="guide_title" value="{{ old('guide_title', $settings['guide_title'] ?? '') }}" class="admin-input" required>
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Judul QnA</span>
                <input name="qna_title" value="{{ old('qna_title', $settings['qna_title'] ?? '') }}" class="admin-input" required>
            </label>
            <label class="block lg:col-span-2">
                <span class="mb-2 block text-sm font-extrabold">Deskripsi Panduan</span>
                <textarea name="guide_subtitle" rows="3" class="admin-textarea">{{ old('guide_subtitle', $settings['guide_subtitle'] ?? '') }}</textarea>
            </label>
            <label class="block lg:col-span-2">
                <span class="mb-2 block text-sm font-extrabold">Deskripsi QnA</span>
                <textarea name="qna_subtitle" rows="3" class="admin-textarea">{{ old('qna_subtitle', $settings['qna_subtitle'] ?? '') }}</textarea>
            </label>
        </div>
    </section>

    <div class="sticky bottom-4 z-10 flex flex-col-reverse gap-3 rounded-3xl border border-slate-200 bg-white/90 p-3 shadow-soft backdrop-blur dark:border-slate-800 dark:bg-slate-900/90 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ route('admin.dashboard') }}" class="admin-btn-secondary">Batal</a>
        <button class="admin-btn-primary"><i class="ph ph-check-circle"></i> Simpan Settings</button>
    </div>
</form>
@endsection
