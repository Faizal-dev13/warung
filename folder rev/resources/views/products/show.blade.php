@php
    use App\Support\Money;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $productImagePath = $product->image_path ?? null;
    $productImageUrl = null;
    $activeVariants = $product->relationLoaded('activeVariants') ? $product->activeVariants : collect();
    $hasVariants = $activeVariants->isNotEmpty();
    $availableVariants = $activeVariants->filter(fn ($variant) => is_null($variant->stock) || (int) $variant->stock > 0);
    $priceVariants = $availableVariants->isNotEmpty() ? $availableVariants : $activeVariants;
    $displayPrice = $hasVariants ? (int) $priceVariants->min('price') : (int) $product->price;
    $displayOldPrice = $hasVariants ? (int) $priceVariants->max('old_price') : (int) $product->old_price;
    $totalVariants = $activeVariants->count();
    $defaultVariantId = optional($availableVariants->first() ?? $activeVariants->first())->id;

    if ($productImagePath) {
        if (Str::startsWith($productImagePath, ['http://', 'https://', '/'])) {
            $productImageUrl = $productImagePath;
        } elseif (Str::startsWith($productImagePath, ['storage/'])) {
            $productImageUrl = asset($productImagePath);
        } else {
            $productImageUrl = Storage::url($productImagePath);
        }
    }

    $productFeatures = collect($product->features ?? [])
        ->filter(fn ($feature) => filled($feature))
        ->map(fn ($feature) => trim((string) $feature))
        ->values();
@endphp

