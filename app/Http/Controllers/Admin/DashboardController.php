<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'waiting_whatsapp_confirmation')->count(),
            'activeVouchers' => Voucher::where('is_active', true)->count(),
            'activeBanners' => Banner::where('is_active', true)->count(),
            'latestOrders' => Order::with('items.product')->latest()->limit(6)->get(),
        ]);
    }
}
