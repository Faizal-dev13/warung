@extends('admin.layouts.app')
@section('page_title','Produk')
@section('page_description','Kelola produk yang tampil di katalog. Foto produk, harga, status, dan urutan bisa dicek dari tabel ini.')
@section('page_action')<a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white shadow-sm dark:bg-white dark:text-slate-950"><i class="ph ph-plus-circle"></i> Tambah Produk</a>@endsection
@section('content')
<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900" data-admin-table>
    <div class="border-b border-slate-100 p-4 dark:border-slate-800">
        <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h2 class="font-extrabold">Daftar Produk</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Cari, urutkan, dan cek visual produk sebelum tampil ke customer.</p>
            </div>
            <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_220px] xl:min-w-[560px]">
                <label class="relative block"><i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input data-datatable-search placeholder="Cari produk..." class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-semibold outline-none dark:border-slate-700 dark:bg-slate-800"></label>
                <form method="GET" action="{{ route('admin.products.index') }}">
                    <select name="category_id" onchange="this.form.submit()" class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold outline-none dark:border-slate-700 dark:bg-slate-800">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>
    <div class="admin-scrollbar overflow-x-auto">
        <table class="w-full min-w-[1040px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                <tr>
                    <th data-sortable class="px-5 py-4">Produk <i class="sort-icon ph ph-caret-up-down ml-1"></i></th>
                    <th data-sortable>Kategori <i class="sort-icon ph ph-caret-up-down ml-1"></i></th>
                    <th data-sortable>Harga <i class="sort-icon ph ph-caret-up-down ml-1"></i></th>
                    <th>Label</th>
                    <th>Status</th>
                    <th data-sortable>Urutan <i class="sort-icon ph ph-caret-up-down ml-1"></i></th>
                    <th class="pr-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($products as $product)
                @php
                    $imagePath = $product->image_path ?? null;
                    $imageUrl = $imagePath
                        ? (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://', '/']) ? $imagePath : \Illuminate\Support\Facades\Storage::url($imagePath))
                        : null;
                @endphp
                <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="grid h-full w-full place-items-center text-slate-400"><i class="ph ph-image text-2xl"></i></div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="max-w-[280px] truncate font-extrabold text-slate-950 dark:text-white">{{ $product->name }}</div>
                                <div class="mt-1 max-w-[360px] truncate text-xs text-slate-500 dark:text-slate-400">{{ $product->summary }}</div>
                                @unless($imageUrl)<div class="mt-1 text-[11px] font-bold text-amber-600 dark:text-amber-300">Foto belum diupload</div>@endunless
                            </div>
                        </div>
                    </td>
                    <td>{{ $product->category?->name ?: '-' }}</td>
                    <td><b>Rp {{ number_format($product->price,0,',','.') }}</b>@if($product->old_price)<br><span class="text-xs text-slate-400 line-through">Rp {{ number_format($product->old_price,0,',','.') }}</span>@endif</td>
                    <td>@if($product->badge)<span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold text-blue-700 dark:bg-blue-400/10 dark:text-blue-300">{{ $product->badge }}</span>@else<span class="text-slate-400">-</span>@endif</td>
                    <td><div class="flex flex-wrap gap-1.5"><span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>@if($product->is_featured)<span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-extrabold text-violet-700 dark:bg-violet-400/10 dark:text-violet-300">Unggulan</span>@endif @if($product->is_latest)<span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-extrabold text-cyan-700 dark:bg-cyan-400/10 dark:text-cyan-300">Terbaru</span>@endif</div></td>
                    <td>{{ $product->sort_order }}</td>
                    <td class="pr-5 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-2 text-xs font-extrabold transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800" href="{{ route('admin.products.edit',$product) }}"><i class="ph ph-pencil-simple"></i> Edit</a>
                            <form class="inline" method="POST" action="{{ route('admin.products.destroy',$product) }}" onsubmit="return confirm('Hapus produk ini?')">@csrf @method('DELETE') <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr data-empty-row><td colspan="7" class="px-5 py-10 text-center text-slate-500">Belum ada produk.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex flex-col gap-3 border-t border-slate-100 p-4 text-sm dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
        <div class="text-slate-500 dark:text-slate-400"><span data-datatable-info></span></div>
        <div class="flex items-center gap-2"><select data-datatable-length class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold dark:border-slate-700 dark:bg-slate-900"><option value="5">5 baris</option><option value="10" selected>10 baris</option><option value="20">20 baris</option></select><button type="button" data-datatable-prev class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold disabled:opacity-40 dark:border-slate-700">Prev</button><button type="button" data-datatable-next class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold disabled:opacity-40 dark:border-slate-700">Next</button></div>
    </div>
</section>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
