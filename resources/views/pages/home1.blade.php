@extends('layouts.app')
@section('title', config('store.name').' - Belanja Produk Digital')
@section('content')
@once
    <style>
        html {
            scroll-behavior: smooth;
        }

        #home,
        #produk,
        #testimoni,
        #voucher {
            scroll-margin-top: 5.5rem;
        }

        .home-product-grid {
            align-items: stretch;
        }

        .home-product-card {
            min-width: 0;
            height: 100%;
        }

        .home-product-card .store-product-card {
            height: 100%;
            border-radius: 1.35rem;
            box-shadow: 0 12px 34px rgba(15, 23, 42, .065);
        }

        .home-product-card .store-product-media {
            aspect-ratio: 4 / 3;
        }

        .home-product-card .store-product-photo {
            display: block !important;
            width: 100% !important;
            height: 100% !important;
            max-height: none !important;
            object-fit: cover !important;
            opacity: 1 !important;
            visibility: visible !important;
        }


        .testimonial-slider-track {
            cursor: grab;
            overscroll-behavior-x: contain;
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
            touch-action: pan-x;
        }

        .testimonial-slider-track.is-dragging {
            cursor: grabbing;
            scroll-behavior: auto;
            scroll-snap-type: none;
        }

        .testimonial-slider-track::-webkit-scrollbar {
            display: none;
        }

        .testimonial-slide {
            flex: 0 0 clamp(16.5rem, 82vw, 19.5rem);
            scroll-snap-align: start;
            scroll-snap-stop: normal;
        }

        .testimonial-dot.is-active {
            width: 1.45rem;
            background: rgb(15 118 110);
        }

        .dark .testimonial-dot.is-active {
            background: rgb(45 212 191);
        }



        .testimonial-modal {
            position: fixed;
            inset: 0;
            z-index: 2147483000;
            display: none;
            overflow-y: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
            padding: clamp(1.35rem, 5vh, 3.4rem) clamp(.85rem, 2vw, 1.5rem);
            isolation: isolate;
        }

        .testimonial-modal.is-open {
            display: grid;
            place-items: center;
        }

        .testimonial-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1;
            background:
                radial-gradient(circle at 50% 18%, rgba(20, 184, 166, .16), transparent 38%),
                rgba(2, 6, 23, .78);
            backdrop-filter: blur(18px);
            animation: testimonialBackdropIn .18s ease-out both;
        }

        .testimonial-modal-close-area {
            position: fixed;
            inset: 0;
            z-index: 2;
            width: 100%;
            height: 100%;
            cursor: default;
        }

        .testimonial-modal-panel {
            position: relative;
            z-index: 3;
            width: min(100%, 31.5rem);
            max-height: min(40rem, calc(100svh - clamp(2.7rem, 10vh, 6.8rem)));
            margin: auto;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 1.45rem;
            border: 1px solid rgba(255,255,255,.14);
            background: rgba(255,255,255,.98);
            box-shadow: 0 28px 90px rgba(2, 6, 23, .42);
            animation: testimonialModalIn .2s ease-out both;
            scrollbar-gutter: stable;
        }

        .dark .testimonial-modal-panel {
            background: rgba(2, 6, 23, .98);
            border-color: rgba(255,255,255,.12);
        }

        .testimonial-modal-header {
            position: relative;
            flex: 0 0 auto;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(45, 212, 191, .24), transparent 42%),
                radial-gradient(circle at bottom left, rgba(16, 185, 129, .14), transparent 46%),
                linear-gradient(135deg, rgb(2 6 23), rgb(15 23 42));
            color: white;
            padding: .85rem .95rem .78rem;
        }

        .testimonial-modal-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            background: white;
            padding: .9rem .95rem .95rem;
            scrollbar-width: thin;
        }

        .testimonial-modal-body::-webkit-scrollbar {
            width: .36rem;
        }

        .testimonial-modal-body::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: rgba(148, 163, 184, .55);
        }

        .dark .testimonial-modal-body {
            background: rgb(2 6 23);
        }

        .testimonial-form-stack {
            display: grid;
            gap: .72rem;
        }

        .testimonial-form-label {
            margin-bottom: .32rem;
            display: block;
            font-size: .64rem;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgb(100 116 139);
        }

        .dark .testimonial-form-label {
            color: rgb(148 163 184);
        }

        .testimonial-rating-card {
            min-width: 0;
        }

        .testimonial-modal-grip {
            display: none;
        }

        .testimonial-client-error.is-visible {
            display: flex;
        }

        @media (max-width: 639px) {
            .testimonial-modal {
                padding: max(1rem, env(safe-area-inset-top)) .82rem max(1rem, env(safe-area-inset-bottom));
            }

            .testimonial-modal-panel {
                width: min(100%, 23rem);
                max-height: calc(100svh - max(2rem, env(safe-area-inset-top) + env(safe-area-inset-bottom)));
                border-radius: 1.25rem;
            }

            .testimonial-modal-header {
                padding: .72rem .78rem .68rem;
            }

            .testimonial-modal-body {
                padding: .78rem .78rem .82rem;
            }

            .testimonial-form-stack {
                gap: .64rem;
            }

            .testimonial-form-label {
                margin-bottom: .28rem;
                font-size: .58rem;
                letter-spacing: .105em;
            }

            .testimonial-rating-grid {
                grid-template-columns: repeat(5, minmax(0, 1fr));
                gap: .34rem;
            }

            .testimonial-rating-card > span {
                height: 2.12rem;
                border-radius: .8rem;
                padding-left: .05rem;
                padding-right: .05rem;
                font-size: .7rem;
            }

            .testimonial-rating-card .rating-stars {
                font-size: .72rem;
            }
        }

        @media (max-height: 620px) and (min-width: 640px) {
            .testimonial-modal {
                padding-top: 1.15rem;
                padding-bottom: 1.15rem;
            }

            .testimonial-modal-panel {
                max-height: calc(100svh - 2.3rem);
            }
        }

        @keyframes testimonialModalIn {
            from {
                opacity: 0;
                transform: translateY(10px) scale(.985);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes testimonialBackdropIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (min-width: 640px) {
            .testimonial-slide {
                flex-basis: calc((100% - 1.25rem) / 2);
            }
        }

        @media (min-width: 1024px) {
            .testimonial-slide {
                flex-basis: calc((100% - 2.5rem) / 3);
            }
        }

        .mobile-bottom-link,
        .mobile-bottom-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .25rem;
            border-radius: 1rem;
            padding: .55rem .5rem;
            font-size: 10px;
            font-weight: 800;
            line-height: 1;
            transition: transform .18s ease, color .18s ease, background .18s ease, box-shadow .18s ease;
        }

        .mobile-bottom-link {
            color: rgb(100 116 139);
        }

        .dark .mobile-bottom-link {
            color: rgb(148 163 184);
        }

        .mobile-bottom-link:hover {
            background: rgb(241 245 249);
            color: rgb(15 23 42);
        }

        .dark .mobile-bottom-link:hover {
            background: rgba(255, 255, 255, .1);
            color: #fff;
        }

        .mobile-bottom-link.is-active {
            background: rgb(15 23 42);
            color: #fff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .18);
        }

        .dark .mobile-bottom-link.is-active {
            background: rgb(15 118 110);
            color: #fff;
            box-shadow: 0 10px 24px rgba(15, 118, 110, .28);
        }

        .mobile-bottom-action {
            position: relative;
            border: 1px solid rgba(15, 118, 110, .18);
            background: linear-gradient(135deg, rgba(15, 118, 110, .1), rgba(5, 150, 105, .12));
            color: rgb(15 118 110);
        }

        .dark .mobile-bottom-action {
            border-color: rgba(45, 212, 191, .22);
            background: rgba(15, 118, 110, .22);
            color: rgb(153 246 228);
        }

        .mobile-bottom-action:active {
            transform: scale(.96);
        }

        @media (max-width: 767px) {
            body {
                padding-bottom: 5.75rem;
            }

            .mobile-bottom-menu {
                padding-bottom: max(.75rem, env(safe-area-inset-bottom));
            }
        }

        @media (max-width: 639px) {
            .home-product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: .72rem;
            }

            .home-product-card .store-product-card {
                border-radius: 1.05rem;
                box-shadow: 0 10px 24px rgba(15, 23, 42, .075);
            }

            .home-product-card .store-product-card > a {
                padding: .55rem .55rem 0 !important;
            }

            .home-product-card .store-product-media {
                aspect-ratio: 1 / .78;
                min-height: 6.95rem;
                border-radius: .85rem !important;
            }

            .home-product-card .store-product-body {
                padding: .65rem !important;
            }

            .home-product-card .store-product-category {
                font-size: .62rem !important;
                line-height: .85rem !important;
            }

            .home-product-card .store-product-title {
                min-height: 2.14rem;
                font-size: .82rem !important;
                line-height: 1.07rem !important;
                letter-spacing: -.01em;
            }

            .home-product-card .store-product-summary {
                min-height: 1rem;
                font-size: .69rem !important;
                line-height: .98rem !important;
                -webkit-line-clamp: 1 !important;
            }

            .home-product-card .store-product-old-price {
                font-size: .64rem !important;
                line-height: .8rem !important;
            }

            .home-product-card .store-product-price {
                font-size: .9rem !important;
                line-height: 1.1rem !important;
            }

            .home-product-card .store-product-footer {
                padding-top: .62rem !important;
            }

            .home-product-card .store-product-actions {
                grid-template-columns: minmax(0, 1fr) 2.12rem;
                gap: .42rem !important;
                margin-top: .55rem !important;
            }

            .home-product-card .store-product-add-button,
            .home-product-card .store-product-detail-button {
                height: 2.12rem !important;
                min-height: 2.12rem !important;
                border-radius: .78rem !important;
                font-size: .68rem !important;
                line-height: .9rem !important;
                white-space: nowrap;
            }

            .home-product-card .store-product-add-button {
                padding-inline: .45rem !important;
            }

            .home-product-card .store-product-detail-button {
                width: 2.12rem !important;
                min-width: 2.12rem !important;
            }

            .home-product-card .store-product-add-button i,
            .home-product-card .store-product-detail-button i {
                font-size: .92rem !important;
            }

            .mobile-bottom-link,
            .mobile-bottom-action {
                min-height: 3.35rem;
            }
        }
    </style>
