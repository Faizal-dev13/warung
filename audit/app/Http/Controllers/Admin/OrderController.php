<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    private function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }
}
