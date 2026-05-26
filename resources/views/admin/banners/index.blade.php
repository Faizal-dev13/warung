@extends('admin.layouts.app')
@section('page_title','Banner')
@section('page_description','Kelola slide promosi yang tampil di halaman depan.')
@section('page_action')
<a href="{{ route('admin.banners.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-teal-700 px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-teal-800 dark:hover:bg-teal-400 hover:-translate-y-0.5 hover:shadow-lg dark:bg-teal-500 dark:text-white"><i class="ph ph-plus-circle"></i> Tambah Banner</a>
@endsection
@section('content')
<form method="GET" action="{{ route('admin.banners.index') }}" class="mb-4 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <input type="hidden" name="sort" value="{{ $filters['sort'] ?? 'created_at' }}">
    <input type="hidden" name="direction" value="{{ $filters['direction'] ?? 'desc' }}">
    <div class="grid gap-3 lg:grid-cols-[1fr_180px_140px_auto]">
        <label class="relative block"><i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari banner..." class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800"></label>
        <select name="status" class="h-11 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800"><option value="" @selected(($filters['status'] ?? '') === '')>Semua status</option><option value="active" @selected(($filters['status'] ?? '') === 'active')>Aktif</option><option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Nonaktif</option></select>
        <select name="per_page" class="h-11 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold outline-none transition focus:border-teal-600 focus:bg-white dark:border-slate-700 dark:bg-slate-800">@foreach([10,25,50] as $size)<option value="{{ $size }}" @selected(($filters['per_page'] ?? 10) == $size)>{{ $size }} data</option>@endforeach</select>
        <div class="flex gap-2"><button class="inline-flex h-11 flex-1 items-center justify-center gap-2 rounded-2xl bg-teal-700 px-5 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-teal-800 dark:bg-teal-500 dark:hover:bg-teal-400 dark:text-white"><i class="ph ph-funnel"></i> Terapkan</button><a href="{{ route('admin.banners.index') }}" class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 px-4 text-sm font-extrabold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Reset</a></div>
    </div>
</form>

<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="border-b border-slate-100 p-5 dark:border-slate-800"><h2 class="font-extrabold">Daftar Banner</h2><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Cek gambar, teks, tombol, status, dan urutan banner.</p></div>
    <div class="admin-scrollbar overflow-x-auto"><table class="w-full min-w-[1020px] text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400"><tr><th class="px-5 py-4">@include('admin.partials.sort-link', ['sort' => 'title', 'label' => 'Banner', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th><th>Gambar</th><th>Tombol</th><th>@include('admin.partials.sort-link', ['sort' => 'sort_order', 'label' => 'Urutan', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th><th>@include('admin.partials.sort-link', ['sort' => 'status', 'label' => 'Status', 'defaultSort' => $filters['sort'] ?? 'sort_order'])</th><th class="pr-5 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100 dark:divide-slate-800">
    @forelse($banners as $banner)
        @php
            $imagePath = $banner->image_path ?? null;
            $imageUrl = $imagePath ? (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://', '/']) ? $imagePath : \Illuminate\Support\Facades\Storage::url($imagePath)) : null;
            $mobileImagePath = $banner->mobile_image_path ?? null;
            $mobileImageUrl = $mobileImagePath ? (\Illuminate\Support\Str::startsWith($mobileImagePath, ['http://', 'https://', '/']) ? $mobileImagePath : \Illuminate\Support\Facades\Storage::url($mobileImagePath)) : null;
        @endphp
        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
            <td class="px-5 py-4"><div class="max-w-[340px]"><p class="font-extrabold text-slate-950 dark:text-white">{{ $banner->title }}</p>@if($banner->label)<p class="mt-1 text-xs font-bold text-teal-600 dark:text-teal-300">{{ $banner->label }}</p>@endif @if($banner->subtitle)<p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500 dark:text-slate-400">{{ $banner->subtitle }}</p>@endif</div></td>
            <td><div class="flex items-center gap-2"><div class="h-14 w-28 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 dark:border-slate-700 dark:bg-slate-800">@if($imageUrl)<img src="{{ $imageUrl }}" alt="{{ $banner->title }}" class="h-full w-full object-cover">@else<div class="grid h-full w-full place-items-center text-slate-400"><i class="ph ph-image text-2xl"></i></div>@endif</div>@if($mobileImageUrl)<span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">Mobile</span>@endif</div></td>
            <td><div class="max-w-[180px] truncate font-semibold">{{ $banner->button_text ?: '-' }}</div></td>
            <td class="font-semibold">{{ $banner->sort_order }}</td>
            <td><span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $banner->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">{{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
            <td class="pr-5 text-right"><div class="inline-flex items-center gap-2"><a class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-2 text-xs font-extrabold transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800" href="{{ route('admin.banners.edit',$banner) }}"><i class="ph ph-pencil-simple"></i> Edit</a><form class="inline" method="POST" action="{{ route('admin.banners.destroy',$banner) }}" onsubmit="return confirm('Hapus banner ini?')">@csrf @method('DELETE') <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button></form></div></td>
        </tr>
    @empty
        @include('admin.partials.empty-state', ['colspan' => 6, 'icon' => 'ph-images', 'title' => 'Banner belum tersedia', 'description' => 'Tambahkan banner untuk menampilkan promosi di halaman depan.'])
    @endforelse
    </tbody></table></div>
</section>
@include('admin.partials.pagination', ['paginator' => $banners])
@endsection
