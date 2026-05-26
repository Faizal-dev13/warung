@php
    use App\Support\Money;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $productImagePath = $product->image_path ?? null;
    $productImageUrl = null;

    if ($productImagePath) {
        if (Str::startsWith($productImagePath, ['http://', 'https://', '/'])) {
            $productImageUrl = $productImagePath;
        } elseif (Str::startsWith($productImagePath, ['storage/'])) {
            $productImageUrl = asset($productImagePath);
        } else {
            $productImageUrl = Storage::url($productImagePath);
        }
    }
@endphp

@extends('layouts.app')
@section('title', $product->name.' - '.config('store.name'))
@section('content')
<section class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:py-12">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ route('home') }}#produk" class="inline-flex w-fit items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-extrabold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-blue-600 hover:shadow-md dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:border-blue-400/30 dark:hover:text-blue-300">
            <span class="grid h-8 w-8 place-items-center rounded-xl bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-white">
                <i class="ph ph-arrow-left"></i>
            </span>
            Kembali ke Katalog
        </a>

        <button data-cart-toggle type="button" class="inline-flex w-fit items-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:bg-white dark:text-slate-950">
            <i class="ph ph-shopping-cart-simple"></i> Cek Keranjang
        </button>
    </div>

    <div class="grid gap-6 lg:grid-cols-[.95fr_1.05fr] lg:gap-10">
        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft dark:border-white/10 dark:bg-white/5 sm:rounded-[2rem]">
            <div class="relative overflow-hidden bg-slate-100 dark:bg-white/10">
                @if($productImageUrl)
                    <img src="{{ $productImageUrl }}" alt="{{ $product->name }}" class="aspect-[4/3] w-full object-cover sm:aspect-square lg:aspect-[4/4.35]">
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/18 via-transparent to-transparent"></div>
                    @if($product->badge)
                        <span class="absolute left-4 top-4 rounded-full bg-white/90 px-4 py-2 text-xs font-extrabold text-slate-800 shadow-sm ring-1 ring-white/70 backdrop-blur dark:bg-slate-950/80 dark:text-white dark:ring-white/10">{{ $product->badge }}</span>
                    @endif
                @else
                    <div class="relative flex min-h-[320px] flex-col justify-between overflow-hidden bg-gradient-to-br {{ $product->accent ?: 'from-slate-900 to-blue-900' }} p-7 text-white sm:min-h-[460px] sm:p-8">
                        <div class="absolute -right-16 -top-16 h-60 w-60 rounded-full bg-white/15 blur-3xl"></div>
                        <span class="relative w-fit rounded-full bg-white/20 px-4 py-2 text-sm font-bold backdrop-blur">{{ $product->badge ?? 'Produk Pilihan' }}</span>
                        <i class="ph {{ $product->icon ?: 'ph-package' }} relative text-8xl drop-shadow"></i>
                        <div class="relative">
                            <p class="text-sm leading-7 text-white/72">{{ $product->summary }}</p>
                            <h1 class="mt-3 text-3xl font-extrabold leading-tight sm:text-4xl">{{ $product->name }}</h1>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:py-4">
            <div class="flex flex-wrap items-center gap-2">
                @if($product->category)
                    <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-extrabold text-blue-700 dark:bg-blue-400/10 dark:text-blue-300">{{ $product->category->name }}</span>
                @endif
                @if($product->badge)
                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-extrabold text-slate-600 dark:bg-white/10 dark:text-slate-300">{{ $product->badge }}</span>
                @endif
            </div>

            <h1 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-slate-950 dark:text-white sm:text-5xl">{{ $product->name }}</h1>
            <p class="mt-4 text-base leading-8 text-slate-600 dark:text-slate-300">{{ $product->description }}</p>

            <div class="mt-6 rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:rounded-[2rem]">
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400">Harga Produk</p>
                <div class="mt-2 flex flex-wrap items-end gap-3">
                    @if($product->old_price > $product->price)
                        <p class="text-base font-semibold text-slate-400 line-through">{{ Money::rupiah($product->old_price) }}</p>
                    @endif
                    <p class="text-3xl font-extrabold text-slate-950 dark:text-white sm:text-4xl">{{ Money::rupiah($product->price) }}</p>
                </div>
            </div>

            @if(! empty($product->features))
                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    @foreach(($product->features ?? []) as $feature)
                        <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                            <i class="ph-fill ph-check-circle mt-0.5 text-xl text-emerald-500"></i>
                            <span class="text-sm font-semibold leading-6">{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 grid gap-3 sm:grid-cols-[1fr_auto]">
                <form action="{{ route('cart.add', $product->slug) }}" method="post" class="min-w-0" data-cart-form data-cart-open="true">
                    @csrf
                    <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-6 py-4 font-extrabold text-white shadow-soft transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white dark:text-slate-950">
                        <i class="ph ph-shopping-cart-simple"></i> Tambah ke Keranjang
                    </button>
                </form>
                <button data-cart-toggle type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-6 py-4 font-extrabold transition hover:-translate-y-0.5 hover:shadow-lg dark:border-white/10 dark:bg-white/10">
                    <i class="ph-fill ph-whatsapp-logo text-emerald-500"></i> Checkout
                </button>
            </div>
        </div>
    </div>
</section>

@if($related->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pb-24 sm:px-6 md:pb-16 lg:px-8">
    <div class="mb-6 flex items-end justify-between gap-4">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-wide text-blue-600 dark:text-blue-400">Produk Terkait</p>
            <h2 class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">Pilihan lain yang bisa kamu cek</h2>
        </div>
        <a href="{{ route('home') }}#produk" class="hidden rounded-2xl border border-slate-200 px-5 py-3 text-sm font-extrabold transition hover:bg-white dark:border-white/10 dark:hover:bg-white/10 sm:inline-flex">Lihat Katalog</a>
    </div>
    <div class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-3">
        @foreach($related as $product)
            @include('products.card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif
@endsection
