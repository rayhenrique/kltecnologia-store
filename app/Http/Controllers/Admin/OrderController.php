<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Order::class);

        return view('admin.orders.index', ['orders' => Order::query()->with(['user', 'product'])->latest()->paginate(15)]);
    }
}
