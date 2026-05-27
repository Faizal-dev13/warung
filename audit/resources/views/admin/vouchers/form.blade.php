@extends('admin.layouts.app')
@section('page_title', $voucher->exists ? 'Edit Voucher' : 'Tambah Voucher')
@section('page_description','Atur kode promo, nilai diskon, masa berlaku, dan status voucher.')
@section('page_action')
<a href="{{ route('admin.vouchers.index') }}" class="admin-btn-secondary"><i class="ph ph-arrow-left"></i> Kembali</a>
@endsection
@section('content')
<form method="POST" action="{{ $voucher->exists ? route('admin.vouchers.update',$voucher) : route('admin.vouchers.store') }}" class="space-y-5">
    @csrf
    @if($voucher->exists)@method('PUT')@endif

    <section class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="bg-slate-950 p-5 text-white sm:p-6">
            <div class="flex items-start gap-3">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-amber-400 text-slate-950 shadow-lg shadow-amber-950/20"><i class="ph ph-ticket text-2xl"></i></span>
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[.22em] text-amber-200">Voucher Promo</p>
                    <h2 class="mt-1 text-xl font-extrabold">{{ $voucher->exists ? 'Perbarui Voucher' : 'Tambah Voucher Baru' }}</h2>
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-300">Buat promo yang jelas agar customer mudah menggunakannya saat checkout.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-2">
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Kode Voucher</span>
                <input name="code" value="{{ old('code',$voucher->code) }}" placeholder="PROMO10" class="admin-input uppercase" required>
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Nama Promo</span>
                <input name="label" value="{{ old('label',$voucher->label) }}" placeholder="Diskon pembelian pertama" class="admin-input" required>
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Tipe Diskon</span>
                <select name="type" class="admin-select" required>
                    <option value="fixed" @selected(old('type',$voucher->type ?: 'fixed') === 'fixed')>Nominal Rupiah</option>
                    <option value="percent" @selected(old('type',$voucher->type) === 'percent')>Persentase</option>
                </select>
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Nilai Diskon</span>
                <input name="value" type="number" min="0" value="{{ old('value',$voucher->value) }}" placeholder="10000 atau 10" class="admin-input" required>
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Minimum Belanja</span>
                <input name="minimum_order" type="number" min="0" value="{{ old('minimum_order',$voucher->minimum_order ?? 0) }}" class="admin-input">
                <small class="mt-2 block text-xs text-slate-500 dark:text-slate-400">Isi 0 jika tidak ada minimal belanja.</small>
            </label>
            <label class="flex cursor-pointer items-start gap-3 rounded-3xl border border-slate-200 bg-slate-50 p-4 transition hover:border-teal-200 hover:bg-teal-50/40 dark:border-slate-800 dark:bg-slate-950/40">
                <input type="checkbox" name="is_active" value="1" class="mt-1 h-5 w-5 rounded border-slate-300 text-teal-700 focus:ring-teal-600" @checked(old('is_active',$voucher->is_active ?? true))>
                <span><b class="block text-sm text-slate-950 dark:text-white">Voucher Aktif</b><small class="mt-1 block text-slate-500 dark:text-slate-400">Voucher bisa digunakan customer.</small></span>
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Mulai Berlaku <span class="font-semibold text-slate-400">opsional</span></span>
                <input name="starts_at" type="datetime-local" value="{{ old('starts_at', optional($voucher->starts_at)->format('Y-m-d\TH:i')) }}" class="admin-input">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Berakhir <span class="font-semibold text-slate-400">opsional</span></span>
                <input name="ends_at" type="datetime-local" value="{{ old('ends_at', optional($voucher->ends_at)->format('Y-m-d\TH:i')) }}" class="admin-input">
            </label>
        </div>
    </section>

    <div class="sticky bottom-4 z-10 flex flex-col-reverse gap-3 rounded-3xl border border-slate-200 bg-white/90 p-3 shadow-soft backdrop-blur dark:border-slate-800 dark:bg-slate-900/90 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ route('admin.vouchers.index') }}" class="admin-btn-secondary">Batal</a>
        <button class="admin-btn-primary"><i class="ph ph-check-circle"></i> Simpan Voucher</button>
    </div>
</form>
@endsection