@endonce

<section id="home" class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
    <div class="grid items-start gap-4 lg:grid-cols-[1.25fr_.75fr] lg:gap-5">
        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft dark:border-white/10 dark:bg-white/5 sm:rounded-[2rem]">
            <div data-banner-slider class="relative">
                @forelse($banners as $banner)
                    @php
                        $bannerImagePath = $banner->image_path ?? null;
                        $bannerImageUrl = $bannerImagePath
                            ? (\Illuminate\Support\Str::startsWith($bannerImagePath, ['http://', 'https://', '/']) ? $bannerImagePath : \Illuminate\Support\Facades\Storage::url($bannerImagePath))
                            : null;

                        $bannerMobileImagePath = $banner->mobile_image_path ?? null;
                        $bannerMobileImageUrl = $bannerMobileImagePath
                            ? (\Illuminate\Support\Str::startsWith($bannerMobileImagePath, ['http://', 'https://', '/']) ? $bannerMobileImagePath : \Illuminate\Support\Facades\Storage::url($bannerMobileImagePath))
                            : null;
                    @endphp
                    <article data-banner-slide class="{{ $loop->first ? '' : 'hidden' }} relative aspect-[4/3] min-h-0 overflow-hidden {{ $bannerImageUrl ? 'bg-slate-950' : 'bg-gradient-to-br '.($banner->accent ?: 'from-slate-950 to-blue-950') }} p-6 text-white sm:aspect-[8/3] sm:p-10">
                        @if($bannerImageUrl)
                            <picture class="absolute inset-0 block h-full w-full">
                                @if($bannerMobileImageUrl)
                                    <source media="(max-width: 639px)" srcset="{{ $bannerMobileImageUrl }}">
                                @endif
                                <img src="{{ $bannerImageUrl }}" alt="{{ $banner->title }}" class="absolute inset-0 block h-full w-full object-cover">
                            </picture>
                            <div class="absolute inset-0 bg-gradient-to-br from-slate-950/82 via-slate-950/55 to-slate-950/20"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/55 via-transparent to-transparent"></div>
                        @endif

                        <div class="relative z-10 flex h-full flex-col justify-center gap-8">
                            <div>
                                @if($banner->label)
                                    <span class="inline-flex rounded-full bg-white/15 px-3.5 py-2 text-[11px] font-bold uppercase tracking-wide text-white/90 ring-1 ring-white/15 backdrop-blur sm:px-4 sm:text-xs">{{ $banner->label }}</span>
                                @endif

                                <h1 class="mt-5 max-w-2xl text-3xl font-extrabold leading-tight tracking-tight drop-shadow-sm sm:mt-6 sm:text-5xl">{{ $banner->title }}</h1>

                                @if($banner->subtitle)
                                    <p class="mt-4 max-w-xl text-sm leading-7 text-white/78 drop-shadow-sm sm:text-base">{{ $banner->subtitle }}</p>
                                @endif
                            </div>

                        </div>

                        @unless($bannerImageUrl)
                            <div class="pointer-events-none absolute -right-16 -top-16 h-52 w-52 rounded-full bg-white/10 blur-2xl sm:h-72 sm:w-72"></div>
                            <i class="ph {{ $banner->icon ?: 'ph-image-square' }} pointer-events-none absolute bottom-6 right-6 text-7xl text-white/15 sm:bottom-8 sm:right-8 sm:text-9xl"></i>
                        @endunless
                    </article>
                @empty
                    <article class="relative aspect-[4/3] min-h-0 overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 p-6 text-white sm:aspect-[8/3] sm:p-10">
                        <div class="relative z-10 max-w-2xl">
                            <span class="inline-flex rounded-full bg-white/15 px-3.5 py-2 text-[11px] font-bold uppercase tracking-wide text-white/90 backdrop-blur">Belanja Praktis</span>
                            <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight sm:text-5xl">Temukan produk pilihan dan pesan lebih mudah.</h1>
                            <p class="mt-4 max-w-xl text-sm leading-7 text-white/75 sm:text-base">Pilih produk favorit, masukkan ke keranjang, lalu lanjutkan konfirmasi pesanan melalui WhatsApp.</p>
                        </div>
                        <div class="pointer-events-none absolute -right-16 -top-16 h-52 w-52 rounded-full bg-white/10 blur-2xl sm:h-72 sm:w-72"></div>
                        <i class="ph ph-shopping-bag-open pointer-events-none absolute bottom-6 right-6 text-7xl text-white/15 sm:bottom-8 sm:right-8 sm:text-9xl"></i>
                    </article>
                @endforelse

                @if($banners->count() > 1)
                    <div class="absolute bottom-5 left-6 z-20 flex gap-2 sm:left-7">
                        @foreach($banners as $banner)
                            <button data-banner-dot type="button" class="h-2.5 w-2.5 rounded-full bg-white/40 transition first:bg-white" aria-label="Banner {{ $loop->iteration }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="hidden md:block lg:h-full">
            <div class="flex h-full items-start gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-white/10 dark:bg-white/5 sm:rounded-[2rem] sm:p-6 lg:block">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100 dark:bg-emerald-400/10 dark:text-emerald-300 dark:ring-emerald-400/20">
                    <i class="ph-fill ph-whatsapp-logo text-2xl"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm font-extrabold leading-tight text-slate-950 dark:text-white sm:mt-4 sm:text-base">Pesan Lebih Cepat</h3>
                    <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400 sm:mt-2 sm:text-sm sm:leading-6">Pilih produk, masuk keranjang, lalu konfirmasi pesanan tanpa proses yang membingungkan.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 hidden gap-3 md:grid md:grid-cols-2 lg:gap-5">
        <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-white/10 dark:bg-white/5 sm:rounded-[2rem] sm:p-6">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-blue-100 dark:bg-blue-400/10 dark:text-blue-300 dark:ring-blue-400/20">
                <i class="ph ph-squares-four text-2xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-sm font-extrabold leading-tight text-slate-950 dark:text-white sm:mt-4 sm:text-base">Katalog Rapi</h3>
                <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400 sm:mt-2 sm:text-sm sm:leading-6">Produk, kategori, dan promo tersusun jelas agar mudah dibandingkan.</p>
            </div>
        </div>

        <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-white/10 dark:bg-white/5 sm:rounded-[2rem] sm:p-6">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-violet-50 text-violet-600 ring-1 ring-violet-100 dark:bg-violet-400/10 dark:text-violet-300 dark:ring-violet-400/20">
                <i class="ph ph-sparkle text-2xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-sm font-extrabold leading-tight text-slate-950 dark:text-white sm:mt-4 sm:text-base">Nyaman Dilihat</h3>
                <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400 sm:mt-2 sm:text-sm sm:leading-6">Tampilan ringan dan responsif, tetap rapi saat dibuka dari HP maupun desktop.</p>
            </div>
        </div>
    </div>
