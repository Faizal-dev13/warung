<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Support\SortPositionManager;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = $this->perPage($request);
        $sort = (string) $request->query('sort', 'sort_order');
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';

        $sortable = [
            'name' => 'name',
            'products' => 'products_count',
            'sort_order' => 'sort_order',
            'status' => 'is_active',
            'created_at' => 'created_at',
        ];

        if (! array_key_exists($sort, $sortable)) {
            $sort = 'sort_order';
        }

        $categories = Category::query()
            ->withCount('products')
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.trim((string) $request->query('q')).'%';
                $query->where('name', 'like', $keyword);
            })
            ->when($request->query('status') === 'active', fn ($query) => $query->where('is_active', true))
            ->when($request->query('status') === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy($sortable[$sort], $direction)
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'status' => (string) $request->query('status', ''),
                'per_page' => $perPage,
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', [
            'category' => new Category(),
            'nextSortOrder' => SortPositionManager::next(Category::class),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $payload = $this->payload($request);
            $payload['sort_order'] = SortPositionManager::makeRoomForCreate(Category::class, $payload['sort_order'] ?? null);

            Category::create($payload);
        });

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan. Posisi tampil kategori lain sudah otomatis digeser.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        DB::transaction(function () use ($request, $category) {
            $payload = $this->payload($request, $category);
            $payload['sort_order'] = SortPositionManager::move($category, $payload['sort_order'] ?? null);

            $category->update($payload);
        });

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui. Posisi tampil kategori lain sudah otomatis disesuaikan.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Kategori masih dipakai produk. Pindahkan produk dulu sebelum dihapus.');
        }

        $position = (int) $category->sort_order;

        DB::transaction(function () use ($category, $position) {
            $category->delete();
            SortPositionManager::closeGap(Category::class, $position);
        });

        return back()->with('success', 'Kategori berhasil dihapus. Posisi tampil kategori lain sudah dirapikan.');
    }

    private function payload(Request $request, ?Category $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('categories', 'name')->ignore($category?->id)],
            'icon' => ['nullable', 'string', 'max:80'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
        ]);

        return [
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'icon' => $data['icon'] ?: 'ph-tag',
            'sort_order' => $data['sort_order'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }
}
