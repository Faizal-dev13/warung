@extends('admin.layouts.app')
@section('page_title','Kategori')
@section('page_description','Atur kelompok produk agar katalog lebih mudah dipahami oleh customer.')
@section('page_action')
<a href="{{ route('admin.categories.create') }}" class="admin-btn-primary"><i class="ph ph-plus-circle"></i> Tambah Kategori</a>
@endsection
@section('content')
<form method="GET" action="{{ route('admin.categories.index') }}" class="mb-5 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <input type="hidden" name="sort" value="{{ $filters['sort'] ?? 'created_at' }}">
    <input type="hidden" name="direction" value="{{ $filters['direction'] ?? 'desc' }}">
    <div class="grid gap-3 lg:grid-cols-[1fr_180px_140px_auto]">
        <label class="relative block">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari kategori..." class="admin-input h-12 pl-11">
        </label>
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
            <a href="{{ route('admin.categories.index') }}" class="admin-btn-secondary h-12">Reset</a>
        </div>
    </div>
</form>

<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="flex flex-col gap-3 border-b border-slate-100 p-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-extrabold">Daftar Kategori</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelola kategori, icon, urutan, dan status tayang.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
            {{ $categories->total() }} kategori
        </span>
    </div>

    <div class="grid gap-3 p-4 md:hidden">
        @forelse($categories as $category)
            <article class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-white text-teal-700 shadow-sm dark:bg-slate-900 dark:text-teal-300">
                            <i class="ph {{ $category->icon ?: 'ph-tag' }} text-2xl"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="truncate font-extrabold text-slate-950 dark:text-white">{{ $category->name }}</p>
                            <p class="mt-1 text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $category->products_count }} produk · Urutan {{ $category->sort_order }}</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full px-3 py-1 text-[11px] font-extrabold {{ $category->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-200 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <a class="admin-btn-secondary h-11 text-xs" href="{{ route('admin.categories.edit',$category) }}"><i class="ph ph-pencil-simple"></i> Edit</a>
                    <form method="POST" action="{{ route('admin.categories.destroy',$category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-2xl border border-red-200 bg-white px-3 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:bg-slate-900 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-center dark:border-slate-700">
                <i class="ph ph-folders text-4xl text-slate-400"></i>
                <p class="mt-3 font-extrabold">Kategori belum tersedia</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tambahkan kategori agar produk lebih mudah dikelompokkan.</p>
            </div>
        @endforelse
    </div>

    <div class="admin-scrollbar hidden overflow-x-auto md:block">
        <table class="w-full min-w-[860px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4">@include('admin.partials.sort-link', ['sort' => 'name', 'label' => 'Kategori', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th>
                    <th>Icon</th>
                    <th>@include('admin.partials.sort-link', ['sort' => 'products', 'label' => 'Produk', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th>
                    <th>@include('admin.partials.sort-link', ['sort' => 'sort_order', 'label' => 'Urutan', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th>
                    <th>@include('admin.partials.sort-link', ['sort' => 'status', 'label' => 'Status', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th>
                    <th class="pr-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($categories as $category)
                    <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                        <td class="px-5 py-4 font-extrabold text-slate-950 dark:text-white">{{ $category->name }}</td>
                        <td><span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300"><i class="ph {{ $category->icon ?: 'ph-tag' }} text-base"></i> {{ $category->icon ?: 'ph-tag' }}</span></td>
                        <td class="font-semibold">{{ $category->products_count }} produk</td>
                        <td class="font-semibold">{{ $category->sort_order }}</td>
                        <td><span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $category->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="pr-5 text-right"><div class="inline-flex items-center gap-2"><a class="admin-btn-secondary px-3 py-2 text-xs" href="{{ route('admin.categories.edit',$category) }}"><i class="ph ph-pencil-simple"></i> Edit</a><form class="inline" method="POST" action="{{ route('admin.categories.destroy',$category) }}" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE') <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form></div></td>
                    </tr>
                @empty
                    @include('admin.partials.empty-state', ['colspan' => 6, 'icon' => 'ph-folders', 'title' => 'Kategori belum tersedia', 'description' => 'Tambahkan kategori agar produk lebih mudah dikelompokkan.'])
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@include('admin.partials.pagination', ['paginator' => $categories])
@endsection