</section>

<section id="produk" class="mx-auto max-w-7xl px-4 pt-7 pb-8 sm:px-6 sm:pt-10 sm:pb-8 md:pb-8 lg:px-8">
    <div class="relative mb-6 text-center md:mb-8">
        <div class="mx-auto max-w-2xl">
            <p class="text-sm font-extrabold uppercase tracking-wide text-teal-700 dark:text-teal-300">Katalog Produk</p>
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-4xl">Pilih produk yang paling sesuai</h2>
        </div>
        <a href="{{ route('products.index') }}" class="hidden items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:bg-white dark:text-slate-950 md:absolute md:right-0 md:top-1/2 md:inline-flex md:-translate-y-1/2">
            Lihat Selengkapnya <i class="ph ph-arrow-right"></i>
        </a>
    </div>

    <div class="mb-6 hidden gap-3 rounded-[1.5rem] border border-teal-100 bg-gradient-to-br from-teal-50 via-white to-emerald-50 p-3 shadow-sm dark:border-teal-400/15 dark:from-teal-500/10 dark:via-white/5 dark:to-emerald-500/10 sm:mb-8 sm:p-4 md:grid md:grid-cols-[1fr_auto] md:items-center">
        <div class="flex items-start gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-teal-700 text-white shadow-lg shadow-teal-700/20">
                <i class="ph ph-shopping-cart-simple text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-slate-950 dark:text-white sm:text-base">Checkout lebih praktis</h3>
                <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400 sm:text-sm">Tambahkan beberapa produk ke keranjang, cek ringkasannya, lalu lanjut konfirmasi pesanan via WhatsApp.</p>
            </div>
        </div>
        <button data-cart-toggle type="button" class="inline-flex h-11 items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:shadow-md dark:bg-white dark:text-slate-950">
            Buka Checkout <i class="ph ph-arrow-right"></i>
        </button>
    </div>

    <div class="home-product-grid grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
        @forelse($products as $product)
            <div class="home-product-card min-w-0">
                @include('products.card', ['product' => $product])
            </div>
        @empty
            <div class="col-span-full rounded-[1.5rem] border border-dashed border-slate-300 bg-white/60 p-8 text-center dark:border-white/15 dark:bg-white/5 sm:rounded-[2rem] sm:p-10">
                <i class="ph ph-magnifying-glass text-5xl text-slate-400"></i>
                <h3 class="mt-4 text-lg font-extrabold text-slate-950 dark:text-white sm:text-xl">Produk tidak ditemukan</h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 sm:text-base">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6 flex justify-center">
        <a href="{{ route('products.index') }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-6 text-sm font-extrabold text-slate-950 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-white/10 dark:bg-white/10 dark:text-white">
            Lihat Selengkapnya <i class="ph ph-arrow-right"></i>
        </a>
    </div>
