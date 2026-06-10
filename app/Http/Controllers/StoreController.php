<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Testimonial;
use App\Services\PublicDataService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(private readonly PublicDataService $publicData)
    {
    }

    public function home(Request $request): View
    {
        return view('pages.home', array_merge($this->publicData->homeData(), [
            'filters' => [
                'query' => '',
                'category' => 'all',
                'sort' => 'popular',
            ],
            'cart' => $this->publicData->cartSummary(),
            'settings' => $this->publicData->settings(),
        ]));
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

        return redirect(route('testimonials.index').'#form-testimoni')
            ->with('success', 'Terima kasih, testimoni kamu sudah terkirim. Admin akan mengecek dulu sebelum ditampilkan.');
    }

    public function testimonials(): View
    {
        $testimonials = Testimonial::query()
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('pages.testimonials', [
            'testimonials' => $testimonials,
            'testimonialSummary' => $this->publicData->testimonialSummary(),
            'cart' => $this->publicData->cartSummary(),
            'settings' => $this->publicData->settings(),
        ]);
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
            'categories' => $this->publicData->productCategories(),
            'products' => $products,
            'filters' => $filters,
            'cart' => $this->publicData->cartSummary(),
            'settings' => $this->publicData->settings(),
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
            'cart' => $this->publicData->cartSummary(),
            'settings' => $this->publicData->settings(),
        ]);
    }

    public function qna(): View
    {
        return view('pages.qna', [
            'qnas' => $this->publicData->qnas(),
            'cart' => $this->publicData->cartSummary(),
            'settings' => $this->publicData->settings(),
        ]);
    }

    public function product(string $slug): View
    {
        $product = $this->publicData->productDetail($slug);

        abort_unless($product, 404);

        return view('products.show', [
            'product' => $product,
            'related' => $this->publicData->relatedProducts($product),
            'cart' => $this->publicData->cartSummary(),
            'settings' => $this->publicData->settings(),
        ]);
    }
}
