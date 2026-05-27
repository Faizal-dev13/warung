@extends('admin.layouts.app')
@section('page_title','Produk')
@section('page_description','Kelola foto, harga, status aktif, dan urutan produk yang tampil di katalog.')
@section('page_action')
<a href="{{ route('admin.products.create') }}" class="admin-btn-primary"><i class="ph ph-plus-circle"></i> Tambah Produk</a>
@endsection
@section('content')
<form method="GET" action="{{ route('admin.products.index') }}" class="mb-5 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <input type="hidden" name="sort" value="{{ $filters['sort'] ?? 'created_at' }}">
    <input type="hidden" name="direction" value="{{ $filters['direction'] ?? 'desc' }}">
    <div class="grid gap-3 xl:grid-cols-[1fr_220px_170px_140px_auto]">
        <label class="relative block">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari produk..." class="admin-input h-12 pl-11">
        </label>
        <select name="category_id" class="admin-select h-12">
            <option value="">Semua kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="status" class="admin-select h-12">
            <option value="" @selected(($filters['status'] ?? '') === '')>Semua status</option>
            <option value="active" @selected(($filters['status'] ?? '') === 'active')>Aktif</option>
            <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Nonaktif</option>
        </select>
        <select name="per_page" class="admin-select h-12">
            @foreach([10,25,50] as $size)<option value="{{ $size }}" @selected(($filters['per_page'] ?? 10) == $size)>{{ $size }} data</option>@endforeach
        </select>
        <div class="grid grid-cols-2 gap-2 sm:flex">
            <button class="admin-btn-primary h-12"><i class="ph ph-funnel"></i> Terapkan</button>
            <a href="{{ route('admin.products.index') }}" class="admin-btn-secondary h-12">Reset</a>
        </div>
    </div>
</form>

<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="flex flex-col gap-3 border-b border-slate-100 p-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-extrabold">Daftar Produk</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Cek foto, kategori, harga, status aktif, dan urutan produk.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $products->total() }} produk</span>
    </div>

    <div class="grid gap-3 p-4 lg:hidden">
        @forelse($products as $product)
            @php
                $imagePath = $product->image_path ?? null;
                $imageUrl = $imagePath
                    ? (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://', '/']) ? $imagePath : \Illuminate\Support\Facades\Storage::url($imagePath))
                    : null;
            @endphp
            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950/40">
                <div class="flex gap-3 p-4">
                    <div class="h-24 w-24 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <div class="grid h-full w-full place-items-center text-slate-400"><i class="ph ph-image text-3xl"></i></div>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <p class="line-clamp-2 font-extrabold text-slate-950 dark:text-white">{{ $product->name }}</p>
                            <span class="shrink-0 rounded-full px-3 py-1 text-[11px] font-extrabold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-200 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </div>
                        <p class="mt-1 text-xs font-semibold text-teal-700 dark:text-teal-300">{{ $product->category?->name ?? '-' }}</p>
                        <p class="mt-2 font-extrabold">Rp {{ number_format($product->price,0,',','.') }}</p>
                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $product->variants_count ?? $product->variants()->count() }} varian</span>
                            <a href="{{ route('admin.variants.create', ['product_id' => $product->id]) }}" class="rounded-full bg-teal-50 px-3 py-1 text-[11px] font-extrabold text-teal-700 transition hover:bg-teal-100 dark:bg-teal-400/10 dark:text-teal-300">Tambah varian</a>
                        </div>
                        @if($product->badge)
                            <span class="mt-2 inline-flex rounded-full bg-amber-50 px-3 py-1 text-[11px] font-extrabold text-amber-700 dark:bg-amber-400/10 dark:text-amber-300">{{ $product->badge }}</span>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 border-t border-slate-200 p-3 dark:border-slate-800">
                    <a class="admin-btn-secondary h-11 text-xs" href="{{ route('admin.products.edit',$product) }}"><i class="ph ph-pencil-simple"></i> Edit</a>
                    <a class="admin-btn-secondary h-11 text-xs" href="{{ route('admin.variants.index', ['product_id' => $product->id]) }}"><i class="ph ph-stack"></i> Varian</a>
                    <form method="POST" action="{{ route('admin.products.destroy',$product) }}" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-2xl border border-red-200 bg-white px-3 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:bg-slate-900 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-center dark:border-slate-700">
                <i class="ph ph-package text-4xl text-slate-400"></i>
                <p class="mt-3 font-extrabold">Produk belum tersedia</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tambahkan produk agar katalog bisa mulai digunakan.</p>
            </div>
        @endforelse
    </div>

    <div class="admin-scrollbar hidden overflow-x-auto lg:block">
        <table class="w-full min-w-[1040px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4">@include('admin.partials.sort-link', ['sort' => 'name', 'label' => 'Produk', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th>
                    <th>@include('admin.partials.sort-link', ['sort' => 'category', 'label' => 'Kategori', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th>
                    <th>@include('admin.partials.sort-link', ['sort' => 'price', 'label' => 'Harga', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th>
                    <th>Varian</th>
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
                            </div>
                        </div>
                    </td>
                    <td><span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-extrabold text-teal-700 dark:bg-teal-400/10 dark:text-teal-300">{{ $product->category?->name ?? '-' }}</span></td>
                    <td class="font-extrabold">Rp {{ number_format($product->price,0,',','.') }}</td>
                    <td><a href="{{ route('admin.variants.index', ['product_id' => $product->id]) }}" class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-600 transition hover:bg-teal-50 hover:text-teal-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-teal-400/10 dark:hover:text-teal-300"><i class="ph ph-stack"></i>{{ $product->variants_count ?? 0 }}</a></td>
                    <td>@if($product->badge)<span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-extrabold text-amber-700 dark:bg-amber-400/10 dark:text-amber-300">{{ $product->badge }}</span>@else<span class="text-slate-400">-</span>@endif</td>
                    <td><span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="font-semibold">{{ $product->sort_order }}</td>
                    <td class="pr-5 text-right"><div class="inline-flex items-center gap-2"><a class="admin-btn-secondary px-3 py-2 text-xs" href="{{ route('admin.products.edit',$product) }}"><i class="ph ph-pencil-simple"></i> Edit</a><a class="admin-btn-secondary px-3 py-2 text-xs" href="{{ route('admin.variants.create', ['product_id' => $product->id]) }}"><i class="ph ph-plus-circle"></i> Varian</a><form class="inline" method="POST" action="{{ route('admin.products.destroy',$product) }}" onsubmit="return confirm('Hapus produk ini?')">@csrf @method('DELETE') <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form></div></td>
                </tr>
            @empty
                @include('admin.partials.empty-state', ['colspan' => 8, 'icon' => 'ph-package', 'title' => 'Produk belum tersedia', 'description' => 'Tambahkan produk agar katalog bisa mulai digunakan.'])
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@include('admin.partials.pagination', ['paginator' => $products])
@endsection