</section>

@php($testimonialList = $testimonials ?? collect())
<section id="testimoni" class="mx-auto max-w-7xl px-4 pt-1 pb-7 sm:px-6 sm:pt-3 sm:pb-9 lg:px-8">
    <div class="mb-5 text-center sm:mb-7">
        <p class="text-xs font-extrabold uppercase tracking-wide text-teal-700 dark:text-teal-300 sm:text-sm">Testimoni Customer</p>
        <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-4xl">Apa kata customer?</h2>
        <p class="mx-auto mt-2 max-w-xl text-sm leading-6 text-slate-500 dark:text-slate-400 sm:mt-3 sm:text-base">Cerita singkat dari customer setelah menggunakan produk dan layanan kami.</p>
    </div>

    @if($testimonialList->isNotEmpty())
        <div data-testimonial-slider class="relative">
            <div data-testimonial-track class="testimonial-slider-track -mx-4 flex gap-3 overflow-x-auto px-4 pb-3 sm:mx-0 sm:gap-5 sm:px-0 sm:pb-4">
                @foreach($testimonialList as $testimonial)
                    @php($testimonialImageUrl = $testimonial->image_url)
                    <article data-testimonial-slide class="testimonial-slide group rounded-[1.25rem] border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-soft dark:border-white/10 dark:bg-white/5 sm:rounded-[1.5rem] sm:p-5">
                        <div class="flex items-center gap-3">
                            <div class="grid h-10 w-10 shrink-0 place-items-center overflow-hidden rounded-2xl bg-teal-50 text-teal-700 ring-1 ring-teal-100 dark:bg-teal-400/10 dark:text-teal-300 dark:ring-teal-400/20 sm:h-11 sm:w-11">
                                @if($testimonialImageUrl)
                                    <img src="{{ $testimonialImageUrl }}" alt="{{ $testimonial->name }}" class="h-full w-full object-cover" draggable="false">
                                @else
                                    <i class="ph ph-user-circle text-xl sm:text-2xl"></i>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate text-sm font-extrabold text-slate-950 dark:text-white sm:text-base">{{ $testimonial->name }}</h3>
                                <div class="mt-1 flex items-center gap-0.5 text-amber-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="ph-fill ph-star text-xs sm:text-sm {{ $i <= (int) $testimonial->rating ? '' : 'opacity-25' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-2xl bg-slate-50 text-slate-400 transition group-hover:bg-teal-50 group-hover:text-teal-700 dark:bg-white/5 dark:text-slate-500 dark:group-hover:bg-teal-400/10 dark:group-hover:text-teal-300">
                                <i class="ph ph-quotes text-sm"></i>
                            </span>
                        </div>
                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600 dark:text-slate-300 sm:mt-4">{{ $testimonial->message }}</p>
                    </article>
                @endforeach
            </div>

            @if($testimonialList->count() > 1)
                <button data-testimonial-prev type="button" class="absolute left-0 top-1/2 z-10 hidden h-10 w-10 -translate-x-2 -translate-y-1/2 place-items-center rounded-full border border-slate-200 bg-white/95 text-slate-700 shadow-lg shadow-slate-900/10 backdrop-blur transition hover:-translate-x-3 hover:bg-slate-950 hover:text-white dark:border-white/10 dark:bg-slate-900/95 dark:text-white dark:hover:bg-teal-600 sm:grid" aria-label="Testimoni sebelumnya">
                    <i class="ph ph-caret-left text-lg"></i>
                </button>
                <button data-testimonial-next type="button" class="absolute right-0 top-1/2 z-10 hidden h-10 w-10 translate-x-2 -translate-y-1/2 place-items-center rounded-full border border-slate-200 bg-white/95 text-slate-700 shadow-lg shadow-slate-900/10 backdrop-blur transition hover:translate-x-3 hover:bg-slate-950 hover:text-white dark:border-white/10 dark:bg-slate-900/95 dark:text-white dark:hover:bg-teal-600 sm:grid" aria-label="Testimoni berikutnya">
                    <i class="ph ph-caret-right text-lg"></i>
                </button>

                <div class="mt-1 flex items-center justify-center gap-1.5 sm:mt-2">
                    @foreach($testimonialList as $testimonial)
                        <button data-testimonial-dot data-index="{{ $loop->index }}" type="button" class="testimonial-dot h-2 w-2 rounded-full bg-slate-300 transition-all duration-300 hover:bg-teal-600 dark:bg-white/20 dark:hover:bg-teal-300 {{ $loop->first ? 'is-active' : '' }}" aria-label="Lihat testimoni {{ $loop->iteration }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-white/70 p-6 text-center shadow-sm dark:border-white/15 dark:bg-white/5 sm:rounded-[2rem]">
            <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-300">
                <i class="ph ph-chat-teardrop-text text-2xl"></i>
            </div>
            <h3 class="mt-4 text-base font-extrabold text-slate-950 dark:text-white">Belum ada testimoni aktif</h3>
            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500 dark:text-slate-400">Jadilah customer pertama yang membagikan pengalaman setelah menggunakan produk kami.</p>
        </div>
    @endif

    <div id="kirim-testimoni" class="mt-5 sm:mt-6">
        <div class="relative overflow-hidden rounded-[1.5rem] border border-white/10 bg-slate-950 p-4 text-white shadow-soft sm:rounded-[2rem] sm:p-6 lg:p-7">
            <div class="pointer-events-none absolute -right-20 -top-20 h-56 w-56 rounded-full bg-teal-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-20 h-64 w-64 rounded-full bg-emerald-400/15 blur-3xl"></div>
            <div class="relative grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
                <div class="flex items-start gap-3 sm:gap-4">
                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-teal-200 ring-1 ring-white/10 sm:h-13 sm:w-13">
                        <i class="ph ph-chat-teardrop-text text-2xl"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-[11px] font-extrabold uppercase tracking-[.18em] text-teal-200 sm:text-xs">Cerita Customer</p>
                        <h3 class="mt-1 text-lg font-extrabold leading-tight sm:text-2xl">Punya pengalaman belanja?</h3>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-white/70">
                            Bagikan pengalaman singkatmu saat memilih produk atau checkout. Cerita kamu bisa membantu customer lain lebih yakin sebelum membeli.
                        </p>
                        <div class="mt-3 inline-flex max-w-full items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5 text-[11px] font-semibold leading-5 text-white/70 sm:text-xs">
                            <i class="ph ph-shield-check shrink-0 text-teal-200"></i>
                            <span>Dicek admin dulu sebelum tampil.</span>
                        </div>
                    </div>
                </div>
                <button data-testimonial-open type="button" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-white px-5 text-sm font-extrabold text-slate-950 shadow-lg shadow-black/10 transition hover:-translate-y-0.5 hover:shadow-xl active:translate-y-0 md:min-w-[12.5rem]">
                    <i class="ph ph-pencil-simple-line text-xl"></i>
                    Tulis Pengalaman
                </button>
            </div>
        </div>
    </div>

    <div data-testimonial-modal data-open-on-load="{{ $errors->any() ? 'true' : 'false' }}" class="testimonial-modal {{ $errors->any() ? 'is-open' : '' }}" role="dialog" aria-modal="true" aria-labelledby="testimonial-modal-title">
        <div class="testimonial-modal-backdrop"></div>
        <button data-testimonial-close type="button" class="testimonial-modal-close-area" aria-label="Tutup modal testimoni"></button>

        <div class="testimonial-modal-panel">
            <div class="testimonial-modal-header">
                <span class="testimonial-modal-grip"></span>
                <div class="relative flex items-start justify-between gap-3">
                    <div class="flex min-w-0 items-start gap-2.5">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-white/10 text-teal-200 ring-1 ring-white/10 sm:h-9 sm:w-9 sm:rounded-2xl">
                            <i class="ph ph-chat-circle-dots text-lg sm:text-xl"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[9px] font-extrabold uppercase tracking-[.18em] text-teal-200 sm:text-[10px]">Testimoni Customer</p>
                            <h3 id="testimonial-modal-title" class="mt-0.5 text-[15px] font-extrabold leading-tight sm:text-[17px]">Bagikan pengalaman belanja</h3>
                            <p class="mt-0.5 max-w-md text-[10.5px] leading-4 text-white/65 sm:text-[11.5px] sm:leading-5">Ceritakan singkat pengalamanmu. Admin akan mengecek sebelum ditampilkan.</p>
                        </div>
                    </div>

                    <button data-testimonial-close type="button" class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-white/10 text-white/80 ring-1 ring-white/10 transition hover:bg-white hover:text-slate-950" aria-label="Tutup form testimoni">
                        <i class="ph ph-x text-base sm:text-lg"></i>
                    </button>
                </div>
            </div>

            <form action="{{ route('testimonials.store') }}" method="post" class="testimonial-modal-body" novalidate data-testimonial-form>
                @csrf

                <div data-testimonial-client-error class="testimonial-client-error hidden mb-3 items-start gap-2 rounded-2xl border border-rose-100 bg-rose-50 p-3 text-xs font-semibold leading-5 text-rose-700 dark:border-rose-400/20 dark:bg-rose-500/10 dark:text-rose-200">
                    <i class="ph-fill ph-warning-circle mt-0.5 text-base"></i>
                    <span>Nama dan cerita pengalaman wajib diisi dulu ya.</span>
                </div>

                @if($errors->any())
                    <div class="mb-3 rounded-2xl border border-rose-100 bg-rose-50 p-3 text-xs font-semibold leading-5 text-rose-700 dark:border-rose-400/20 dark:bg-rose-500/10 dark:text-rose-200">
                        <div class="flex gap-2">
                            <i class="ph-fill ph-warning-circle mt-0.5 text-base"></i>
                            <div>
                                <p>Mohon cek kembali data testimoni.</p>
                                <ul class="mt-1 list-inside list-disc text-[11px] font-medium">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="testimonial-form-stack">
                    <label class="block">
                        <span class="testimonial-form-label">Nama kamu</span>
                        <div class="relative">
                            <i class="ph ph-user absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input data-testimonial-field="name" name="name" value="{{ old('name') }}" maxlength="80" placeholder="Contoh: Rina" class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-3 text-[13px] font-semibold text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:bg-white focus:ring-4 focus:ring-teal-500/10 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-teal-300 sm:h-[2.65rem] sm:rounded-2xl sm:pl-10 sm:text-sm">
                        </div>
                    </label>

                    <div>
                        <span class="testimonial-form-label">Rating pengalaman</span>
                        <div class="testimonial-rating-grid grid grid-cols-5 gap-1.5 sm:gap-2">
                            @foreach([5 => 'Sangat puas', 4 => 'Puas', 3 => 'Cukup', 2 => 'Kurang', 1 => 'Tidak puas'] as $value => $label)
                                <label class="testimonial-rating-card group cursor-pointer" title="{{ $label }}">
                                    <input type="radio" name="rating" value="{{ $value }}" class="peer sr-only" @checked((string) old('rating', '5') === (string) $value)>
                                    <span class="flex h-10 items-center justify-center gap-1 rounded-xl border border-slate-200 bg-slate-50 px-1 text-center text-xs font-extrabold text-slate-500 transition peer-checked:border-amber-300 peer-checked:bg-amber-50 peer-checked:text-amber-700 peer-checked:ring-4 peer-checked:ring-amber-400/10 hover:border-amber-200 hover:bg-amber-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:peer-checked:border-amber-300/50 dark:peer-checked:bg-amber-400/10 dark:peer-checked:text-amber-200 sm:h-11 sm:rounded-2xl sm:text-sm">
                                        <i class="ph-fill ph-star rating-stars text-amber-500"></i>
                                        <span>{{ $value }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-1.5 text-[11px] leading-4 text-slate-500 dark:text-slate-400">Pilih rating yang paling sesuai dengan pengalaman belanja kamu.</p>
                    </div>

                    <label class="block">
                        <span class="testimonial-form-label">Ceritakan pengalamanmu</span>
                        <textarea data-testimonial-field="message" name="message" rows="3" maxlength="500" placeholder="Contoh: Produknya mudah dipilih, checkout jelas, dan adminnya responsif..." class="h-[5.1rem] w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-[13px] font-medium leading-5 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:bg-white focus:ring-4 focus:ring-teal-500/10 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-teal-300 sm:h-[5.45rem] sm:rounded-2xl sm:text-sm sm:leading-6">{{ old('message') }}</textarea>
                    </label>
                </div>

                <div class="mt-3 rounded-xl border border-teal-100 bg-teal-50/70 px-3 py-2 dark:border-teal-400/15 dark:bg-teal-400/10 sm:rounded-2xl">
                    <div class="flex items-start gap-2 text-[11px] leading-4 text-slate-600 dark:text-slate-300 sm:text-xs sm:leading-5">
                        <i class="ph ph-shield-check mt-0.5 text-sm text-teal-600 dark:text-teal-300 sm:text-base"></i>
                        <p>Aman, testimoni masuk ke admin dulu sebelum ditampilkan.</p>
                    </div>
                </div>

                <div class="mt-3.5 grid grid-cols-2 gap-2.5 sm:flex sm:items-center sm:justify-end sm:gap-3">
                    <button data-testimonial-close type="button" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-extrabold text-slate-600 transition hover:bg-slate-100 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10 sm:h-11 sm:rounded-2xl sm:text-sm">
                        Batal
                    </button>
                    <button class="inline-flex h-10 items-center justify-center gap-1.5 rounded-xl bg-teal-600 px-4 text-xs font-extrabold text-white shadow-lg shadow-teal-600/25 transition hover:-translate-y-0.5 hover:bg-teal-700 active:translate-y-0 dark:bg-teal-500 dark:text-slate-950 dark:hover:bg-teal-400 sm:h-11 sm:rounded-2xl sm:px-6 sm:text-sm">
                        <i class="ph ph-paper-plane-tilt text-base sm:text-xl"></i>
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>

</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-testimonial-slider]').forEach((slider) => {
            const track = slider.querySelector('[data-testimonial-track]');
            const slides = Array.from(slider.querySelectorAll('[data-testimonial-slide]'));
            const dots = Array.from(slider.querySelectorAll('[data-testimonial-dot]'));
            const prev = slider.querySelector('[data-testimonial-prev]');
            const next = slider.querySelector('[data-testimonial-next]');
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            let activeIndex = 0;
            let autoplayTimer = null;
            let scrollTimer = null;
            let isPointerDown = false;
            let pointerStartX = 0;
            let scrollStartLeft = 0;
            let hasDragged = false;

            if (!track || slides.length <= 1) return;

            const setActiveDot = (index) => {
                activeIndex = Math.max(0, Math.min(index, slides.length - 1));
                dots.forEach((dot, dotIndex) => dot.classList.toggle('is-active', dotIndex === activeIndex));
            };

            const getSlideLeft = (index) => slides[index]?.offsetLeft ?? 0;

            const goToSlide = (index, smooth = true) => {
                const targetIndex = (index + slides.length) % slides.length;
                track.scrollTo({
                    left: getSlideLeft(targetIndex),
                    behavior: smooth && !reduceMotion ? 'smooth' : 'auto',
                });
                setActiveDot(targetIndex);
            };

            const syncActiveFromScroll = () => {
                const closest = slides.reduce((best, slide, index) => {
                    const distance = Math.abs(slide.offsetLeft - track.scrollLeft);
                    return distance < best.distance ? { index, distance } : best;
                }, { index: activeIndex, distance: Number.POSITIVE_INFINITY });
                setActiveDot(closest.index);
            };

            const startAutoplay = () => {
                if (reduceMotion || slides.length <= 1 || autoplayTimer) return;
                autoplayTimer = window.setInterval(() => goToSlide(activeIndex + 1), 4600);
            };

            const stopAutoplay = () => {
                if (!autoplayTimer) return;
                window.clearInterval(autoplayTimer);
                autoplayTimer = null;
            };

            const finishDrag = () => {
                if (!isPointerDown) return;
                isPointerDown = false;
                track.classList.remove('is-dragging');
                syncActiveFromScroll();
                window.setTimeout(() => { hasDragged = false; }, 80);
                startAutoplay();
            };

            prev?.addEventListener('click', () => goToSlide(activeIndex - 1));
            next?.addEventListener('click', () => goToSlide(activeIndex + 1));
            dots.forEach((dot) => dot.addEventListener('click', () => goToSlide(Number(dot.dataset.index || 0))));

            track.addEventListener('scroll', () => {
                window.clearTimeout(scrollTimer);
                scrollTimer = window.setTimeout(syncActiveFromScroll, 90);
            }, { passive: true });

            track.addEventListener('pointerdown', (event) => {
                if (event.pointerType === 'mouse' && event.button !== 0) return;
                isPointerDown = true;
                hasDragged = false;
                pointerStartX = event.clientX;
                scrollStartLeft = track.scrollLeft;
                track.classList.add('is-dragging');
                stopAutoplay();
            });

            track.addEventListener('pointermove', (event) => {
                if (!isPointerDown) return;
                const deltaX = event.clientX - pointerStartX;
                if (Math.abs(deltaX) > 4) hasDragged = true;
                track.scrollLeft = scrollStartLeft - deltaX;
            });

            track.addEventListener('pointerup', finishDrag);
            track.addEventListener('pointercancel', finishDrag);
            track.addEventListener('pointerleave', () => {
                if (isPointerDown) finishDrag();
            });
            track.addEventListener('click', (event) => {
                if (!hasDragged) return;
                event.preventDefault();
                event.stopPropagation();
            }, true);

            slider.addEventListener('mouseenter', stopAutoplay);
            slider.addEventListener('mouseleave', startAutoplay);
            slider.addEventListener('focusin', stopAutoplay);
            slider.addEventListener('focusout', startAutoplay);
            slider.addEventListener('touchstart', stopAutoplay, { passive: true });
            slider.addEventListener('touchend', startAutoplay, { passive: true });

            setActiveDot(0);
            startAutoplay();
        });

        const testimonialModal = document.querySelector('[data-testimonial-modal]');
        const testimonialOpenButtons = document.querySelectorAll('[data-testimonial-open]');

        if (testimonialModal) {
            if (testimonialModal.parentElement !== document.body) {
                document.body.appendChild(testimonialModal);
            }

            const testimonialCloseButtons = testimonialModal.querySelectorAll('[data-testimonial-close]');
            const testimonialForm = testimonialModal.querySelector('[data-testimonial-form]');
            const firstInput = testimonialModal.querySelector('[data-testimonial-field="name"]');
            const messageInput = testimonialModal.querySelector('[data-testimonial-field="message"]');
            const modalBody = testimonialModal.querySelector('.testimonial-modal-body');
            const clientError = testimonialModal.querySelector('[data-testimonial-client-error]');

            const showClientError = (show) => {
                clientError?.classList.toggle('hidden', !show);
                clientError?.classList.toggle('is-visible', show);
            };

            const openTestimonialModal = () => {
                testimonialModal.classList.add('is-open');
                testimonialModal.scrollTop = 0;
                if (modalBody) modalBody.scrollTop = 0;
                document.body.classList.add('overflow-hidden');
                document.body.style.overflow = 'hidden';
            };

            const closeTestimonialModal = () => {
                testimonialModal.classList.remove('is-open');
                document.body.classList.remove('overflow-hidden');
                document.body.style.overflow = '';
                showClientError(false);
            };

            testimonialForm?.addEventListener('submit', (event) => {
                const nameIsEmpty = !firstInput?.value.trim();
                const messageIsEmpty = !messageInput?.value.trim();

                if (nameIsEmpty || messageIsEmpty) {
                    event.preventDefault();
                    showClientError(true);
                    (nameIsEmpty ? firstInput : messageInput)?.focus({ preventScroll: true });
                    if (modalBody) modalBody.scrollTop = 0;
                }
            });

            testimonialOpenButtons.forEach((button) => button.addEventListener('click', openTestimonialModal));
            testimonialCloseButtons.forEach((button) => button.addEventListener('click', closeTestimonialModal));

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && testimonialModal.classList.contains('is-open')) {
                    closeTestimonialModal();
                }
            });

            if (testimonialModal.dataset.openOnLoad === 'true') {
                openTestimonialModal();
            }
        }
    });
