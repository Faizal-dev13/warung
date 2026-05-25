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
    public function index(): View
    {
        return view('admin.vouchers.index', [
            'vouchers' => Voucher::latest()->paginate(12),
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
}
