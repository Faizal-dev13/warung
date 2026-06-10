<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Qna;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Voucher;
use App\Support\Money;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PublicDataService
{
    public function __construct(private readonly PublicCacheService $cache)
    {
    }

    public function settings(): array
    {
        return $this->cache->remember('settings', now()->addHours(6), fn () => Setting::store());
    }

    public function cartSummary(?array $items = null): array
    {
        $items = $items ?? session('cart', []);
        $subtotal = collect($items)->sum(fn ($item) => (int) ($item['price'] ?? 0) * (int) ($item['qty'] ?? 0));

        return [
            'items' => $items,
            'count' => collect($items)->sum(fn ($item) => (int) ($item['qty'] ?? 0)),
            'subtotal' => $subtotal,
            'subtotal_formatted' => Money::rupiah($subtotal),
        ];
    }

    public function homeData(): array
    {
        return [
            'banners' => $this->banners(),
            'categories' => $this->categories(),
            'products' => $this->featuredProducts(),
            'vouchers' => $this->activeVouchers(),
            'qnas' => $this->qnas(),
        ];
    }

    public function banners(): Collection
    {
        return $this->cache->remember('home:banners', now()->addHours(6), fn () => Banner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get());
    }

    public function categories(): Collection
    {
        return $this->cache->remember('home:categories', now()->addHours(6), fn () => Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get());
    }

    public function productCategories(): Collection
    {
        return $this->cache->remember('products:categories', now()->addHours(6), fn () => Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get());
    }

    public function featuredProducts(): Collection
    {
        $variantPriceSql = "select min(price) from product_variants where product_variants.product_id = products.id and product_variants.is_active = 1 and (product_variants.stock is null or product_variants.stock > 0)";
        $variantDiscountSql = "select max(case when old_price is not null and old_price > price then old_price - price else 0 end) from product_variants where product_variants.product_id = products.id and product_variants.is_active = 1 and (product_variants.stock is null or product_variants.stock > 0)";

        return $this->cache->remember('home:products', now()->addMinutes(30), fn () => Product::query()
            ->select('products.*')
            ->selectRaw("coalesce(($variantPriceSql), products.price) as sort_price")
            ->selectRaw("coalesce(($variantDiscountSql), case when products.old_price is not null and products.old_price > products.price then products.old_price - products.price else 0 end, 0) as sort_discount")
            ->with(['category', 'activeVariants'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->take(8)
            ->get());
    }

    public function activeVouchers(): Collection
    {
        return $this->cache->remember('home:vouchers', now()->addMinutes(30), fn () => Voucher::query()
            ->with('products')
            ->where('is_active', true)
            ->latest()
            ->get());
    }

    public function qnas(): Collection
    {
        return $this->cache->remember('qna:list', now()->addHours(6), fn () => Qna::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get());
    }

    public function testimonialSummary(): array
    {
        return $this->cache->remember('testimonials:summary', now()->addHours(3), fn () => [
            'count' => (int) Testimonial::query()->where('is_active', true)->count(),
            'average_rating' => round((float) Testimonial::query()->where('is_active', true)->avg('rating'), 1),
        ]);
    }

    public function productDetail(string $slug): ?Model
    {
        // Detail produk sengaja tidak dicache keras agar perubahan harga, stok, dan varian
        // dari admin langsung aman terbaca tanpa harus membersihkan key dinamis satu per satu.
        return Product::query()
            ->with(['category', 'activeVariants'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    public function relatedProducts(Product $product): Collection
    {
        return Product::query()
            ->with(['category', 'activeVariants'])
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->orderBy('sort_order')
            ->take(3)
            ->get();
    }
}
