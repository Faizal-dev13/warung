<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VoucherController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = $this->perPage($request);
        $sort = (string) $request->query('sort', 'created_at');
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $sortable = [
            'code' => 'code',
            'label' => 'label',
            'type' => 'type',
            'value' => 'value',
            'minimum_order' => 'minimum_order',
            'status' => 'is_active',
            'created_at' => 'created_at',
        ];

        if (! array_key_exists($sort, $sortable)) {
            $sort = 'created_at';
        }

        $vouchers = Voucher::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.trim((string) $request->query('q')).'%';
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('code', 'like', $keyword)
                        ->orWhere('label', 'like', $keyword);
                });
            })
            ->when($request->query('status') === 'active', fn ($query) => $query->where('is_active', true))
            ->when($request->query('status') === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when(in_array($request->query('type'), ['fixed', 'percent'], true), fn ($query) => $query->where('type', $request->query('type')))
            ->orderBy($sortable[$sort], $direction)
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.vouchers.index', [
            'vouchers' => $vouchers,
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'status' => (string) $request->query('status', ''),
                'type' => (string) $request->query('type', ''),
                'per_page' => $perPage,
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.vouchers.form', ['voucher' => new Voucher()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Voucher::create($this->payload($request));

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function edit(Voucher $voucher): View
    {
        return view('admin.vouchers.form', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher): RedirectResponse
    {
        $voucher->update($this->payload($request, $voucher));

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy(Voucher $voucher): RedirectResponse
    {
        $voucher->delete();

        return back()->with('success', 'Voucher berhasil dihapus.');
    }

    private function payload(Request $request, ?Voucher $voucher = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:40', Rule::unique('vouchers', 'code')->ignore($voucher?->id)],
            'label' => ['required', 'string', 'max:120'],
            'type' => ['required', 'in:fixed,percent'],
            'value' => ['required', 'integer', 'min:0'],
            'minimum_order' => ['nullable', 'integer', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        return [
            'code' => Str::upper($data['code']),
            'label' => $data['label'],
            'type' => $data['type'],
            'value' => (int) $data['value'],
            'minimum_order' => (int) ($data['minimum_order'] ?? 0),
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }
}
