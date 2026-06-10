@php
    use App\Support\Money;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $productImagePath = $product->image_path ?? null;
    $productImageUrl = null;
    $activeVariants = $product->relationLoaded('activeVariants') ? $product->activeVariants : collect();
    $availableVariants = $activeVariants->filter(fn ($variant) => is_null($variant->stock) || (int) $variant->stock > 0);
    $priceVariants = $availableVariants->isNotEmpty() ? $availableVariants : $activeVariants;
    $hasVariants = $activeVariants->isNotEmpty();
    $displayPrice = $hasVariants ? (int) $priceVariants->min('price') : (int) $product->price;
    $displayOldPrice = $hasVariants ? (int) $priceVariants->max('old_price') : (int) $product->old_price;

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

<article class="store-product-card group flex h-full min-w-0 flex-col overflow-hidden rounded-[1.35rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-teal-200 hover:shadow-soft dark:border-white/10 dark:bg-white/5 dark:hover:border-teal-400/30">
    <a href="{{ route('products.show', $product->slug) }}" class="block p-3 pb-0 sm:p-4 sm:pb-0" aria-label="Lihat detail {{ $product->name }}">
        <div class="store-product-media aspect-[4/3] relative overflow-hidden rounded-[1.05rem] bg-slate-100 ring-1 ring-slate-100 dark:bg-white/10 dark:ring-white/10 sm:rounded-[1.25rem]">
            @if($productImageUrl)
                <img src="{{ $productImageUrl }}" alt="{{ $product->name }}" class="store-product-photo absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/22 via-transparent to-transparent opacity-80"></div>
            @else
                <div class="absolute inset-0 overflow-hidden bg-gradient-to-br {{ $product->accent ?: 'from-slate-200 to-slate-300' }} p-4 text-white">
                    <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-white/20 blur-2xl"></div>
                    <div class="relative flex h-full items-center justify-center">
                        <i class="ph {{ $product->icon ?: 'ph-package' }} text-5xl drop-shadow"></i>
                    </div>
                </div>
            @endif

        </div>

        @if($product->badge || $hasVariants)
            <div class="store-product-badges mt-2 flex min-w-0 flex-wrap gap-1.5">
                @if($product->badge)
                    <span class="max-w-full truncate rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-extrabold text-slate-700 ring-1 ring-slate-200 dark:bg-white/10 dark:text-slate-200 dark:ring-white/10 sm:text-[11px]">
                        {{ $product->badge }}
                    </span>
                @endif
                @if($hasVariants)
                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-extrabold text-emerald-700 ring-1 ring-emerald-100 dark:bg-emerald-400/10 dark:text-emerald-300 dark:ring-emerald-400/20 sm:text-[11px]">
                        {{ $activeVariants->count() }} varian
                    </span>
                @endif
            </div>
        @endif
    </a>

    <div class="store-product-body flex min-w-0 flex-1 flex-col p-3 sm:p-4">
        <p class="store-product-category truncate text-[11px] font-extrabold uppercase tracking-wide text-teal-700 dark:text-teal-300 sm:text-xs">{{ $product->category?->name ?? 'Produk' }}</p>

        <a href="{{ route('products.show', $product->slug) }}" class="store-product-title mt-1.5 line-clamp-2 min-h-[2.25rem] text-sm font-extrabold leading-tight text-slate-950 transition group-hover:text-teal-700 dark:text-white dark:group-hover:text-teal-300 sm:mt-2 sm:min-h-[3rem] sm:text-lg sm:leading-snug">
            {{ $product->name }}
        </a>

        <p class="store-product-summary mt-1.5 line-clamp-2 min-h-[2rem] text-xs leading-5 text-slate-500 dark:text-slate-400 sm:mt-2 sm:min-h-[3rem] sm:text-sm sm:leading-6">{{ $product->summary }}</p>

        <div class="store-product-footer mt-auto pt-3">
            <div class="min-w-0 rounded-2xl bg-slate-50 p-3 dark:bg-white/5">
                @if($displayOldPrice > $displayPrice)
                    <p class="store-product-old-price truncate text-[11px] font-semibold text-slate-400 line-through sm:text-sm">{{ Money::rupiah($displayOldPrice) }}</p>
                @endif
                @if($hasVariants)
                    <p class="text-[10px] font-extrabold uppercase tracking-wide text-emerald-600 dark:text-emerald-300">Mulai dari</p>
                @endif
                <p class="store-product-price truncate text-sm font-extrabold text-slate-950 dark:text-white sm:text-xl">{{ Money::rupiah($displayPrice) }}</p>
            </div>

            <div class="store-product-actions mt-3 grid grid-cols-[1fr_2.5rem] gap-2 sm:grid-cols-[1fr_3rem]">
                @if($hasVariants)
                    <a href="{{ route('products.show', $product->slug) }}" class="store-product-add-button inline-flex h-10 w-full items-center justify-center gap-1.5 rounded-2xl bg-slate-950 px-3 text-xs font-extrabold text-white transition hover:scale-[1.02] dark:bg-white dark:text-slate-950 sm:h-12 sm:text-sm">
                        <i class="ph ph-list-checks text-base"></i>
                        <span>Pilih</span>
                    </a>
                @else
                    <form action="{{ route('cart.add', $product->slug) }}" method="post" class="min-w-0" data-cart-form data-cart-open="true">
                        @csrf
                        <button class="store-product-add-button inline-flex h-10 w-full items-center justify-center gap-1.5 rounded-2xl bg-slate-950 px-3 text-xs font-extrabold text-white transition hover:scale-[1.02] disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white dark:text-slate-950 sm:h-12 sm:text-sm">
                            <i class="ph ph-shopping-cart-simple text-base"></i>
                            <span>Tambah</span>
                        </button>
                    </form>
                @endif

                <a href="{{ route('products.show', $product->slug) }}" class="store-product-detail-button inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 transition hover:border-teal-200 hover:text-teal-700 dark:border-white/10 dark:bg-white/10 dark:text-white sm:h-12 sm:w-12" aria-label="Detail {{ $product->name }}" title="Detail produk">
                    <i class="ph ph-arrow-right text-base sm:text-lg"></i>
                </a>
            </div>
        </div>
    </div>
</article>
