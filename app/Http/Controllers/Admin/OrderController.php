<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrderRequest;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderMailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Order::class);

        $search = trim((string) $request->query('search', ''));
        $status = trim((string) $request->query('status', ''));

        $query = Order::query()->with(['user', 'product']);

        if ($status !== '' && $status !== 'all') {
            $query->status($status);
        }

        if ($search !== '') {
            $query->search($search);
        }

        $orders = $query->latest('id')->paginate(15)->withQueryString();

        $metrics = [
            'total' => Order::count(),
            'paid' => Order::where('status', OrderStatus::Paid)->count(),
            'pending' => Order::where('status', OrderStatus::Pending)->count(),
            'revenue' => (float) Order::where('status', OrderStatus::Paid)->sum('amount'),
        ];

        return view('admin.orders.index', [
            'orders' => $orders,
            'metrics' => $metrics,
            'search' => $search,
            'currentStatus' => $status,
            'statuses' => OrderStatus::cases(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Order::class);

        $users = User::query()->orderBy('name')->get(['id', 'name', 'email']);
        $products = Product::query()->orderBy('title')->get(['id', 'title', 'price']);

        return view('admin.orders.create', [
            'order' => new Order,
            'users' => $users,
            'products' => $products,
            'statuses' => OrderStatus::cases(),
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        Gate::authorize('create', Order::class);

        $order = Order::create($request->validated());

        return redirect()->route('admin.orders.show', $order)
            ->with('success', "Pedido #{$order->id} registrado com sucesso.");
    }

    public function show(Order $order): View
    {
        Gate::authorize('view', $order);

        $order->load(['user', 'product']);

        return view('admin.orders.show', [
            'order' => $order,
        ]);
    }

    public function edit(Order $order): View
    {
        Gate::authorize('update', $order);

        $order->load(['user', 'product']);
        $users = User::query()->orderBy('name')->get(['id', 'name', 'email']);
        $products = Product::query()->orderBy('title')->get(['id', 'title', 'price']);

        return view('admin.orders.edit', [
            'order' => $order,
            'users' => $users,
            'products' => $products,
            'statuses' => OrderStatus::cases(),
        ]);
    }

    public function update(UpdateOrderRequest $request, Order $order, OrderMailService $mailService): RedirectResponse
    {
        Gate::authorize('update', $order);

        $wasPaid = $order->status === OrderStatus::Paid;
        $order->update($request->validated());

        if (! $wasPaid && $order->status === OrderStatus::Paid) {
            $order->load(['user', 'product']);
            if ($order->user) {
                $mailService->sendOrderPaidEmail($order->user, $order);
            }
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', "Pedido #{$order->id} atualizado com sucesso.");
    }

    public function destroy(Order $order): RedirectResponse
    {
        Gate::authorize('delete', $order);

        $orderId = $order->id;
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', "Pedido #{$orderId} excluído com sucesso.");
    }
}
