@extends('admin.layouts.app')
@section('page_title','Detail Order '.$order->invoice_number)
@section('page_description','Cek pesanan, data customer, total pembayaran, dan status follow-up.')
@section('page_action')
<div class="flex flex-col gap-2 sm:flex-row">
    <a href="{{ route('admin.orders.invoice',$order) }}" target="_blank" class="admin-btn-primary"><i class="ph ph-file-pdf"></i> Cetak Invoice PDF</a>
    <a href="{{ route('admin.orders.index') }}" class="admin-btn-secondary"><i class="ph ph-arrow-left"></i> Kembali</a>
</div>
@endsection
@section('content')
@php
    $statusLabels = [
        'waiting_whatsapp_confirmation' => 'Menunggu WA',
        'confirmed' => 'Dikonfirmasi',
        'processed' => 'Diproses',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];
    $statusClasses = [
        'waiting_whatsapp_confirmation'=>'bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300',
        'confirmed'=>'bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-300',
        'processed'=>'bg-sky-50 text-sky-700 dark:bg-sky-400/10 dark:text-sky-300',
        'completed'=>'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300',
        'cancelled'=>'bg-red-50 text-red-700 dark:bg-red-400/10 dark:text-red-300',
    ];
@endphp

<section class="mb-5 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="bg-slate-950 p-5 text-white sm:p-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[.22em] text-teal-200">Invoice</p>
                <h2 class="mt-2 text-2xl font-extrabold sm:text-3xl">{{ $order->invoice_number }}</h2>
                <p class="mt-2 text-sm text-slate-300">Dibuat pada {{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[420px]">
                <div class="rounded-3xl border border-white/10 bg-white/10 p-4">
                    <p class="text-xs font-bold text-slate-300">Customer</p>
                    <p class="mt-1 font-extrabold">{{ $order->customer_name }}</p>
                    <p class="mt-1 text-xs text-slate-300">{{ $order->customer_phone ?: '-' }}</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/10 p-4">
                    <p class="text-xs font-bold text-slate-300">Total</p>
                    <p class="mt-1 text-xl font-extrabold text-teal-200">Rp {{ number_format($order->total,0,',','.') }}</p>
                    <span class="mt-2 inline-flex rounded-full px-3 py-1 text-[11px] font-extrabold {{ $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-500' }}">{{ $statusLabels[$order->status] ?? str_replace('_',' ',$order->status) }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="grid gap-5 xl:grid-cols-[1fr_390px]">
    <section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-100 p-5 dark:border-slate-800">
            <h2 class="text-lg font-extrabold">Item Pesanan</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Produk yang dipilih customer.</p>
        </div>
        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @foreach($order->items as $item)
                <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-300"><i class="ph ph-package text-xl"></i></span>
                        <div>
                            <p class="font-extrabold text-slate-950 dark:text-white">{{ $item->product_name }}</p>
                            @if($item->variant_name)
                                <p class="mt-1 inline-flex rounded-full bg-teal-50 px-3 py-1 text-xs font-extrabold text-teal-700 dark:bg-teal-400/10 dark:text-teal-300">Varian: {{ $item->variant_name }}</p>
                            @endif
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $item->quantity }} x Rp {{ number_format($item->price,0,',','.') }}</p>
                        </div>
                    </div>
                    <b class="text-left sm:text-right">Rp {{ number_format($item->subtotal,0,',','.') }}</b>
                </div>
            @endforeach
        </div>
        <div class="border-t border-slate-100 p-5 text-sm dark:border-slate-800">
            <div class="ml-auto max-w-sm space-y-3">
                <div class="flex justify-between gap-5"><span class="text-slate-500 dark:text-slate-400">Subtotal</span><b>Rp {{ number_format($order->subtotal,0,',','.') }}</b></div>
                <div class="flex justify-between gap-5"><span class="text-slate-500 dark:text-slate-400">Diskon</span><b class="text-red-500">- Rp {{ number_format($order->discount,0,',','.') }}</b></div>
                <div class="flex justify-between gap-5 rounded-2xl bg-slate-950 p-4 text-base text-white dark:bg-white dark:text-slate-950"><span>Total</span><b>Rp {{ number_format($order->total,0,',','.') }}</b></div>
            </div>
        </div>
    </section>

    <aside class="space-y-5">
        <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-lg font-extrabold">Data Customer</h2>
            <div class="mt-4 grid gap-3 text-sm">
                <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60"><span class="text-xs font-bold text-slate-400">Nama</span><p class="mt-1 font-extrabold">{{ $order->customer_name }}</p></div>
                <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60"><span class="text-xs font-bold text-slate-400">No HP</span><p class="mt-1 font-extrabold">{{ $order->customer_phone ?: '-' }}</p></div>
                <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60"><span class="text-xs font-bold text-slate-400">Voucher</span><p class="mt-1 font-extrabold">{{ $order->voucher_code ?: '-' }}</p></div>
                <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60"><span class="text-xs font-bold text-slate-400">Catatan</span><p class="mt-1 font-semibold leading-6">{{ $order->note ?: '-' }}</p></div>
            </div>
        </section>

        <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-lg font-extrabold">Update Status</h2>
            <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">Perbarui status agar proses order lebih mudah dipantau.</p>
            <form method="POST" action="{{ route('admin.orders.status',$order) }}" class="mt-4 grid gap-3">
                @csrf @method('PATCH')
                <select name="status" class="admin-select">
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected($order->status === $status)>{{ $statusLabels[$status] ?? str_replace('_',' ',$status) }}</option>
                    @endforeach
                </select>
                <button class="admin-btn-primary w-full"><i class="ph ph-check-circle"></i> Simpan Status</button>
            </form>
        </section>

        @if($order->whatsapp_message)
            <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-extrabold">Pesan WhatsApp</h2>
                <div class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm leading-6 text-slate-600 dark:bg-slate-800/60 dark:text-slate-300 whitespace-pre-wrap">{{ $order->whatsapp_message }}</div>
            </section>
        @endif
    </aside>
</div>
@endsection
