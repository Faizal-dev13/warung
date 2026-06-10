@extends('admin.layouts.app')
@section('page_title', $testimonial->exists ? 'Edit Testimoni' : 'Tambah Testimoni')
@section('page_description','Kelola ulasan customer yang tampil di halaman depan toko.')
@section('page_action')
<a href="{{ route('admin.testimonials.index') }}" class="admin-btn-secondary"><i class="ph ph-arrow-left"></i> Kembali</a>
@endsection
@section('content')
@php($imageUrl = $testimonial->image_url)
<form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update',$testimonial) : route('admin.testimonials.store') }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @if($testimonial->exists)@method('PUT')@endif

    <section class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="bg-slate-950 p-5 text-white sm:p-6">
            <div class="flex items-start gap-3">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-teal-600 text-white shadow-lg shadow-teal-950/30"><i class="ph ph-chat-teardrop-text text-2xl"></i></span>
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[.22em] text-teal-200">Testimoni Customer</p>
                    <h2 class="mt-1 text-xl font-extrabold">{{ $testimonial->exists ? 'Perbarui Testimoni' : 'Tambah Testimoni Baru' }}</h2>
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-300">Isi testimoni singkat dan natural agar customer baru lebih percaya sebelum checkout.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 p-5 sm:p-6">
            <div class="grid gap-5 lg:grid-cols-[1fr_280px]">
                <div class="space-y-5">
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Nama Customer</span>
                        <input name="name" value="{{ old('name',$testimonial->name) }}" class="admin-input" placeholder="Contoh: Rina" required>
                        @error('name')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Isi Testimoni</span>
                        <textarea name="message" rows="7" class="admin-textarea" placeholder="Tulis ulasan customer secara singkat..." required>{{ old('message',$testimonial->message) }}</textarea>
                        <p class="mt-2 text-xs font-semibold text-slate-400">Maksimal 1200 karakter. Usahakan tetap ringkas agar enak dibaca di homepage.</p>
                        @error('message')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </label>
                </div>

                <div class="space-y-5">
                    <label class="block">
                        <span class="mb-2 block text-sm font-extrabold">Rating</span>
                        <select name="rating" class="admin-select" required>
                            @foreach([5 => '5 - Sangat puas', 4 => '4 - Puas', 3 => '3 - Cukup', 2 => '2 - Kurang', 1 => '1 - Tidak puas'] as $value => $label)
                                <option value="{{ $value }}" @selected((int) old('rating',$testimonial->rating ?? 5) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('rating')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </label>

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                        <span class="mb-3 block text-sm font-extrabold">Foto Customer <span class="font-semibold text-slate-400">opsional</span></span>
                        <div class="flex items-center gap-3">
                            <div class="grid h-20 w-20 shrink-0 place-items-center overflow-hidden rounded-3xl bg-white text-slate-400 ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $testimonial->name }}" class="h-full w-full object-cover">
                                @else
                                    <i class="ph ph-user-circle text-4xl"></i>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <input type="file" name="image" accept="image/png,image/jpeg,image/jpg,image/webp" class="admin-input">
                                <p class="mt-2 text-xs font-semibold text-slate-400">Format JPG, PNG, atau WEBP. Maksimal 2MB.</p>
                            </div>
                        </div>
                        @if($imageUrl)
                            <label class="mt-4 flex cursor-pointer items-start gap-2 rounded-2xl border border-red-100 bg-white p-3 text-sm font-bold text-red-600 dark:border-red-900/50 dark:bg-slate-900">
                                <input type="checkbox" name="remove_image" value="1" class="mt-0.5 h-4 w-4 rounded border-red-200 text-red-600 focus:ring-red-500">
                                Hapus foto lama
                            </label>
                        @endif
                        @error('image')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <label class="flex cursor-pointer items-start gap-3 rounded-3xl border border-slate-200 bg-slate-50 p-4 transition hover:border-teal-200 hover:bg-teal-50/40 dark:border-slate-800 dark:bg-slate-950/40">
                        <input type="checkbox" name="is_active" value="1" class="mt-1 h-5 w-5 rounded border-slate-300 text-teal-700 focus:ring-teal-600" @checked(old('is_active',$testimonial->is_active ?? true))>
                        <span>
                            <b class="block text-sm text-slate-950 dark:text-white">Testimoni Aktif</b>
                            <small class="mt-1 block text-slate-500 dark:text-slate-400">Tampilkan testimoni ini di homepage.</small>
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </section>

    <div class="sticky bottom-4 z-10 flex flex-col-reverse gap-3 rounded-3xl border border-slate-200 bg-white/90 p-3 shadow-soft backdrop-blur dark:border-slate-800 dark:bg-slate-900/90 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ route('admin.testimonials.index') }}" class="admin-btn-secondary">Batal</a>
        <button class="admin-btn-primary"><i class="ph ph-check-circle"></i> Simpan Testimoni</button>
    </div>
</form>
@endsection