@extends('layouts.app')
@section('title', $product->name.' - '.($settings['store_name'] ?? \App\Models\Setting::getValue('store_name', config('store.name'))))
@section('content')
<section class="mx-auto max-w-7xl px-4 py-5 pb-24 sm:px-6 sm:py-8 lg:px-8 lg:py-12">
    <div class="mb-5 flex items-center justify-between gap-3">
        <a href="{{ route('home') }}#produk" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-extrabold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-200 hover:text-teal-700 hover:shadow-md dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:border-teal-400/30 dark:hover:text-teal-300 sm:px-4 sm:py-3 sm:text-sm">
            <span class="grid h-8 w-8 place-items-center rounded-xl bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-white">
                <i class="ph ph-arrow-left"></i>
            </span>
            Katalog
        </a>

        <button data-cart-toggle type="button" class="relative inline-flex items-center gap-2 rounded-2xl bg-slate-950 px-4 py-3 text-xs font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:bg-white dark:text-slate-950 sm:text-sm">
            <span data-cart-count-badge class="absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full bg-rose-500 px-1 text-[10px] font-extrabold text-white {{ ($cart['count'] ?? 0) > 0 ? '' : 'hidden' }}">{{ $cart['count'] ?? 0 }}</span>
            <i class="ph ph-shopping-cart-simple"></i> Checkout
        </button>
    </div>

    <div class="grid gap-6 lg:grid-cols-[.9fr_1.1fr] lg:gap-9">
        <div class="lg:sticky lg:top-28 lg:self-start">
            <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft dark:border-white/10 dark:bg-white/5 sm:rounded-[2rem]">
                <div class="relative overflow-hidden bg-slate-100 dark:bg-white/10">
                    @if($productImageUrl)
                        <img src="{{ $productImageUrl }}" alt="{{ $product->name }}" class="aspect-[4/3] w-full object-cover sm:aspect-square lg:aspect-[4/4.2]">
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/25 via-transparent to-transparent"></div>
                    @else
                        <div class="relative flex min-h-[320px] flex-col justify-between overflow-hidden bg-gradient-to-br {{ $product->accent ?: 'from-slate-900 to-teal-900' }} p-7 text-white sm:min-h-[460px] sm:p-8">
                            <div class="absolute -right-16 -top-16 h-60 w-60 rounded-full bg-white/15 blur-3xl"></div>
                            <i class="ph {{ $product->icon ?: 'ph-package' }} relative text-8xl drop-shadow"></i>
                            <div class="relative">
                                <p class="text-sm leading-7 text-white/72">{{ $product->summary }}</p>
                                <h1 class="mt-3 text-3xl font-extrabold leading-tight sm:text-4xl">{{ $product->name }}</h1>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($productFeatures->isNotEmpty())
                <div class="mt-4 grid grid-cols-3 gap-3">
                    @foreach($productFeatures->take(6) as $feature)
                        <div class="rounded-3xl border border-slate-200 bg-white p-4 text-center shadow-sm dark:border-white/10 dark:bg-white/5">
                            <i class="ph ph-check-circle text-2xl text-emerald-600 dark:text-emerald-300"></i>
                            <p class="mt-2 line-clamp-2 text-[11px] font-extrabold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $feature }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-white/10 dark:bg-white/5 sm:rounded-[2rem] sm:p-7">
                <div class="flex flex-wrap items-center gap-2">
                    @if($product->badge)
                        <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-extrabold text-slate-700 dark:bg-white/10 dark:text-slate-200">{{ $product->badge }}</span>
                    @endif
                    @if($product->category)
                        <span class="rounded-full bg-teal-50 px-3 py-1.5 text-xs font-extrabold text-teal-700 dark:bg-teal-400/10 dark:text-teal-300">{{ $product->category->name }}</span>
                    @endif
                    @if($hasVariants)
                        <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-extrabold text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300">{{ $totalVariants }} pilihan</span>
                    @endif
                </div>

                <h1 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-slate-950 dark:text-white sm:text-5xl">{{ $product->name }}</h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300 sm:text-base sm:leading-8">{{ $product->description ?: $product->summary }}</p>

                <div class="mt-6 rounded-[1.5rem] border border-teal-100 bg-gradient-to-br from-teal-50 via-white to-emerald-50 p-5 dark:border-teal-400/15 dark:from-teal-500/10 dark:via-white/5 dark:to-emerald-500/10 sm:rounded-[2rem]">
                    <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $hasVariants ? 'Harga mulai dari' : 'Harga produk' }}</p>
                    <div class="mt-2 flex flex-wrap items-end gap-3">
                        @if($displayOldPrice > $displayPrice)
                            <p class="text-base font-semibold text-slate-400 line-through">{{ Money::rupiah($displayOldPrice) }}</p>
                        @endif
                        <p class="text-3xl font-extrabold text-slate-950 dark:text-white sm:text-4xl">{{ Money::rupiah($displayPrice) }}</p>
                    </div>
                    <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $hasVariants ? 'Pilih paket yang paling sesuai sebelum checkout.' : 'Tambahkan ke keranjang untuk melanjutkan pesanan.' }}</p>
                </div>
            </div>

            <form action="{{ route('cart.add', $product->slug) }}" method="post" class="mt-5 space-y-4 rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-white/10 dark:bg-white/5 sm:rounded-[2rem] sm:p-6" data-cart-form data-cart-open="true">
                @csrf
                @if($hasVariants)
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-lg font-extrabold text-slate-950 dark:text-white">Pilih Varian</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tentukan pilihan yang paling sesuai untuk pesananmu.</p>
                            </div>
                            <span class="shrink-0 rounded-full bg-teal-50 px-3 py-1 text-xs font-extrabold text-teal-700 dark:bg-teal-400/10 dark:text-teal-300">{{ $activeVariants->count() }} pilihan</span>
                        </div>

                        <div class="mt-4 grid gap-3">
                            @foreach($activeVariants as $variant)
                                @php
                                    $isAvailable = is_null($variant->stock) || (int) $variant->stock > 0;
                                @endphp
                                <label data-variant-option class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 p-4 transition dark:border-white/10 dark:bg-white/5 sm:p-5 {{ (int) $variant->id === (int) $defaultVariantId ? 'is-selected' : '' }} {{ $isAvailable ? 'cursor-pointer hover:border-teal-300 hover:bg-teal-50/70 dark:hover:border-teal-400/50 dark:hover:bg-teal-400/10' : 'cursor-not-allowed opacity-55' }}">
                                    <input type="radio" name="variant_id" value="{{ $variant->id }}" class="peer sr-only" @checked((int) $variant->id === (int) $defaultVariantId) @disabled(! $isAvailable) required>
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="variant-radio-mark grid h-6 w-6 place-items-center rounded-full border border-slate-300 bg-white text-transparent transition peer-checked:border-teal-600 peer-checked:bg-teal-600 peer-checked:text-white dark:border-white/20 dark:bg-white/10">
                                                    <i class="variant-radio-check ph-bold ph-check text-xs"></i>
                                                </span>
                                                <p class="font-extrabold text-slate-950 dark:text-white">{{ $variant->name }}</p>
                                            </div>
                                            @if($variant->duration)
                                                <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $variant->duration }}</p>
                                            @endif
                                            @if($variant->description)
                                                <p class="mt-2 text-xs leading-5 text-slate-500 dark:text-slate-400 sm:text-sm">{{ $variant->description }}</p>
                                            @endif
                                            @if(!is_null($variant->stock))
                                                <p class="mt-2 text-[11px] font-extrabold uppercase tracking-wide {{ $isAvailable ? 'text-emerald-600 dark:text-emerald-300' : 'text-rose-600 dark:text-rose-300' }}">{{ $isAvailable ? 'Tersedia' : 'Habis' }}</p>
                                            @endif
                                        </div>
                                        <div class="shrink-0 text-right">
                                            @if((int) $variant->old_price > (int) $variant->price)
                                                <p class="text-xs font-semibold text-slate-400 line-through">{{ Money::rupiah($variant->old_price) }}</p>
                                            @endif
                                            <p class="text-base font-extrabold text-teal-700 dark:text-teal-300 sm:text-lg">{{ Money::rupiah($variant->price) }}</p>
                                            <span class="variant-selected-text mt-1 hidden items-center justify-end gap-1 text-[10px] font-extrabold uppercase tracking-wide text-teal-700 dark:text-teal-300">
                                                <i class="ph ph-check-circle"></i> Dipilih
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="grid gap-3 sm:grid-cols-[1fr_auto]">
                    <button class="inline-flex min-h-[3.35rem] w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-6 py-4 font-extrabold text-white shadow-soft transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white dark:text-slate-950" @disabled($hasVariants && $availableVariants->isEmpty())>
                        <i class="ph ph-shopping-cart-simple"></i> {{ $hasVariants && $availableVariants->isEmpty() ? 'Belum Tersedia' : 'Tambah ke Keranjang' }}
                    </button>
                    <button data-cart-toggle type="button" class="inline-flex min-h-[3.35rem] items-center justify-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-4 font-extrabold text-emerald-700 transition hover:-translate-y-0.5 hover:shadow-lg dark:border-emerald-400/20 dark:bg-emerald-400/10 dark:text-emerald-300">
                        <i class="ph-fill ph-whatsapp-logo"></i> Buka Checkout
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>


