<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Support\SortPositionManager;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = $this->perPage($request);
        $sort = (string) $request->query('sort', 'sort_order');
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';

        $sortable = [
            'name' => 'name',
            'category' => 'category_id',
            'price' => 'price',
            'sort_order' => 'sort_order',
            'status' => 'is_active',
            'created_at' => 'created_at',
        ];

        if (! array_key_exists($sort, $sortable)) {
            $sort = 'sort_order';
        }

        $products = Product::query()
            ->with('category')->withCount('variants')
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.trim((string) $request->query('q')).'%';
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', $keyword)
                        ->orWhere('summary', 'like', $keyword)
                        ->orWhere('description', 'like', $keyword)
                        ->orWhere('badge', 'like', $keyword);
                });
            })
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->query('category_id')))
            ->when($request->query('status') === 'active', fn ($query) => $query->where('is_active', true))
            ->when($request->query('status') === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy($sortable[$sort], $direction)
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'category_id' => (string) $request->query('category_id', ''),
                'status' => (string) $request->query('status', ''),
                'per_page' => $perPage,
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'nextSortOrder' => SortPositionManager::next(Product::class),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $product = null;

        DB::transaction(function () use ($request, &$product) {
            $payload = $this->payload($request);
            $payload['sort_order'] = SortPositionManager::makeRoomForCreate(Product::class, $payload['sort_order'] ?? null);

            $product = new Product();
            $product->forceFill($payload)->save();
        });

        return redirect()->route('admin.products.edit', $product)->with('success', 'Produk berhasil ditambahkan. Posisi tampil produk lain sudah otomatis digeser. Tambahkan varian jika produk memiliki pilihan paket atau durasi.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product->load('variants'),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product) {
            $payload = $this->payload($request, $product);
            $payload['sort_order'] = SortPositionManager::move($product, $payload['sort_order'] ?? null);

            $product->forceFill($payload)->save();
        });

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui. Posisi tampil produk lain sudah otomatis disesuaikan.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $position = (int) $product->sort_order;

        $this->deletePublicFile($product->image_path ?? null);

        DB::transaction(function () use ($product, $position) {
            $product->delete();
            SortPositionManager::closeGap(Product::class, $position);
        });

        return back()->with('success', 'Produk berhasil dihapus. Posisi tampil produk lain sudah dirapikan.');
    }

    private function payload(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:160', Rule::unique('products', 'name')->ignore($product?->id)],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'old_price' => ['nullable', 'integer', 'min:0'],
            'badge' => ['nullable', 'string', 'max:60'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'remove_image' => ['nullable', 'boolean'],
            'features' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
        ]);

        $imagePath = $product->image_path ?? null;

        if ($request->boolean('remove_image')) {
            $this->deletePublicFile($imagePath);
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            $this->deletePublicFile($imagePath);
            $imagePath = app(ImageOptimizationService::class)->storeWebp($request->file('image'), 'products', 1100, 82);
        }

        $features = collect(preg_split('/\r\n|\r|\n/', (string) ($data['features'] ?? '')))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();

        return [
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'summary' => $data['summary'],
            'description' => $data['description'],
            'price' => (int) $data['price'],
            'old_price' => (int) ($data['old_price'] ?? 0),
            'badge' => $data['badge'] ?? null,
            'image_path' => $imagePath,
            'icon' => 'ph-image-square',
            'accent' => 'from-slate-200 to-slate-300',
            'features' => $features,
            'is_latest' => false,
            'is_featured' => false,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? null,
        ];
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }
}
