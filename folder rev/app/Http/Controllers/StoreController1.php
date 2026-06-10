<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Qna;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Voucher;
use App\Support\Money;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function home(Request $request): View
    {
        $products = $this->productQuery([
                'query' => '',
                'category' => 'all',
                'sort' => 'popular',
            ])
            ->take(8)
            ->get();

        return view('pages.home', [
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'products' => $products,
            'vouchers' => Voucher::with('products')->where('is_active', true)->latest()->get(),
            'testimonials' => Testimonial::where('is_active', true)->latest()->take(6)->get(),
            'filters' => [
                'query' => '',
                'category' => 'all',
                'sort' => 'popular',
            ],
            'cart' => $this->cartSummary(),
            'settings' => Setting::store(),
        ]);
    }

    public function storeTestimonial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'name.required' => 'Nama customer wajib diisi.',
            'name.max' => 'Nama customer maksimal 80 karakter.',
            'rating.required' => 'Rating wajib dipilih.',
            'rating.integer' => 'Rating tidak valid.',
            'rating.min' => 'Rating minimal 1.',
            'rating.max' => 'Rating maksimal 5.',
            'message.required' => 'Isi testimoni wajib diisi.',
            'message.min' => 'Isi testimoni minimal 10 karakter.',
            'message.max' => 'Isi testimoni maksimal 500 karakter.',
        ]);

        Testimonial::create([
            'name' => trim($validated['name']),
            'message' => trim($validated['message']),
            'rating' => (int) $validated['rating'],
            'image_path' => null,
            'is_active' => false,
        ]);

        return redirect(route('home').'#testimoni')
            ->with('success', 'Terima kasih, testimoni kamu sudah terkirim dan akan tampil setelah dicek admin.');
    }

    public function products(Request $request): View
    {
        $filters = [
            'query' => trim((string) $request->query('q', '')),
            'category' => (string) $request->query('category', 'all'),
            'sort' => (string) $request->query('sort', 'popular'),
        ];

        $products = $this->productQuery($filters)
            ->paginate(12)
            ->withQueryString();

        return view('pages.products', [
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'products' => $products,
            'filters' => $filters,
            'cart' => $this->cartSummary(),
            'settings' => Setting::store(),
        ]);
    }

    private function productQuery(array $filters)
    {
        $variantPriceSql = "select min(price) from product_variants where product_variants.product_id = products.id and product_variants.is_active = 1 and (product_variants.stock is null or product_variants.stock > 0)";
        $variantDiscountSql = "select max(case when old_price is not null and old_price > price then old_price - price else 0 end) from product_variants where product_variants.product_id = products.id and product_variants.is_active = 1 and (product_variants.stock is null or product_variants.stock > 0)";

        return Product::query()
            ->select('products.*')
            ->selectRaw("coalesce(($variantPriceSql), products.price) as sort_price")
            ->selectRaw("coalesce(($variantDiscountSql), case when products.old_price is not null and products.old_price > products.price then products.old_price - products.price else 0 end, 0) as sort_discount")
            ->with(['category', 'activeVariants'])
            ->where('is_active', true)
            ->when(($filters['category'] ?? 'all') !== 'all', function ($query) use ($filters) {
                $query->whereHas('category', fn ($category) => $category->where('slug', $filters['category']));
            })
            ->when(($filters['query'] ?? '') !== '', function ($query) use ($filters) {
                $keyword = '%'.$filters['query'].'%';
                $query->where(function ($search) use ($keyword) {
                    $search->where('name', 'like', $keyword)
                        ->orWhere('summary', 'like', $keyword)
                        ->orWhere('description', 'like', $keyword);
                });
            })
            ->when(($filters['sort'] ?? 'popular') === 'highest', fn ($query) => $query->orderByDesc('sort_price'))
            ->when(($filters['sort'] ?? 'popular') === 'lowest', fn ($query) => $query->orderBy('sort_price'))
            ->when(($filters['sort'] ?? 'popular') === 'discount', fn ($query) => $query->orderByDesc('sort_discount')->orderBy('sort_price'))
            ->when(($filters['sort'] ?? 'popular') === 'popular', fn ($query) => $query->orderBy('sort_order')->orderByDesc('id'));
    }

    public function guide(): View
    {
        return view('pages.guide', [
            'cart' => $this->cartSummary(),
            'settings' => Setting::store(),
        ]);
    }

    public function qna(): View
    {
        return view('pages.qna', [
            'qnas' => Qna::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'cart' => $this->cartSummary(),
            'settings' => Setting::store(),
        ]);
    }

    public function product(string $slug): View
    {
        $product = Product::with(['category', 'activeVariants'])->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('products.show', [
            'product' => $product,
            'related' => Product::with(['category', 'activeVariants'])
                ->where('is_active', true)
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->id)
                ->orderBy('sort_order')
                ->take(3)
                ->get(),
            'cart' => $this->cartSummary(),
            'settings' => Setting::store(),
        ]);
    }

    private function cartSummary(): array
    {
        $items = session('cart', []);
        $subtotal = collect($items)->sum(fn ($item) => $item['price'] * $item['qty']);

        return [
            'items' => $items,
            'count' => collect($items)->sum('qty'),
            'subtotal' => $subtotal,
            'subtotal_formatted' => Money::rupiah($subtotal),
        ];
    }
}
