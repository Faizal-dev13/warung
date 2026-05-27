<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = $this->perPage($request);
        $sort = (string) $request->query('sort', 'sort_order');
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';

        $sortable = [
            'title' => 'title',
            'label' => 'label',
            'sort_order' => 'sort_order',
            'status' => 'is_active',
            'created_at' => 'created_at',
        ];

        if (! array_key_exists($sort, $sortable)) {
            $sort = 'sort_order';
        }

        $banners = Banner::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.trim((string) $request->query('q')).'%';
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('title', 'like', $keyword)
                        ->orWhere('label', 'like', $keyword)
                        ->orWhere('subtitle', 'like', $keyword);
                });
            })
            ->when($request->query('status') === 'active', fn ($query) => $query->where('is_active', true))
            ->when($request->query('status') === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy($sortable[$sort], $direction)
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.banners.index', [
            'banners' => $banners,
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
        return view('admin.banners.form', ['banner' => new Banner()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $banner = new Banner();
        $banner->forceFill($this->payload($request))->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $banner->forceFill($this->payload($request, $banner))->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->deletePublicFile($banner->image_path ?? null);
        $this->deletePublicFile($banner->mobile_image_path ?? null);
        $banner->delete();

        return back()->with('success', 'Banner berhasil dihapus.');
    }

    private function payload(Request $request, ?Banner $banner = null): array
    {
        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:160'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'button_url' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'mobile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
            'remove_mobile_image' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $imagePath = $banner->image_path ?? null;
        $mobileImagePath = $banner->mobile_image_path ?? null;

        if ($request->boolean('remove_image')) {
            $this->deletePublicFile($imagePath);
            $imagePath = null;
        }

        if ($request->boolean('remove_mobile_image')) {
            $this->deletePublicFile($mobileImagePath);
            $mobileImagePath = null;
        }

        if ($request->hasFile('image')) {
            $this->deletePublicFile($imagePath);
            $imagePath = $request->file('image')->store('banners', 'public');
        }

        if ($request->hasFile('mobile_image')) {
            $this->deletePublicFile($mobileImagePath);
            $mobileImagePath = $request->file('mobile_image')->store('banners/mobile', 'public');
        }

        return [
            'label' => $data['label'] ?? null,
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'button_text' => $data['button_text'] ?? null,
            'button_url' => $data['button_url'] ?? null,
            'image_path' => $imagePath,
            'mobile_image_path' => $mobileImagePath,
            'icon' => 'ph-image-square',
            'accent' => 'from-slate-950 to-teal-900',
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
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
