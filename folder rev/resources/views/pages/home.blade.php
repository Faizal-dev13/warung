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

        .home-banner-title {
            text-wrap: balance;
        }

        .home-banner-subtitle {
            text-wrap: pretty;
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
            .home-banner-slide {
                aspect-ratio: 4 / 3;
                min-height: 20.5rem;
                padding: 1.45rem 1.2rem 2.15rem !important;
            }

            .home-banner-content {
                justify-content: center;
                gap: 1rem !important;
            }

            .home-banner-copy {
                max-width: 18.5rem;
                margin-left: auto;
                margin-right: auto;
                padding-left: .15rem;
                padding-right: .15rem;
            }

            .home-banner-label {
                max-width: 100%;
                justify-content: center;
                padding: .55rem .85rem !important;
                font-size: .66rem !important;
                line-height: 1.05rem !important;
            }

            .home-banner-title {
                margin-top: .95rem !important;
                font-size: clamp(1.55rem, 7vw, 2.05rem) !important;
                line-height: 1.14 !important;
                letter-spacing: -.035em;
            }

            .home-banner-subtitle {
                display: -webkit-box;
                max-width: 17.5rem;
                margin-top: .78rem !important;
                overflow: hidden;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 3;
                font-size: .82rem !important;
                line-height: 1.5rem !important;
            }

            [data-banner-slider] [data-banner-dot] {
                width: .55rem;
                height: .55rem;
            }

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
                    <article data-banner-slide class="{{ $loop->first ? '' : 'hidden' }} home-banner-slide relative aspect-[4/3] min-h-0 overflow-hidden {{ $bannerImageUrl ? 'bg-slate-950' : 'bg-gradient-to-br '.($banner->accent ?: 'from-slate-950 to-blue-950') }} px-5 py-7 text-white sm:aspect-[8/3] sm:p-10">
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

                        <div class="home-banner-content relative z-10 flex h-full flex-col items-center justify-center gap-5 text-center sm:items-start sm:gap-8 sm:text-left">
                            <div class="home-banner-copy w-full">
                                @if($banner->label)
                                    <span class="home-banner-label inline-flex rounded-full bg-white/15 px-3.5 py-2 text-[11px] font-bold uppercase tracking-wide text-white/90 ring-1 ring-white/15 backdrop-blur sm:px-4 sm:text-xs">{{ $banner->label }}</span>
                                @endif

                                <h1 class="home-banner-title mx-auto mt-5 max-w-2xl text-3xl font-extrabold leading-tight tracking-tight drop-shadow-sm sm:mx-0 sm:mt-6 sm:text-5xl">{{ $banner->title }}</h1>

                                @if($banner->subtitle)
                                    <p class="home-banner-subtitle mx-auto mt-4 max-w-xl text-sm leading-7 text-white/78 drop-shadow-sm sm:mx-0 sm:text-base">{{ $banner->subtitle }}</p>
                                @endif
                            </div>

                        </div>

                        @unless($bannerImageUrl)
                            <div class="pointer-events-none absolute -right-16 -top-16 h-52 w-52 rounded-full bg-white/10 blur-2xl sm:h-72 sm:w-72"></div>
                            <i class="ph {{ $banner->icon ?: 'ph-image-square' }} pointer-events-none absolute bottom-6 right-6 text-7xl text-white/15 sm:bottom-8 sm:right-8 sm:text-9xl"></i>
                        @endunless
                    </article>
                @empty
                    <article class="home-banner-slide relative aspect-[4/3] min-h-0 overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 px-5 py-7 text-white sm:aspect-[8/3] sm:p-10">
                        <div class="home-banner-content relative z-10 flex h-full flex-col items-center justify-center text-center sm:items-start sm:text-left">
                            <div class="home-banner-copy mx-auto max-w-2xl sm:mx-0">
                                <span class="home-banner-label inline-flex rounded-full bg-white/15 px-3.5 py-2 text-[11px] font-bold uppercase tracking-wide text-white/90 backdrop-blur">Belanja Praktis</span>
                                <h1 class="home-banner-title mt-5 text-3xl font-extrabold leading-tight tracking-tight sm:text-5xl">Temukan produk pilihan dan pesan lebih mudah.</h1>
                                <p class="home-banner-subtitle mx-auto mt-4 max-w-xl text-sm leading-7 text-white/75 sm:mx-0 sm:text-base">Pilih produk favorit, masukkan ke keranjang, lalu lanjutkan konfirmasi pesanan melalui WhatsApp.</p>
                            </div>
                        </div>
                        <div class="pointer-events-none absolute -right-16 -top-16 h-52 w-52 rounded-full bg-white/10 blur-2xl sm:h-72 sm:w-72"></div>
                        <i class="ph ph-shopping-bag-open pointer-events-none absolute bottom-6 right-6 text-7xl text-white/15 sm:bottom-8 sm:right-8 sm:text-9xl"></i>
                    </article>
                @endforelse

                @if($banners->count() > 1)
                    <div class="absolute bottom-4 left-1/2 z-20 flex -translate-x-1/2 gap-2 sm:bottom-5 sm:left-7 sm:translate-x-0">
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
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-teal-700 dark:text-teal-300 sm:text-4xl">Katalog Produk</h2>
            <p class="text-sm font-extrabold uppercase tracking-wide text-slate-950 dark:text-white ">Pilih produk yang paling sesuai</p>
            
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


<section class="hidden md:block mx-auto max-w-7xl px-4 pt-1 pb-5 sm:px-6 lg:px-8">
    <div class="grid gap-4 rounded-[1.55rem] border border-amber-100 bg-gradient-to-br from-white via-amber-50/80 to-orange-50 p-4 shadow-sm dark:border-amber-400/15 dark:from-white/10 dark:via-amber-500/10 dark:to-orange-500/10 lg:grid-cols-[1fr_auto] lg:items-center lg:p-5">
        <div class="flex items-start gap-3.5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20">
                <i class="ph ph-chat-circle-dots text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-base font-extrabold tracking-tight text-slate-950 dark:text-white">Punya pengalaman belanja?</h3>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                    Ceritakan pengalamanmu secara singkat. Masukan dari kamu membantu customer lain lebih yakin sebelum memilih produk.
                </p>
            </div>
        </div>
        <a href="{{ route('testimonials.index') }}#form-testimoni" class="inline-flex h-11 items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 dark:bg-white dark:text-slate-950 lg:min-w-[11rem]">
            <i class="ph ph-pencil-simple-line text-lg"></i>
            Tulis Testimoni
        </a>
    </div>
</section>


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


        <div class="mt-1 grid gap-3 rounded-[1.35rem] border border-amber-100 bg-gradient-to-br from-white via-amber-50/80 to-orange-50 p-3.5 shadow-sm dark:border-amber-400/15 dark:from-white/10 dark:via-amber-500/10 dark:to-orange-500/10">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20">
                    <i class="ph ph-chat-circle-dots text-xl"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm font-extrabold leading-tight text-slate-950 dark:text-white">Punya pengalaman belanja?</h3>
                    <p class="mt-1 text-xs leading-5 text-slate-600 dark:text-slate-300">Bagikan cerita singkatmu supaya customer lain lebih yakin sebelum belanja.</p>
                </div>
            </div>
            <a href="{{ route('testimonials.index') }}#form-testimoni" class="inline-flex h-10 items-center justify-center gap-2 rounded-2xl bg-slate-950 px-4 text-xs font-extrabold text-white shadow-sm transition active:scale-[0.99] dark:bg-white dark:text-slate-950">
                <i class="ph ph-pencil-simple-line text-base"></i>
                Tulis Testimoni
            </a>
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
