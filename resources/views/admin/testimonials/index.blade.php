@extends('admin.layouts.app')
@section('page_title','Testimoni')
@section('page_description','Kelola ulasan customer yang tampil di halaman depan toko.')
@section('page_action')
<a href="{{ route('admin.testimonials.create') }}" class="admin-btn-primary"><i class="ph ph-plus-circle"></i> Tambah Testimoni</a>
@endsection
@section('content')
<form method="GET" action="{{ route('admin.testimonials.index') }}" class="mb-5 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <input type="hidden" name="sort" value="{{ $filters['sort'] ?? 'created_at' }}">
    <input type="hidden" name="direction" value="{{ $filters['direction'] ?? 'desc' }}">
    <div class="grid gap-3 xl:grid-cols-[1fr_170px_140px_auto]">
        <label class="relative block">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama atau isi testimoni..." class="admin-input h-12 pl-11">
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
            <a href="{{ route('admin.testimonials.index') }}" class="admin-btn-secondary h-12">Reset</a>
        </div>
    </div>
</form>

<section class="rounded-[1.5rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="flex flex-col gap-3 border-b border-slate-100 p-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-extrabold">Daftar Testimoni</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ulasan aktif akan muncul di homepage customer.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $testimonials->total() }} testimoni</span>
    </div>

    <div class="grid gap-3 p-4 lg:hidden">
        @forelse($testimonials as $testimonial)
            <article class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex min-w-0 items-start gap-3">
                        @php($imageUrl = $testimonial->image_url)
                        <div class="grid h-12 w-12 shrink-0 place-items-center overflow-hidden rounded-2xl bg-teal-50 text-teal-700 ring-1 ring-teal-100 dark:bg-teal-400/10 dark:text-teal-300 dark:ring-teal-400/20">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $testimonial->name }}" class="h-full w-full object-cover">
                            @else
                                <i class="ph ph-user-circle text-2xl"></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="font-extrabold text-slate-950 dark:text-white">{{ $testimonial->name }}</p>
                            <div class="mt-1 flex items-center gap-0.5 text-amber-500">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ph-fill ph-star text-sm {{ $i <= (int) $testimonial->rating ? '' : 'opacity-25' }}"></i>
                                @endfor
                            </div>
                            <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $testimonial->message }}</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full px-3 py-1 text-[11px] font-extrabold {{ $testimonial->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-200 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">{{ $testimonial->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <a class="admin-btn-secondary h-11 text-xs" href="{{ route('admin.testimonials.edit',$testimonial) }}"><i class="ph ph-pencil-simple"></i> Edit</a>
                    <form method="POST" action="{{ route('admin.testimonials.destroy',$testimonial) }}" onsubmit="return confirm('Hapus testimoni ini?')">
                        @csrf @method('DELETE')
                        <button class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-2xl border border-red-200 bg-white px-3 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:bg-slate-900 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-center dark:border-slate-700">
                <i class="ph ph-chat-teardrop-text text-4xl text-slate-400"></i>
                <p class="mt-3 font-extrabold">Testimoni belum tersedia</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tambahkan ulasan customer agar toko terlihat lebih meyakinkan.</p>
            </div>
        @endforelse
    </div>

    <div class="admin-scrollbar hidden overflow-x-auto lg:block">
        <table class="w-full min-w-[920px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4">@include('admin.partials.sort-link', ['sort' => 'name', 'label' => 'Customer', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => $filters['direction'] ?? 'desc'])</th>
                    <th>Testimoni</th>
                    <th>@include('admin.partials.sort-link', ['sort' => 'rating', 'label' => 'Rating', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => $filters['direction'] ?? 'desc'])</th>
                    <th>@include('admin.partials.sort-link', ['sort' => 'status', 'label' => 'Status', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => $filters['direction'] ?? 'desc'])</th>
                    <th>@include('admin.partials.sort-link', ['sort' => 'created_at', 'label' => 'Tanggal', 'defaultSort' => $filters['sort'] ?? 'created_at', 'defaultDirection' => $filters['direction'] ?? 'desc'])</th>
                    <th class="pr-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($testimonials as $testimonial)
                    @php($imageUrl = $testimonial->image_url)
                    <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="grid h-11 w-11 shrink-0 place-items-center overflow-hidden rounded-2xl bg-teal-50 text-teal-700 ring-1 ring-teal-100 dark:bg-teal-400/10 dark:text-teal-300 dark:ring-teal-400/20">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $testimonial->name }}" class="h-full w-full object-cover">
                                    @else
                                        <i class="ph ph-user-circle text-2xl"></i>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-extrabold text-slate-950 dark:text-white">{{ $testimonial->name }}</p>
                                    <p class="mt-0.5 text-xs font-semibold text-slate-400">{{ optional($testimonial->created_at)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="line-clamp-2 max-w-[390px] text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $testimonial->message }}</p></td>
                        <td>
                            <div class="flex items-center gap-0.5 text-amber-500">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ph-fill ph-star {{ $i <= (int) $testimonial->rating ? '' : 'opacity-25' }}"></i>
                                @endfor
                            </div>
                        </td>
                        <td><span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $testimonial->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">{{ $testimonial->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="font-semibold text-slate-500 dark:text-slate-400">{{ optional($testimonial->created_at)->format('d M Y') }}</td>
                        <td class="pr-5 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a class="admin-btn-secondary px-3 py-2 text-xs" href="{{ route('admin.testimonials.edit',$testimonial) }}"><i class="ph ph-pencil-simple"></i> Edit</a>
                                <form class="inline" method="POST" action="{{ route('admin.testimonials.destroy',$testimonial) }}" onsubmit="return confirm('Hapus testimoni ini?')">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-extrabold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:hover:bg-red-950/30"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty-state', ['colspan' => 6, 'icon' => 'ph-chat-teardrop-text', 'title' => 'Testimoni belum tersedia', 'description' => 'Tambahkan ulasan customer agar toko terlihat lebih meyakinkan.'])
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@include('admin.partials.pagination', ['paginator' => $testimonials])
@endsection
