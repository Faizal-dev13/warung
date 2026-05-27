@extends('admin.layouts.app')
@section('page_title','Voucher')
@section('page_description','Kelola kode promo, nilai diskon, minimum belanja, dan status voucher.')
@section('page_action')
<a href="{{ route('admin.vouchers.create') }}" class="admin-btn-primary"><i class="ph ph-plus-circle"></i> Tambah Voucher</a>
@endsection
@section('content')
<form method="GET" action="{{ route('admin.vouchers.index') }}" class="mb-5 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <input type="hidden" name="sort" value="{{ $filters['sort'] ?? 'created_at' }}">
    <input type="hidden" name="direction" value="{{ $filters['direction'] ?? 'desc' }}">
    <div class="grid gap-3 xl:grid-cols-[1fr_170px_160px_140px_auto]">
        <label class="relative block"><i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari voucher..." class="admin-input h-12 pl-11"></label>
        <select name="status" class="admin-select h-12"><option value="" @selected(($filters['status'] ?? '') === '')>Semua status</option><option value="active" @selected(($filters['status'] ?? '') === 'active')>Aktif</option><option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Nonaktif</option></select>
        <select name="type" class="admin-select h-12"><option value="" @selected(($filters['type'] ?? '') === '')>Semua tipe</option><option value="fixed" @selected(($filters['type'] ?? '') === 'fixed')>Nominal</option><option value="percent" @selected(($filters['type'] ?? '') === 'percent')>Persen</option></select>
        <select name="per_page" class="admin-select h-12">@foreach([10,25,50] as $size)<option value="{{ $size }}" @selected(($filters['per_page'] ?? 10) == $size)>{{ $size }} data</option>@endforeach</select>
        <div class="grid grid-cols-2 gap-2 sm:flex"><button class="admin-btn-primary h-12"><i class="ph ph-funnel"></i> Terapkan</button><a href="{{ route('admin.vouchers.index') }}" class="admin-btn-secondary h-12">Reset</a></div>
    </div>
</form>

<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="flex flex-col gap-3 border-b border-slate-100 p-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
        <div><h2 class="font-extrabold">Daftar Voucher</h2><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pantau promo aktif dan voucher yang bisa digunakan customer.</p></div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $vouchers->total() }} voucher</span>
    </div>

    <div class="grid gap-3 p-4 lg:hidden">
        @forelse($vouchers as $voucher)
            <article class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-extrabold text-slate-950 dark:text-white">{{ $voucher->code }}</p>
                        <p class="mt-1 text-sm font-semibold text-slate-600 dark:text-slate-300">{{ $voucher->label }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-[11px] font-extrabold {{ $voucher->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-200 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">{{ $voucher->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3 rounded-2xl bg-white p-3 text-sm dark:bg-slate-900">
                    <div><p class="text-xs font-bold text-slate-400">Diskon</p><p class="mt-1 font-extrabold">{{ $voucher->type === 'percent' ? $voucher->value.'%' : 'Rp '.number_format($voucher->value,0,',','.') }}</p></div>
                    <div><p class="text-xs font-bold text-slate-400">Min. Belanja</p><p class="mt-1 font-extrabold">Rp {{ number_format($voucher->minimum_order ?? 0,0,',','.') }}</p></div>
                    <div class="col-span-2"><p class="text-xs font-bold text-slate-400">Berlaku Untuk</p><p class="mt-1 line-clamp-2 font-extrabold">{{ $voucher->products->isEmpty() ? 'Semua Produk' : $voucher->products->pluck('name')->join(', ') }}</p></div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <a class="admin-btn-secondary h-11 text-xs" href="{{ route('admin.vouchers.edit',$voucher) }}"><i class="ph ph-pencil-simple"></i> Edit</a>
                    <form method="POST" action="{{ route('admin.vouchers.destroy',$voucher) }}" onsubmit="return confirm('Hapus voucher ini?')">@csrf @method('DELETE')<button class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-2xl border border-red-200 bg-white px-3 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:bg-slate-900 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form>
                </div>
            </article>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-center dark:border-slate-700"><i class="ph ph-ticket text-4xl text-slate-400"></i><p class="mt-3 font-extrabold">Voucher belum tersedia</p><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tambahkan voucher untuk membuat promo.</p></div>
        @endforelse
    </div>

    <div class="admin-scrollbar hidden overflow-x-auto lg:block"><table class="w-full min-w-[980px] text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400"><tr><th class="px-5 py-4">@include('admin.partials.sort-link', ['sort' => 'code', 'label' => 'Kode', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'])</th><th>@include('admin.partials.sort-link', ['sort' => 'label', 'label' => 'Nama Promo', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'])</th><th>@include('admin.partials.sort-link', ['sort' => 'type', 'label' => 'Tipe', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'])</th><th>@include('admin.partials.sort-link', ['sort' => 'value', 'label' => 'Nilai', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'])</th><th>@include('admin.partials.sort-link', ['sort' => 'minimum_order', 'label' => 'Min. Belanja', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'])</th><th>Berlaku Untuk</th><th>@include('admin.partials.sort-link', ['sort' => 'status', 'label' => 'Status', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'])</th><th class="pr-5 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100 dark:divide-slate-800">
    @forelse($vouchers as $voucher)
        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
            <td class="px-5 py-4 font-extrabold text-slate-950 dark:text-white">{{ $voucher->code }}</td>
            <td><div class="max-w-[260px]"><p class="font-bold">{{ $voucher->label }}</p>@if($voucher->starts_at || $voucher->ends_at)<p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ optional($voucher->starts_at)->format('d M Y') ?: 'Mulai sekarang' }} - {{ optional($voucher->ends_at)->format('d M Y') ?: 'Tanpa batas' }}</p>@endif</div></td>
            <td><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $voucher->type === 'percent' ? 'Persen' : 'Nominal' }}</span></td>
            <td class="font-extrabold">{{ $voucher->type === 'percent' ? $voucher->value.'%' : 'Rp '.number_format($voucher->value,0,',','.') }}</td>
            <td class="font-semibold">Rp {{ number_format($voucher->minimum_order ?? 0,0,',','.') }}</td>
            <td><div class="max-w-[220px] text-xs font-bold leading-5 text-slate-500 dark:text-slate-400">{{ $voucher->products->isEmpty() ? 'Semua Produk' : $voucher->products->pluck('name')->join(', ') }}</div></td>
            <td><span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $voucher->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">{{ $voucher->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
            <td class="pr-5 text-right"><div class="inline-flex items-center gap-2"><a class="admin-btn-secondary px-3 py-2 text-xs" href="{{ route('admin.vouchers.edit',$voucher) }}"><i class="ph ph-pencil-simple"></i> Edit</a><form class="inline" method="POST" action="{{ route('admin.vouchers.destroy',$voucher) }}" onsubmit="return confirm('Hapus voucher ini?')">@csrf @method('DELETE') <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form></div></td>
        </tr>
    @empty
        @include('admin.partials.empty-state', ['colspan' => 8, 'icon' => 'ph-ticket', 'title' => 'Voucher belum tersedia', 'description' => 'Tambahkan voucher untuk membuat promo yang bisa digunakan customer.'])
    @endforelse
    </tbody></table></div>
</section>
@include('admin.partials.pagination', ['paginator' => $vouchers])
@endsection
