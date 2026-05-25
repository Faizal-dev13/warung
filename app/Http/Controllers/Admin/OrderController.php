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
        $orders = Order::query()
            ->withCount('items')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%' . $request->search . '%';
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('invoice_number', 'like', $search)
                        ->orWhere('customer_name', 'like', $search)
                        ->orWhere('customer_phone', 'like', $search);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => self::STATUSES,
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load('items.product'),
            'statuses' => self::STATUSES,
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
}
