@extends('layouts.app')
@section('title', 'Testimoni Customer - '.($settings['store_name'] ?? config('store.name')))

@section('content')
@php
    $activeCount = (int) ($testimonialSummary['count'] ?? 0);
    $averageRating = (float) ($testimonialSummary['average_rating'] ?? 0);
    $ratingText = $averageRating > 0 ? number_format($averageRating, 1) : '0.0';
@endphp

@once
    <style>
        #form-testimoni, #daftar-testimoni { scroll-margin-top: 6.25rem; }

        .testi-soft-shadow { box-shadow: 0 18px 50px rgba(15, 23, 42, .08); }
        .dark .testi-soft-shadow { box-shadow: 0 18px 50px rgba(0, 0, 0, .22); }

        .testi-rating-option input:checked + span {
            border-color: rgba(245, 158, 11, .72);
            background: linear-gradient(135deg, rgba(251, 191, 36, .18), rgba(255, 255, 255, .95));
            color: rgb(146 64 14);
            box-shadow: 0 10px 24px rgba(245, 158, 11, .16);
        }
        .dark .testi-rating-option input:checked + span {
            border-color: rgba(251, 191, 36, .56);
            background: rgba(245, 158, 11, .15);
            color: rgb(253 230 138);
        }

        .testi-message-clamp {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .testi-bg-grid {
            background-image:
                radial-gradient(circle at 18% 12%, rgba(20, 184, 166, .14), transparent 28%),
                radial-gradient(circle at 82% 8%, rgba(16, 185, 129, .12), transparent 27%),
                linear-gradient(180deg, rgba(255,255,255,.8), rgba(248,250,252,0));
        }
        .dark .testi-bg-grid {
            background-image:
                radial-gradient(circle at 18% 12%, rgba(45, 212, 191, .10), transparent 28%),
                radial-gradient(circle at 82% 8%, rgba(16, 185, 129, .08), transparent 27%),
                linear-gradient(180deg, rgba(15,23,42,.9), rgba(2,6,23,0));
        }

        @media (max-width: 639px) {
            .testi-soft-shadow { box-shadow: 0 12px 30px rgba(15, 23, 42, .07); }
        }
    </style>
@endonce

<section class="testi-bg-grid relative overflow-hidden px-4 pb-7 pt-7 sm:px-6 sm:pb-8 sm:pt-9 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="grid gap-4 lg:grid-cols-[1fr_auto] lg:items-end">
            <div class="max-w-2xl">
                <a href="{{ route('home') }}#pengalaman-belanja" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/80 px-3.5 py-2 text-[11px] font-extrabold uppercase tracking-[.14em] text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-200 hover:text-teal-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:border-teal-300/30 dark:hover:text-teal-200">
                    <i class="ph ph-arrow-left text-sm"></i>
                    Kembali
                </a>
                <div class="mt-5 inline-flex items-center gap-2 rounded-full bg-teal-50 px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-[.16em] text-teal-700 ring-1 ring-teal-100 dark:bg-teal-400/10 dark:text-teal-200 dark:ring-teal-400/15">
                    <i class="ph-fill ph-chat-teardrop-text text-sm"></i>
                    Cerita Customer
                </div>
                <h1 class="mt-3 text-3xl font-extrabold tracking-[-.04em] text-slate-950 dark:text-white sm:text-4xl lg:text-5xl">Pengalaman belanja dari customer</h1>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-500 dark:text-slate-400 sm:text-base sm:leading-7">Baca pengalaman pembeli lain atau kirim cerita singkat kamu. Ulasan akan dicek admin dulu sebelum tampil di halaman ini.</p>
            </div>

            <div class="grid grid-cols-2 gap-2 sm:w-[24rem] sm:gap-3">
                <div class="rounded-[1.35rem] border border-slate-200 bg-white/85 p-3.5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-white/5 sm:p-4">
                    <div class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-[.12em] text-slate-500 dark:text-slate-400">
                        <i class="ph ph-check-circle text-base text-teal-600 dark:text-teal-300"></i>
                        Tampil
                    </div>
                    <strong class="mt-1 block text-2xl font-extrabold text-slate-950 dark:text-white sm:text-3xl">{{ $activeCount }}</strong>
                    <p class="text-[11px] leading-4 text-slate-400 dark:text-slate-500">testimoni aktif</p>
                </div>
                <div class="rounded-[1.35rem] border border-slate-200 bg-white/85 p-3.5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-white/5 sm:p-4">
                    <div class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-[.12em] text-slate-500 dark:text-slate-400">
                        <i class="ph-fill ph-star text-base text-amber-500"></i>
                        Rating
                    </div>
                    <strong class="mt-1 block text-2xl font-extrabold text-slate-950 dark:text-white sm:text-3xl">{{ $ratingText }}</strong>
                    <p class="text-[11px] leading-4 text-slate-400 dark:text-slate-500">rata-rata ulasan</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto grid max-w-7xl gap-5 px-4 pb-24 sm:px-6 sm:pb-12 lg:grid-cols-[25rem_1fr] lg:gap-6 lg:px-8">
    <aside id="form-testimoni" class="lg:sticky lg:top-24 lg:self-start">
        <div class="testi-soft-shadow overflow-hidden rounded-[1.55rem] border border-slate-200 bg-white dark:border-white/10 dark:bg-slate-950 sm:rounded-[1.75rem]">
            <div class="relative overflow-hidden border-b border-slate-100 bg-gradient-to-br from-slate-950 via-slate-900 to-teal-950 px-4 py-4 text-white dark:border-white/10 sm:px-5">
                <div class="pointer-events-none absolute -right-12 -top-16 h-40 w-40 rounded-full bg-teal-300/20 blur-3xl"></div>
                <div class="relative flex items-center gap-3">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-white/10 text-teal-100 ring-1 ring-white/10">
                        <i class="ph ph-pencil-simple-line text-xl"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-teal-100/80">Tulis testimoni</p>
                        <h2 class="mt-0.5 text-lg font-extrabold tracking-tight sm:text-xl">Bagikan pengalamanmu</h2>
                    </div>
                </div>
            </div>

            <form action="{{ route('testimonials.store') }}" method="post" class="space-y-3.5 p-4 sm:p-5" novalidate>
                @csrf

                @if(session('success'))
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-3 text-xs font-bold leading-5 text-emerald-700 dark:border-emerald-400/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                        <div class="flex gap-2">
                            <i class="ph-fill ph-check-circle mt-0.5 text-base"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="rounded-2xl border border-rose-100 bg-rose-50 p-3 text-xs font-bold leading-5 text-rose-700 dark:border-rose-400/20 dark:bg-rose-500/10 dark:text-rose-200">
                        <div class="flex gap-2">
                            <i class="ph-fill ph-warning-circle mt-0.5 text-base"></i>
                            <div>
                                <p>Mohon cek kembali data testimoni.</p>
                                <ul class="mt-1 list-inside list-disc text-[11px] font-semibold">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <label class="block">
                    <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[.14em] text-slate-500 dark:text-slate-400">Nama kamu</span>
                    <div class="relative">
                        <i class="ph ph-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input name="name" value="{{ old('name') }}" maxlength="80" placeholder="Contoh: Rina" class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm font-semibold text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:bg-white focus:ring-4 focus:ring-teal-500/10 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-teal-300">
                    </div>
                </label>

                <div>
                    <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[.14em] text-slate-500 dark:text-slate-400">Rating pengalaman</span>
                    <div class="grid grid-cols-5 gap-1.5">
                        @foreach([5 => 'Sangat puas', 4 => 'Puas', 3 => 'Cukup', 2 => 'Kurang', 1 => 'Tidak puas'] as $value => $label)
                            <label class="testi-rating-option min-w-0 cursor-pointer" title="{{ $label }}">
                                <input type="radio" name="rating" value="{{ $value }}" class="peer sr-only" @checked((string) old('rating', '5') === (string) $value)>
                                <span class="flex h-10 items-center justify-center gap-1 rounded-2xl border border-slate-200 bg-slate-50 px-1.5 text-xs font-extrabold text-slate-500 transition hover:border-amber-200 hover:bg-amber-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-300">
                                    <i class="ph-fill ph-star text-amber-500"></i>{{ $value }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <label class="block">
                    <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[.14em] text-slate-500 dark:text-slate-400">Ceritakan singkat</span>
                    <textarea name="message" rows="4" maxlength="500" placeholder="Contoh: Produknya mudah dipilih, checkout jelas, dan adminnya responsif..." class="min-h-[7rem] w-full resize-none rounded-2xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm font-medium leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:bg-white focus:ring-4 focus:ring-teal-500/10 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-teal-300">{{ old('message') }}</textarea>
                </label>

                <div class="rounded-2xl border border-teal-100 bg-teal-50/80 p-3 text-[11px] font-semibold leading-5 text-slate-600 dark:border-teal-400/15 dark:bg-teal-400/10 dark:text-slate-300 sm:text-xs">
                    <div class="flex gap-2">
                        <i class="ph ph-shield-check mt-0.5 text-base text-teal-700 dark:text-teal-300"></i>
                        <span>Testimoni masuk ke admin dulu sebelum ditampilkan, supaya halaman tetap rapi dan terpercaya.</span>
                    </div>
                </div>

                <button class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-2xl bg-teal-700 px-4 text-sm font-extrabold text-white shadow-lg shadow-teal-700/20 transition hover:-translate-y-0.5 hover:bg-teal-800 active:translate-y-0 dark:bg-teal-500 dark:text-slate-950 dark:hover:bg-teal-400">
                    <i class="ph ph-paper-plane-tilt text-lg"></i>
                    Kirim Testimoni
                </button>
            </form>
        </div>
    </aside>

    <div id="daftar-testimoni">
        <div class="mb-4 flex flex-col gap-3 sm:mb-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="inline-flex items-center gap-2 rounded-full bg-teal-50 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[.14em] text-teal-700 ring-1 ring-teal-100 dark:bg-teal-400/10 dark:text-teal-200 dark:ring-teal-400/15">
                    <i class="ph ph-sparkle text-sm"></i>
                    Ulasan Terbaru
                </p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-3xl">Apa kata customer?</h2>
            </div>
            <a href="#form-testimoni" class="inline-flex h-10 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 text-xs font-extrabold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-200 hover:text-teal-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:border-teal-300/30 dark:hover:text-teal-200 sm:text-sm">
                <i class="ph ph-pencil-simple-line"></i>
                Tulis juga
            </a>
        </div>

        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @forelse($testimonials as $testimonial)
                @php($testimonialImageUrl = $testimonial->image_url)
                <article class="group testi-soft-shadow relative overflow-hidden rounded-[1.35rem] border border-slate-200 bg-white p-4 transition duration-200 hover:-translate-y-0.5 hover:border-teal-100 dark:border-white/10 dark:bg-white/[.045] dark:hover:border-teal-400/25 sm:rounded-[1.55rem]">
                    <div class="pointer-events-none absolute -right-14 -top-14 h-32 w-32 rounded-full bg-teal-200/20 blur-3xl dark:bg-teal-400/10"></div>

                    <div class="relative flex items-start gap-3">
                        <div class="grid h-10 w-10 shrink-0 place-items-center overflow-hidden rounded-2xl bg-teal-50 text-teal-700 ring-1 ring-teal-100 dark:bg-teal-400/10 dark:text-teal-300 dark:ring-teal-400/20">
                            @if($testimonialImageUrl)
                                <img src="{{ $testimonialImageUrl }}" alt="{{ $testimonial->name }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-sm font-extrabold">{{ strtoupper(substr($testimonial->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-sm font-extrabold leading-5 text-slate-950 dark:text-white">{{ $testimonial->name }}</h3>
                            <div class="mt-1 flex items-center gap-0.5 text-amber-500">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ph-fill ph-star text-xs {{ $i <= (int) $testimonial->rating ? '' : 'opacity-25' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-2xl bg-slate-50 text-slate-400 transition group-hover:bg-teal-50 group-hover:text-teal-700 dark:bg-white/5 dark:text-slate-500 dark:group-hover:bg-teal-400/10 dark:group-hover:text-teal-300">
                            <i class="ph ph-quotes text-sm"></i>
                        </span>
                    </div>

                    <p class="testi-message-clamp relative mt-3 min-h-[5.9rem] text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $testimonial->message }}</p>

                    <div class="relative mt-4 flex items-center justify-between border-t border-slate-100 pt-3 dark:border-white/10">
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500">{{ optional($testimonial->created_at)->format('d M Y') }}</span>
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-[.1em] text-slate-500 dark:bg-white/5 dark:text-slate-400">
                            Verified
                        </span>
                    </div>
                </article>
            @empty
                <div class="md:col-span-2 xl:col-span-3 rounded-[1.65rem] border border-dashed border-slate-300 bg-white/70 p-8 text-center dark:border-white/15 dark:bg-white/5">
                    <div class="mx-auto grid h-13 w-13 place-items-center rounded-2xl bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-300">
                        <i class="ph ph-chat-teardrop-text text-3xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-extrabold text-slate-950 dark:text-white">Belum ada testimoni aktif</h3>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-slate-500 dark:text-slate-400">Testimoni yang sudah disetujui admin akan tampil di halaman ini. Kamu tetap bisa mengirim testimoni lewat form di samping.</p>
                </div>
            @endforelse
        </div>

        @if(method_exists($testimonials, 'links') && $testimonials->hasPages())
            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm dark:border-white/10 dark:bg-white/5">
                {{ $testimonials->links() }}
            </div>
        @endif
    </div>
</section>

@include('partials.mobile-bottom-menu', ['bottomActive' => 'testimonials'])
@endsection
