@extends('layouts.app')
@section('title', config('store.name').' - Belanja Mudah via WhatsApp')
@section('content')
@once
    <style>
        html {
            scroll-behavior: smooth;
        }

        #home,
        #produk,
        #voucher,
        #keunggulan {
            scroll-margin-top: 5.5rem;
        }

        .home-product-card {
            min-width: 0;
            height: 100%;
        }

        .home-product-card > * {
            position: relative;
            display: flex !important;
            height: 100%;
            min-width: 0;
            overflow: hidden;
            flex-direction: column !important;
            border-radius: 1.35rem !important;
            border: 1px solid rgba(226, 232, 240, .96) !important;
            background: rgba(255, 255, 255, .98) !important;
            box-shadow: 0 12px 34px rgba(15, 23, 42, .065) !important;
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        }

        .dark .home-product-card > * {
            border-color: rgba(255, 255, 255, .1) !important;
            background: rgba(255, 255, 255, .055) !important;
        }

        .home-product-card > *:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 44px rgba(15, 23, 42, .12) !important;
            border-color: rgba(59, 130, 246, .32) !important;
        }

        .home-product-card img {
            display: block;
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            background: #f8fafc;
        }

        .home-product-card h2,
        .home-product-card h3 {
            display: -webkit-box !important;
            overflow: hidden !important;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            text-overflow: ellipsis;
            color: inherit;
        }

        .home-product-card p {
            display: -webkit-box !important;
            overflow: hidden !important;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            text-overflow: ellipsis;
        }

        .home-product-card :where(.line-clamp-2, .line-clamp-3) {
            -webkit-line-clamp: 2 !important;
        }

        .home-product-card :where([class*="p-4"], [class*="p-5"], [class*="p-6"]) {
            min-width: 0;
        }

        .home-product-card :where(a, button) {
            max-width: 100%;
        }

        .home-product-card :where(button, a[class*="bg-"], a[class*="border"], a[class*="ring-"]) {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: .45rem !important;
            min-width: 0;
            min-height: 2.55rem;
            border-radius: 1rem !important;
            font-weight: 800 !important;
            line-height: 1.1 !important;
            text-align: center;
        }

        .home-product-card :where(button, a[class*="bg-"], a[class*="border"], a[class*="ring-"]) i {
            flex-shrink: 0;
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
            background: #fff;
            color: rgb(15 23 42);
            box-shadow: 0 10px 24px rgba(255, 255, 255, .12);
        }

        .mobile-bottom-action {
            border: 1px solid rgba(37, 99, 235, .16);
            background: linear-gradient(135deg, rgba(37, 99, 235, .1), rgba(6, 182, 212, .12));
            color: rgb(37 99 235);
        }

        .dark .mobile-bottom-action {
            border-color: rgba(96, 165, 250, .22);
            background: rgba(59, 130, 246, .14);
            color: rgb(147 197 253);
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

            .home-product-card > * {
                border-radius: 1.05rem !important;
                box-shadow: 0 10px 24px rgba(15, 23, 42, .075) !important;
            }

            .home-product-card img {
                aspect-ratio: 1 / .82;
                max-height: 7.2rem;
            }

            .home-product-card :where([class*="p-4"], [class*="p-5"], [class*="p-6"]) {
                padding: .68rem !important;
            }

            .home-product-card :where([class*="gap-4"], [class*="gap-5"], [class*="gap-6"]) {
                gap: .55rem !important;
            }

            .home-product-card h2,
            .home-product-card h3 {
                min-height: 2.18rem;
                font-size: .82rem !important;
                line-height: 1.09rem !important;
                letter-spacing: -.01em;
            }

            .home-product-card p {
                min-height: 1rem;
                font-size: .7rem !important;
                line-height: 1rem !important;
                -webkit-line-clamp: 1;
            }

            .home-product-card :where([class*="text-2xl"], [class*="text-xl"], [class*="text-lg"]) {
                font-size: .95rem !important;
                line-height: 1.25rem !important;
            }

            .home-product-card :where([class*="text-sm"]) {
                font-size: .72rem !important;
                line-height: 1rem !important;
            }

            .home-product-card :where(button, a[class*="bg-"], a[class*="border"], a[class*="ring-"]) {
                min-height: 2.12rem;
                padding: .48rem .55rem !important;
                border-radius: .78rem !important;
                font-size: .68rem !important;
                line-height: .9rem !important;
                white-space: nowrap;
            }

            .home-product-card :where(a[aria-label*="Detail"], a[title*="Detail"], a[aria-label*="detail"], a[title*="detail"], button[aria-label*="Detail"], button[title*="Detail"], button[aria-label*="detail"], button[title*="detail"]) {
                width: 2.12rem !important;
                min-width: 2.12rem !important;
                max-width: 2.12rem !important;
                padding-inline: 0 !important;
            }

            .home-product-card :where(svg, i) {
                width: .92rem;
                height: .92rem;
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
    <div class="grid gap-4 lg:grid-cols-[1.25fr_.75fr] lg:gap-5">
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
                    <article data-banner-slide class="{{ $loop->first ? '' : 'hidden' }} relative min-h-[300px] overflow-hidden {{ $bannerImageUrl ? 'bg-slate-950' : 'bg-gradient-to-br '.($banner->accent ?: 'from-slate-950 to-blue-950') }} p-6 text-white sm:min-h-[380px] sm:p-10">
                        @if($bannerImageUrl)
                            <picture class="absolute inset-0 h-full w-full">
                                @if($bannerMobileImageUrl)
                                    <source media="(max-width: 639px)" srcset="{{ $bannerMobileImageUrl }}">
                                @endif
                                <img src="{{ $bannerImageUrl }}" alt="{{ $banner->title }}" class="h-full w-full object-cover">
                            </picture>
                            <div class="absolute inset-0 bg-gradient-to-br from-slate-950/82 via-slate-950/55 to-slate-950/20"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/55 via-transparent to-transparent"></div>
                        @endif

                        <div class="relative z-10 flex h-full min-h-[252px] flex-col justify-between gap-8 sm:min-h-[300px]">
                            <div>
                                @if($banner->label)
                                    <span class="inline-flex rounded-full bg-white/15 px-3.5 py-2 text-[11px] font-bold uppercase tracking-wide text-white/90 ring-1 ring-white/15 backdrop-blur sm:px-4 sm:text-xs">{{ $banner->label }}</span>
                                @endif

                                <h1 class="mt-5 max-w-2xl text-3xl font-extrabold leading-tight tracking-tight drop-shadow-sm sm:mt-6 sm:text-5xl">{{ $banner->title }}</h1>

                                @if($banner->subtitle)
                                    <p class="mt-4 max-w-xl text-sm leading-7 text-white/78 drop-shadow-sm sm:text-base">{{ $banner->subtitle }}</p>
                                @endif
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                <a href="{{ $banner->button_url ?: '#produk' }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3.5 text-sm font-extrabold text-slate-950 shadow-lg shadow-black/10 transition hover:-translate-y-0.5 hover:shadow-xl sm:px-6 sm:py-4">
                                    {{ $banner->button_text ?: 'Lihat Katalog' }} <i class="ph ph-arrow-right"></i>
                                </a>
                                <button data-cart-toggle type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-5 py-3.5 text-sm font-bold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/15 sm:px-6 sm:py-4">
                                    Lihat Keranjang <i class="ph ph-shopping-cart-simple"></i>
                                </button>
                            </div>
                        </div>

                        @unless($bannerImageUrl)
                            <div class="pointer-events-none absolute -right-16 -top-16 h-52 w-52 rounded-full bg-white/10 blur-2xl sm:h-72 sm:w-72"></div>
                            <i class="ph {{ $banner->icon ?: 'ph-image-square' }} pointer-events-none absolute bottom-6 right-6 text-7xl text-white/15 sm:bottom-8 sm:right-8 sm:text-9xl"></i>
                        @endunless
                    </article>
                @empty
                    <article class="relative min-h-[300px] overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 p-6 text-white sm:min-h-[380px] sm:p-10">
                        <div class="relative z-10 max-w-2xl">
                            <span class="inline-flex rounded-full bg-white/15 px-3.5 py-2 text-[11px] font-bold uppercase tracking-wide text-white/90 backdrop-blur">Belanja Praktis</span>
                            <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight sm:text-5xl">Temukan produk pilihan dan pesan lebih mudah.</h1>
                            <p class="mt-4 max-w-xl text-sm leading-7 text-white/75 sm:text-base">Pilih produk favorit, masukkan ke keranjang, lalu lanjutkan konfirmasi pesanan melalui WhatsApp.</p>
                            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                                <a href="#produk" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3.5 text-sm font-extrabold text-slate-950 shadow-lg shadow-black/10 transition hover:-translate-y-0.5 sm:px-6 sm:py-4">
                                    Mulai Belanja <i class="ph ph-arrow-right"></i>
                                </a>
                                <button data-cart-toggle type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-5 py-3.5 text-sm font-bold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/15 sm:px-6 sm:py-4">
                                    Cek Keranjang <i class="ph ph-shopping-cart-simple"></i>
                                </button>
                            </div>
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

        <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1 lg:gap-5">
            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-white/10 dark:bg-white/5 sm:block sm:rounded-[2rem] sm:p-6">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100 dark:bg-emerald-400/10 dark:text-emerald-300 dark:ring-emerald-400/20">
                    <i class="ph-fill ph-whatsapp-logo text-2xl"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm font-extrabold leading-tight text-slate-950 dark:text-white sm:mt-4 sm:text-base">Pesan Lebih Cepat</h3>
                    <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400 sm:mt-2 sm:text-sm sm:leading-6">Pilih produk, masuk keranjang, lalu konfirmasi pesanan tanpa proses yang membingungkan.</p>
                </div>
            </div>

            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-white/10 dark:bg-white/5 sm:block sm:rounded-[2rem] sm:p-6">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-blue-100 dark:bg-blue-400/10 dark:text-blue-300 dark:ring-blue-400/20">
                    <i class="ph ph-squares-four text-2xl"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm font-extrabold leading-tight text-slate-950 dark:text-white sm:mt-4 sm:text-base">Katalog Rapi</h3>
                    <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400 sm:mt-2 sm:text-sm sm:leading-6">Produk, kategori, promo, dan pilihan terbaru tersusun jelas agar mudah dibandingkan.</p>
                </div>
            </div>

            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-white/10 dark:bg-white/5 sm:block sm:rounded-[2rem] sm:p-6">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-violet-50 text-violet-600 ring-1 ring-violet-100 dark:bg-violet-400/10 dark:text-violet-300 dark:ring-violet-400/20">
                    <i class="ph ph-sparkle text-2xl"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm font-extrabold leading-tight text-slate-950 dark:text-white sm:mt-4 sm:text-base">Nyaman Dilihat</h3>
                    <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400 sm:mt-2 sm:text-sm sm:leading-6">Tampilan ringan dan responsif, tetap rapi saat dibuka dari HP maupun desktop.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="produk" class="mx-auto max-w-7xl px-4 py-7 sm:px-6 sm:py-10 lg:px-8">
    <div class="mb-6 flex flex-col gap-4 md:mb-8 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-wide text-blue-600 dark:text-blue-400">Katalog Produk</p>
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-4xl">Pilih produk yang paling sesuai</h2>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-500 dark:text-slate-400 sm:text-base">Cari produk favorit, cek pilihan yang tersedia, lalu lanjutkan pesanan dengan alur yang simpel.</p>
        </div>
        <button data-cart-toggle type="button" class="hidden items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:bg-white dark:text-slate-950 md:inline-flex">
            Cek Keranjang <i class="ph ph-shopping-cart-simple"></i>
        </button>
    </div>

    <form method="get" action="{{ route('home') }}#produk" class="mb-6 rounded-[1.5rem] border border-slate-200 bg-white p-3 shadow-sm dark:border-white/10 dark:bg-white/5 sm:mb-8 sm:rounded-[2rem] sm:p-4">
        <div class="grid gap-3 md:grid-cols-[1fr_.65fr_.55fr_auto]">
            <label class="relative block">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input name="q" value="{{ $filters['query'] }}" placeholder="Cari produk..." class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-medium outline-none transition placeholder:text-slate-400 focus:border-slate-950 focus:bg-white dark:border-white/10 dark:bg-white/10 dark:focus:border-white/40">
            </label>

            <select name="category" class="h-12 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-medium outline-none transition focus:border-slate-950 focus:bg-white dark:border-white/10 dark:bg-white/10 dark:focus:border-white/40">
                <option value="all" @selected($filters['category'] === 'all')>Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" @selected($filters['category'] === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>

            <select name="sort" class="h-12 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-medium outline-none transition focus:border-slate-950 focus:bg-white dark:border-white/10 dark:bg-white/10 dark:focus:border-white/40">
                <option value="popular" @selected($filters['sort'] === 'popular')>Paling Populer</option>
                <option value="highest" @selected($filters['sort'] === 'highest')>Harga Tertinggi</option>
                <option value="lowest" @selected($filters['sort'] === 'lowest')>Harga Terendah</option>
                <option value="discount" @selected($filters['sort'] === 'discount')>Promo Terbaik</option>
            </select>

            <button class="h-12 rounded-2xl bg-slate-950 px-6 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:bg-white dark:text-slate-950">Terapkan</button>
        </div>
    </form>

    <div class="mb-6 grid gap-3 rounded-[1.5rem] border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-cyan-50 p-3 shadow-sm dark:border-blue-400/15 dark:from-blue-500/10 dark:via-white/5 dark:to-cyan-500/10 sm:mb-8 sm:grid-cols-[1fr_auto] sm:items-center sm:p-4">
        <div class="flex items-start gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/20">
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
</section>

<section id="voucher" class="mx-auto max-w-7xl px-4 py-7 sm:px-6 sm:py-10 lg:px-8">
    <div class="relative overflow-hidden rounded-[1.75rem] bg-slate-950 p-5 text-white shadow-soft sm:rounded-[2rem] sm:p-8">
        <div class="pointer-events-none absolute -right-12 -top-12 h-44 w-44 rounded-full bg-emerald-400/20 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-14 -left-14 h-48 w-48 rounded-full bg-blue-400/20 blur-2xl"></div>

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

@if($latestProducts->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 py-7 sm:px-6 sm:py-10 lg:px-8">
    <div class="mb-6 flex items-end justify-between gap-4 sm:mb-8">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-wide text-blue-600 dark:text-blue-400">Pilihan Terbaru</p>
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-3xl">Produk baru yang bisa kamu cek</h2>
        </div>
        <a href="#produk" class="hidden rounded-2xl border border-slate-200 px-5 py-3 text-sm font-extrabold transition hover:bg-white dark:border-white/10 dark:hover:bg-white/10 sm:inline-flex">Lihat Semua</a>
    </div>

    <div class="home-product-grid grid grid-cols-2 gap-3 sm:gap-5 md:grid-cols-3">
        @foreach($latestProducts as $product)
            <div class="home-product-card min-w-0">
                @include('products.card', ['product' => $product])
            </div>
        @endforeach
    </div>
</section>
@endif

<section id="keunggulan" class="mx-auto max-w-7xl px-4 py-7 pb-24 sm:px-6 sm:py-10 md:pb-10 lg:px-8">
    <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft dark:border-white/10 dark:bg-white/5 sm:rounded-[2rem]">
        <div class="grid gap-0 lg:grid-cols-[.95fr_1.05fr]">
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 p-6 text-white sm:p-8 lg:p-10">
                <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-blue-400/20 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-emerald-400/20 blur-3xl"></div>
                <div class="relative">
                    <span class="inline-flex rounded-full bg-white/12 px-3.5 py-2 text-[11px] font-extrabold uppercase tracking-wide text-white/85 ring-1 ring-white/15">Fitur Unggulan</span>
                    <h2 class="mt-5 text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">Belanja lebih mudah dari lihat produk sampai konfirmasi.</h2>
                    <p class="mt-4 text-sm leading-7 text-white/68 sm:text-base">Pengalaman belanja dibuat ringkas, rapi, dan mudah dipahami agar pelanggan cepat menemukan produk yang cocok.</p>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a href="#produk" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-extrabold text-slate-950 transition hover:-translate-y-0.5">
                            Lihat Produk <i class="ph ph-arrow-right"></i>
                        </a>
                        <button data-cart-toggle type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-white/15">
                            Buka Keranjang <i class="ph ph-shopping-cart-simple"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid gap-3 p-4 sm:grid-cols-2 sm:p-6 lg:p-8">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-white/5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-blue-600 shadow-sm dark:bg-white/10 dark:text-blue-300">
                        <i class="ph ph-funnel text-2xl"></i>
                    </div>
                    <h3 class="mt-4 font-extrabold text-slate-950 dark:text-white">Filter Produk</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">Cari berdasarkan nama, kategori, promo, atau urutan harga agar pilihan lebih cepat ditemukan.</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-white/5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-emerald-600 shadow-sm dark:bg-white/10 dark:text-emerald-300">
                        <i class="ph ph-ticket text-2xl"></i>
                    </div>
                    <h3 class="mt-4 font-extrabold text-slate-950 dark:text-white">Voucher Aktif</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">Kode promo tampil jelas dan mudah disalin sebelum pelanggan lanjut ke proses konfirmasi.</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-white/5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-violet-600 shadow-sm dark:bg-white/10 dark:text-violet-300">
                        <i class="ph ph-basket text-2xl"></i>
                    </div>
                    <h3 class="mt-4 font-extrabold text-slate-950 dark:text-white">Keranjang Ringkas</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">Produk yang dipilih bisa dicek ulang dengan tampilan yang lebih rapi sebelum checkout.</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-white/5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-amber-600 shadow-sm dark:bg-white/10 dark:text-amber-300">
                        <i class="ph ph-device-mobile text-2xl"></i>
                    </div>
                    <h3 class="mt-4 font-extrabold text-slate-950 dark:text-white">Nyaman di Mobile</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">Card produk, tombol, dan menu bawah dibuat lebih proporsional untuk penggunaan dari HP.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<nav class="mobile-bottom-menu fixed inset-x-0 bottom-0 z-50 px-3 md:hidden">
    <div class="mx-auto grid max-w-md grid-cols-4 gap-1 rounded-[1.45rem] border border-slate-200/80 bg-white/95 p-2 shadow-[0_-12px_35px_rgba(15,23,42,.12)] backdrop-blur-xl dark:border-white/10 dark:bg-slate-950/92">
        <a href="#home" data-bottom-target="home" class="mobile-bottom-link is-active">
            <i class="ph ph-house text-lg"></i>
            Home
        </a>
        <a href="#produk" data-bottom-target="produk" class="mobile-bottom-link">
            <i class="ph ph-squares-four text-lg"></i>
            Produk
        </a>
        <a href="#voucher" data-bottom-target="voucher" class="mobile-bottom-link">
            <i class="ph ph-ticket text-lg"></i>
            Promo
        </a>
        <button data-cart-toggle type="button" class="mobile-bottom-action">
            <i class="ph ph-shopping-cart-simple text-lg"></i>
            Checkout
        </button>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const menuLinks = Array.from(document.querySelectorAll('[data-bottom-target]'));
        const sections = menuLinks
            .map((link) => document.getElementById(link.dataset.bottomTarget))
            .filter(Boolean);

        const setActiveMenu = (id) => {
            menuLinks.forEach((link) => {
                link.classList.toggle('is-active', link.dataset.bottomTarget === id);
            });
        };

        const updateActiveMenu = () => {
            if (!sections.length) return;

            const offset = window.innerHeight * 0.38;
            let current = sections[0].id;

            sections.forEach((section) => {
                const rect = section.getBoundingClientRect();
                if (rect.top <= offset) {
                    current = section.id;
                }
            });

            setActiveMenu(current);
        };

        menuLinks.forEach((link) => {
            link.addEventListener('click', () => {
                setActiveMenu(link.dataset.bottomTarget);
            });
        });

        updateActiveMenu();
        window.addEventListener('scroll', updateActiveMenu, { passive: true });
        window.addEventListener('hashchange', updateActiveMenu);
    });
</script>
@endsection