</script>

<section id="voucher" class="mx-auto max-w-7xl px-4 pt-2 pb-7 sm:px-6 sm:pt-3 sm:pb-10 lg:px-8">
    <div class="relative overflow-hidden rounded-[1.75rem] bg-slate-950 p-5 text-white shadow-soft sm:rounded-[2rem] sm:p-8">
        <div class="pointer-events-none absolute -right-12 -top-12 h-44 w-44 rounded-full bg-emerald-400/20 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-14 -left-14 h-48 w-48 rounded-full bg-teal-400/20 blur-2xl"></div>

        <div class="relative mb-5 flex flex-col gap-3 sm:mb-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-extrabold uppercase tracking-wide text-emerald-300">Promo Spesial</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight sm:text-3xl">Gunakan kode voucher aktif</h2>
            </div>
            <p class="max-w-md text-sm leading-7 text-white/65">Pilih voucher yang tersedia dan gunakan saat konfirmasi pesanan untuk mendapatkan penawaran terbaik.</p>
        </div>

        <div class="relative grid gap-3 sm:grid-cols-2 sm:gap-4">
            @forelse($vouchers as $voucher)
                <div class="group rounded-2xl border border-white/10 bg-white/10 p-4 transition hover:-translate-y-1 hover:bg-white/15 sm:rounded-3xl sm:p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="truncate text-xl font-extrabold tracking-wide sm:text-2xl">{{ $voucher->code }}</h3>
                            <p class="mt-1 line-clamp-2 text-xs leading-5 text-white/65 sm:text-sm">{{ $voucher->label }}</p>
                        </div>
                        <button type="button" data-copy="{{ $voucher->code }}" class="inline-flex shrink-0 items-center gap-1.5 rounded-2xl bg-white px-3.5 py-2.5 text-xs font-extrabold text-slate-950 transition group-hover:scale-105 sm:px-4 sm:py-3 sm:text-sm">
                            <i class="ph ph-copy"></i> Salin
                        </button>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-white/10 bg-white/10 p-5 text-sm leading-7 text-white/70 sm:rounded-3xl">
                    Belum ada voucher aktif saat ini. Silakan cek kembali promo terbaru secara berkala.
                </div>
            @endforelse
        </div>
    </div>
