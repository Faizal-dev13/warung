<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public const STATUSES = [
        'waiting_whatsapp_confirmation',
        'confirmed',
        'processed',
        'completed',
        'cancelled',
    ];

    public function index(Request $request): View
    {
        $perPage = $this->perPage($request);
        $sort = (string) $request->query('sort', 'created_at');
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $sortable = [
            'invoice' => 'invoice_number',
            'customer' => 'customer_name',
            'items' => 'items_count',
            'total' => 'total',
            'status' => 'status',
            'created_at' => 'created_at',
        ];

        if (! array_key_exists($sort, $sortable)) {
            $sort = 'created_at';
        }

        $orders = Order::query()
            ->withCount('items')
            ->when(in_array($request->query('status'), self::STATUSES, true), fn ($query) => $query->where('status', $request->query('status')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.trim((string) $request->query('q')).'%';
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('invoice_number', 'like', $keyword)
                        ->orWhere('customer_name', 'like', $keyword)
                        ->orWhere('customer_phone', 'like', $keyword)
                        ->orWhere('voucher_code', 'like', $keyword);
                });
            })
            ->orderBy($sortable[$sort], $direction)
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => self::STATUSES,
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'status' => (string) $request->query('status', ''),
                'per_page' => $perPage,
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load(['items.product', 'items.variant']),
            'statuses' => self::STATUSES,
        ]);
    }

    public function invoice(Order $order): View
    {
        return view('admin.orders.invoice', [
            'order' => $order->load(['items.product', 'items.variant']),
            'statusLabels' => [
                'waiting_whatsapp_confirmation' => 'Menunggu WA',
                'confirmed' => 'Dikonfirmasi',
                'processed' => 'Diproses',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
            ],
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', self::STATUSES)],
        ]);

        $order->update($data);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        DB::transaction(function () use ($order) {
            $order->items()->delete();
            $order->delete();
        });

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'orders' => ['required', 'array', 'min:1'],
            'orders.*' => ['integer', 'exists:orders,id'],
        ], [
            'orders.required' => 'Pilih minimal 1 order yang ingin dihapus.',
            'orders.min' => 'Pilih minimal 1 order yang ingin dihapus.',
            'orders.*.exists' => 'Ada order yang sudah tidak tersedia. Silakan refresh halaman lalu coba lagi.',
        ]);

        $orderIds = collect($data['orders'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($orderIds->isEmpty()) {
            return back()->with('error', 'Pilih minimal 1 order yang ingin dihapus.');
        }

        $deletedCount = DB::transaction(function () use ($orderIds): int {
            OrderItem::whereIn('order_id', $orderIds->all())->delete();

            return Order::whereIn('id', $orderIds->all())->delete();
        });

        return redirect()
            ->route('admin.orders.index')
            ->with('success', $deletedCount.' order berhasil dihapus.');
    }

    private function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }
}
