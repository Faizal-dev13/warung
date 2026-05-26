@extends('admin.layouts.app')
@section('page_title','Order')
@section('page_description','Pantau pesanan masuk, data customer, total pembayaran, dan status follow-up.')
@section('content')
@php
    $statusLabels = [
        'waiting_whatsapp_confirmation'=>'Menunggu WA',
        'confirmed'=>'Dikonfirmasi',
        'processed'=>'Diproses',
        'completed'=>'Selesai',
        'cancelled'=>'Dibatalkan',
    ];
    $statusClasses = [
        'waiting_whatsapp_confirmation'=>'bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300',
        'confirmed'=>'bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-300',
        'processed'=>'bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300',
        'completed'=>'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300',
        'cancelled'=>'bg-red-50 text-red-700 dark:bg-red-400/10 dark:text-red-300',
    ];
@endphp
<form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <input type="hidden" name="sort" value="{{ $filters['sort'] ?? 'created_at' }}">
    <input type="hidden" name="direction" value="{{ $filters['direction'] ?? 'desc' }}">
    <div class="grid gap-3 lg:grid-cols-[1fr_220px_140px_auto]">
        <label class="relative block"><i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari invoice, nama, atau nomor HP..." class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800"></label>
        <select name="status" class="h-11 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800"><option value="" @selected(($filters['status'] ?? '') === '')>Semua status</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $statusLabels[$status] ?? str_replace('_',' ',$status) }}</option>@endforeach</select>
        <select name="per_page" class="h-11 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800">@foreach([10,25,50] as $size)<option value="{{ $size }}" @selected(($filters['per_page'] ?? 10) == $size)>{{ $size }} data</option>@endforeach</select>
        <div class="flex gap-2"><button class="inline-flex h-11 flex-1 items-center justify-center gap-2 rounded-2xl bg-teal-700 px-5 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-teal-800 dark:bg-teal-500 dark:hover:bg-teal-400 dark:text-white"><i class="ph ph-funnel"></i> Terapkan</button><a href="{{ route('admin.orders.index') }}" class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 px-4 text-sm font-extrabold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Reset</a></div>
    </div>
</form>

<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="border-b border-slate-100 p-5 dark:border-slate-800"><h2 class="font-extrabold">Daftar Order</h2><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Urutkan dan filter order sesuai kebutuhan follow-up.</p></div>
    <div class="admin-scrollbar overflow-x-auto"><table class="w-full min-w-[980px] text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400"><tr><th class="px-5 py-4">@include('admin.partials.sort-link', ['sort' => 'invoice', 'label' => 'Invoice', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'])</th><th>@include('admin.partials.sort-link', ['sort' => 'customer', 'label' => 'Customer', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'])</th><th>@include('admin.partials.sort-link', ['sort' => 'items', 'label' => 'Item', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'])</th><th>@include('admin.partials.sort-link', ['sort' => 'total', 'label' => 'Total', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'])</th><th>@include('admin.partials.sort-link', ['sort' => 'status', 'label' => 'Status', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'])</th><th>@include('admin.partials.sort-link', ['sort' => 'created_at', 'label' => 'Tanggal', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => 'desc'])</th><th class="pr-5 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100 dark:divide-slate-800">
    @forelse($orders as $order)
        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
            <td class="px-5 py-4 font-extrabold text-slate-950 dark:text-white">{{ $order->invoice_number }}</td>
            <td><b>{{ $order->customer_name }}</b><br><span class="text-xs text-slate-500">{{ $order->customer_phone ?: '-' }}</span></td>
            <td class="font-semibold">{{ $order->items_count }} item</td>
            <td class="font-extrabold">Rp {{ number_format($order->total,0,',','.') }}</td>
            <td><span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">{{ $statusLabels[$order->status] ?? str_replace('_',' ',$order->status) }}</span></td>
            <td class="text-slate-500 dark:text-slate-400">{{ $order->created_at->format('d M Y H:i') }}</td>
            <td class="pr-5 text-right"><a href="{{ route('admin.orders.show',$order) }}" class="inline-flex items-center gap-1 rounded-xl bg-teal-700 px-3 py-2 text-xs font-extrabold text-white transition hover:bg-teal-800 dark:hover:bg-teal-400 hover:-translate-y-0.5 dark:bg-teal-500 dark:text-white">Detail <i class="ph ph-arrow-right"></i></a></td>
        </tr>
    @empty
        @include('admin.partials.empty-state', ['colspan' => 7, 'icon' => 'ph-receipt', 'title' => 'Order belum tersedia', 'description' => 'Order baru akan muncul setelah customer melakukan checkout.'])
    @endforelse
    </tbody></table></div>
</section>
@include('admin.partials.pagination', ['paginator' => $orders])
@endsection