<style>
    [data-variant-option].is-selected {
        border-color: rgb(13 148 136) !important;
        background: rgb(240 253 250) !important;
        box-shadow: 0 10px 24px rgba(15, 118, 110, .12) !important;
    }
    [data-variant-option].is-selected .variant-selected-text {
        display: inline-flex;
    }
    [data-variant-option].is-selected .variant-radio-mark {
        border-color: rgb(20 184 166) !important;
        background: rgb(20 184 166) !important;
        color: #fff !important;
        box-shadow: 0 0 0 4px rgba(20, 184, 166, .14) !important;
    }
    [data-variant-option].is-selected .variant-radio-check {
        color: #fff !important;
    }
    .dark [data-variant-option].is-selected {
        border-color: rgb(45 212 191) !important;
        background: rgba(45, 212, 191, .10) !important;
    }
    .dark [data-variant-option].is-selected .variant-radio-mark {
        border-color: rgb(94 234 212) !important;
        background: rgb(45 212 191) !important;
        color: rgb(15 23 42) !important;
        box-shadow: 0 0 0 4px rgba(45, 212, 191, .18) !important;
    }
    .dark [data-variant-option].is-selected .variant-radio-check {
        color: rgb(15 23 42) !important;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const variantOptions = Array.from(document.querySelectorAll('[data-variant-option]'));

        const syncVariantActive = () => {
            variantOptions.forEach((option) => {
                const input = option.querySelector('input[name="variant_id"]');
                option.classList.toggle('is-selected', Boolean(input && input.checked));
            });
        };

        variantOptions.forEach((option) => {
            const input = option.querySelector('input[name="variant_id"]');
            if (!input) return;

            input.addEventListener('change', syncVariantActive);
            option.addEventListener('click', () => {
                if (!input.disabled) {
                    window.requestAnimationFrame(syncVariantActive);
                }
            });
        });

        syncVariantActive();
    });
</script>

@endsection
