@extends('admin.layouts.app')
@section('page_title','Produk')
@section('page_description','Kelola foto, harga, status, dan urutan produk yang tampil di katalog.')
@section('page_action')
<a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-teal-700 px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-teal-800 dark:hover:bg-teal-400 hover:-translate-y-0.5 hover:shadow-lg dark:bg-teal-500 dark:text-white"><i class="ph ph-plus-circle"></i> Tambah Produk</a>
@endsection
@section('content')
<form method="GET" action="{{ route('admin.products.index') }}" class="mb-4 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <input type="hidden" name="sort" value="{{ $filters['sort'] ?? 'created_at' }}">
    <input type="hidden" name="direction" value="{{ $filters['direction'] ?? 'desc' }}">
    <div class="grid gap-3 xl:grid-cols-[1fr_220px_170px_140px_auto]">
        <label class="relative block">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari produk..." class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:focus:border-white/40">
        </label>
        <select name="category_id" class="h-11 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800">
            <option value="">Semua kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="status" class="h-11 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800">
            <option value="" @selected(($filters['status'] ?? '') === '')>Semua status</option>
            <option value="active" @selected(($filters['status'] ?? '') === 'active')>Aktif</option>
            <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Nonaktif</option>
        </select>
        <select name="per_page" class="h-11 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800">
            @foreach([10,25,50] as $size)<option value="{{ $size }}" @selected(($filters['per_page'] ?? 10) == $size)>{{ $size }} data</option>@endforeach
        </select>
        <div class="flex gap-2">
            <button class="inline-flex h-11 flex-1 items-center justify-center gap-2 rounded-2xl bg-teal-700 px-5 text-sm font-extrabold text-white transition hover:bg-teal-800 dark:hover:bg-teal-400 hover:-translate-y-0.5 dark:bg-teal-500 dark:text-white"><i class="ph ph-funnel"></i> Terapkan</button>
            <a href="{{ route('admin.products.index') }}" class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 px-4 text-sm font-extrabold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Reset</a>
        </div>
    </div>
</form>

<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="border-b border-slate-100 p-5 dark:border-slate-800">
        <h2 class="font-extrabold">Daftar Produk</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Cek produk, kategori, harga, dan status tayang dari satu tampilan.</p>
    </div>
    <div class="admin-scrollbar overflow-x-auto">
        <table class="w-full min-w-[1040px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4">@include('admin.partials.sort-link', ['sort' => 'name', 'label' => 'Produk', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th>
                    <th>@include('admin.partials.sort-link', ['sort' => 'category', 'label' => 'Kategori', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th>
                    <th>@include('admin.partials.sort-link', ['sort' => 'price', 'label' => 'Harga', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th>
                    <th>Label</th>
                    <th>@include('admin.partials.sort-link', ['sort' => 'status', 'label' => 'Status', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th>
                    <th>@include('admin.partials.sort-link', ['sort' => 'sort_order', 'label' => 'Urutan', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th>
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
                                @unless($imageUrl)<div class="mt-1 text-[11px] font-bold text-amber-600 dark:text-amber-300">Foto belum tersedia</div>@endunless
                            </div>
                        </div>
                    </td>
                    <td><span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-extrabold text-teal-700 dark:bg-teal-400/10 dark:text-teal-300">{{ $product->category?->name ?? '-' }}</span></td>
                    <td class="font-extrabold">Rp {{ number_format($product->price,0,',','.') }}</td>
                    <td><div class="flex flex-wrap gap-1.5">@if($product->badge)<span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-extrabold text-amber-700 dark:bg-amber-400/10 dark:text-amber-300">{{ $product->badge }}</span>@endif @if($product->is_featured)<span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-extrabold text-amber-700 dark:bg-amber-400/10 dark:text-amber-300">Unggulan</span>@endif @if($product->is_latest)<span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-extrabold text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300">Terbaru</span>@endif @if(!$product->badge && !$product->is_featured && !$product->is_latest)<span class="text-slate-400">-</span>@endif</div></td>
                    <td><span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="font-semibold">{{ $product->sort_order }}</td>
                    <td class="pr-5 text-right"><div class="inline-flex items-center gap-2"><a class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-2 text-xs font-extrabold transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800" href="{{ route('admin.products.edit',$product) }}"><i class="ph ph-pencil-simple"></i> Edit</a><form class="inline" method="POST" action="{{ route('admin.products.destroy',$product) }}" onsubmit="return confirm('Hapus produk ini?')">@csrf @method('DELETE') <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form></div></td>
                </tr>
            @empty
                @include('admin.partials.empty-state', ['colspan' => 7, 'icon' => 'ph-package', 'title' => 'Produk belum tersedia', 'description' => 'Tambahkan produk agar katalog bisa mulai digunakan.'])
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@include('admin.partials.pagination', ['paginator' => $products])
@endsection
