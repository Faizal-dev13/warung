<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Voucher;
use App\Support\Money;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function home(Request $request): View
    {
        $filters = [
            'query' => trim((string) $request->query('q', '')),
            'category' => (string) $request->query('category', 'all'),
            'sort' => (string) $request->query('sort', 'popular'),
        ];

        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when($filters['category'] !== 'all', function ($query) use ($filters) {
                $query->whereHas('category', fn ($category) => $category->where('slug', $filters['category']));
            })
            ->when($filters['query'] !== '', function ($query) use ($filters) {
                $keyword = '%'.$filters['query'].'%';
                $query->where(function ($search) use ($keyword) {
                    $search->where('name', 'like', $keyword)
                        ->orWhere('summary', 'like', $keyword)
                        ->orWhere('description', 'like', $keyword);
                });
            })
            ->when($filters['sort'] === 'highest', fn ($query) => $query->orderByDesc('price'))
            ->when($filters['sort'] === 'lowest', fn ($query) => $query->orderBy('price'))
            ->when($filters['sort'] === 'discount', fn ($query) => $query->orderByRaw('(old_price - price) desc'))
            ->when($filters['sort'] === 'popular', fn ($query) => $query->orderBy('sort_order')->orderByDesc('is_featured'))
            ->get();

        return view('pages.home', [
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'products' => $products,
            'latestProducts' => Product::with('category')->where('is_active', true)->where('is_latest', true)->latest()->take(3)->get(),
            'vouchers' => Voucher::where('is_active', true)->latest()->get(),
            'filters' => $filters,
            'cart' => $this->cartSummary(),
        ]);
    }

    public function product(string $slug): View
    {
        $product = Product::with('category')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('products.show', [
            'product' => $product,
            'related' => Product::with('category')
                ->where('is_active', true)
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->id)
                ->take(3)
                ->get(),
            'cart' => $this->cartSummary(),
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