</section>

<section id="mobile-info" class="mx-auto max-w-7xl px-4 pb-24 pt-3 md:hidden">
    <div class="grid gap-3">
        <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100 dark:bg-emerald-400/10 dark:text-emerald-300 dark:ring-emerald-400/20">
                <i class="ph-fill ph-whatsapp-logo text-2xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-sm font-extrabold leading-tight text-slate-950 dark:text-white">Pesan Lebih Cepat</h3>
                <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">Pilih produk, masuk keranjang, lalu konfirmasi pesanan tanpa proses yang membingungkan.</p>
            </div>
        </div>

        <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-blue-100 dark:bg-blue-400/10 dark:text-blue-300 dark:ring-blue-400/20">
                <i class="ph ph-squares-four text-2xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-sm font-extrabold leading-tight text-slate-950 dark:text-white">Katalog Rapi</h3>
                <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">Produk, kategori, dan promo tersusun jelas agar mudah dibandingkan.</p>
            </div>
        </div>

        <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-violet-50 text-violet-600 ring-1 ring-violet-100 dark:bg-violet-400/10 dark:text-violet-300 dark:ring-violet-400/20">
                <i class="ph ph-sparkle text-2xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-sm font-extrabold leading-tight text-slate-950 dark:text-white">Nyaman Dilihat</h3>
                <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">Tampilan ringan dan responsif, tetap rapi saat dibuka dari HP maupun desktop.</p>
            </div>
        </div>

        <div class="mt-1 grid gap-3 rounded-[1.5rem] border border-teal-100 bg-gradient-to-br from-teal-50 via-white to-emerald-50 p-3 shadow-sm dark:border-teal-400/15 dark:from-teal-500/10 dark:via-white/5 dark:to-emerald-500/10">
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-teal-700 text-white shadow-lg shadow-teal-700/20">
                    <i class="ph ph-shopping-cart-simple text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-950 dark:text-white">Checkout lebih praktis</h3>
                    <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">Tambahkan beberapa produk ke keranjang, cek ringkasannya, lalu lanjut konfirmasi pesanan via WhatsApp.</p>
                </div>
            </div>
            <button data-cart-toggle type="button" class="inline-flex h-11 items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:shadow-md dark:bg-white dark:text-slate-950">
                Buka Checkout <i class="ph ph-arrow-right"></i>
            </button>
        </div>
    </div>
</section>


@include('partials.mobile-bottom-menu', ['bottomActive' => 'home'])
@endsection
