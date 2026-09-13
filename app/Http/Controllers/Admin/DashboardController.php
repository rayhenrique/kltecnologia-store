<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'activeProducts' => Product::query()->where('is_active', true)->count(),
            'paidOrders' => Order::query()->where('status', OrderStatus::Paid)->count(),
            'revenue' => Order::query()->where('status', OrderStatus::Paid)->sum('amount'),
            'latestOrders' => Order::query()->with(['user', 'product'])->latest()->limit(5)->get(),
        ]);
    }
}
