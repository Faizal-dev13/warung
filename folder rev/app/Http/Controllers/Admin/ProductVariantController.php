<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Support\SortPositionManager;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = $this->perPage($request);
        $sort = (string) $request->query('sort', 'sort_order');
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';

        $sortable = [
            'name' => 'name',
            'product' => 'product_id',
            'price' => 'price',
            'stock' => 'stock',
            'status' => 'is_active',
            'sort_order' => 'sort_order',
            'created_at' => 'created_at',
        ];

        if (! array_key_exists($sort, $sortable)) {
            $sort = 'sort_order';
        }

        $variants = ProductVariant::query()
            ->with('product.category')
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.trim((string) $request->query('q')).'%';
                $query->where(function ($search) use ($keyword) {
                    $search->where('name', 'like', $keyword)
                        ->orWhere('sku', 'like', $keyword)
                        ->orWhere('duration', 'like', $keyword)
                        ->orWhere('description', 'like', $keyword)
                        ->orWhereHas('product', fn ($product) => $product->where('name', 'like', $keyword));
                });
            })
            ->when($request->filled('product_id'), fn ($query) => $query->where('product_id', $request->query('product_id')))
            ->when($request->query('status') === 'active', fn ($query) => $query->where('is_active', true))
            ->when($request->query('status') === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy($sortable[$sort], $direction)
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.variants.index', [
            'variants' => $variants,
            'products' => Product::orderBy('name')->get(),
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'product_id' => (string) $request->query('product_id', ''),
                'status' => (string) $request->query('status', ''),
                'per_page' => $perPage,
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }

    public function create(Request $request): View
    {
        $selectedProductId = $request->query('product_id');

        return view('admin.variants.form', [
            'variant' => new ProductVariant(['product_id' => $selectedProductId]),
            'products' => Product::where('is_active', true)->orderBy('name')->get(),
            'nextSortOrder' => $selectedProductId
                ? SortPositionManager::next(ProductVariant::class, fn ($query) => $query->where('product_id', $selectedProductId))
                : 1,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $payload = $this->payload($request);
            $productId = (int) $payload['product_id'];
            $payload['sort_order'] = SortPositionManager::makeRoomForCreate(
                ProductVariant::class,
                $payload['sort_order'] ?? null,
                fn ($query) => $query->where('product_id', $productId)
            );

            ProductVariant::create($payload);
        });

        return redirect()->route('admin.variants.index')->with('success', 'Varian produk berhasil ditambahkan. Posisi tampil varian lain sudah otomatis digeser.');
    }

    public function edit(ProductVariant $variant): View
    {
        return view('admin.variants.form', [
            'variant' => $variant,
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, ProductVariant $variant): RedirectResponse
    {
        DB::transaction(function () use ($request, $variant) {
            $payload = $this->payload($request, $variant);
            $oldProductId = (int) $variant->product_id;
            $newProductId = (int) $payload['product_id'];

            if ($oldProductId !== $newProductId) {
                SortPositionManager::closeGap(
                    ProductVariant::class,
                    $variant->sort_order,
                    fn ($query) => $query->where('product_id', $oldProductId),
                    $variant->id
                );

                $payload['sort_order'] = SortPositionManager::makeRoomForCreate(
                    ProductVariant::class,
                    $payload['sort_order'] ?? null,
                    fn ($query) => $query->where('product_id', $newProductId)
                );
            } else {
                $payload['sort_order'] = SortPositionManager::move(
                    $variant,
                    $payload['sort_order'] ?? null,
                    fn ($query) => $query->where('product_id', $newProductId)
                );
            }

            $variant->update($payload);
        });

        return redirect()->route('admin.variants.index')->with('success', 'Varian produk berhasil diperbarui. Posisi tampil varian lain sudah otomatis disesuaikan.');
    }

    public function destroy(ProductVariant $variant): RedirectResponse
    {
        $position = (int) $variant->sort_order;
        $productId = (int) $variant->product_id;

        DB::transaction(function () use ($variant, $position, $productId) {
            $variant->delete();
            SortPositionManager::closeGap(
                ProductVariant::class,
                $position,
                fn ($query) => $query->where('product_id', $productId)
            );
        });

        return back()->with('success', 'Varian produk berhasil dihapus. Posisi tampil varian lain sudah dirapikan.');
    }

    private function payload(Request $request, ?ProductVariant $variant = null): array
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'name' => ['required', 'string', 'max:120'],
            'sku' => ['nullable', 'string', 'max:80', Rule::unique('product_variants', 'sku')->ignore($variant?->id)],
            'duration' => ['nullable', 'string', 'max:80'],
            'price' => ['required', 'integer', 'min:0'],
            'old_price' => ['nullable', 'integer', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
        ]);

        return [
            'product_id' => $data['product_id'],
            'name' => $data['name'],
            'sku' => $data['sku'] ?? null,
            'duration' => $data['duration'] ?? null,
            'price' => (int) $data['price'],
            'old_price' => (int) ($data['old_price'] ?? 0),
            'stock' => array_key_exists('stock', $data) && $data['stock'] !== null ? (int) $data['stock'] : null,
            'description' => $data['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? null,
        ];
    }

    private function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }
}
