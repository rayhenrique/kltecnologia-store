<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Http\Requests\ListFilterRequest;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\User;
use App\Services\NewsletterService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        private readonly NewsletterService $newsletterService,
    ) {}

    public function index(ListFilterRequest $request): View
    {
        Gate::authorize('viewAny', User::class);

        $search = trim((string) ($request->validated('q') ?? $request->validated('search', '')));
        $status = (string) $request->validated('status', 'all');
        $sort = (string) $request->validated('sort', 'recent');

        $query = User::query()
            ->withCount('orders')
            ->withCount(['orders as paid_orders_count' => function ($q): void {
                $q->where('status', OrderStatus::Paid);
            }])
            ->withSum(['orders as total_spent' => function ($q): void {
                $q->where('status', OrderStatus::Paid);
            }], 'amount');

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        match ($status) {
            'customer' => $query->where('role', UserRole::Customer),
            'admin' => $query->where('role', UserRole::Admin),
            'with_orders' => $query->has('orders'),
            'with_paid_orders' => $query->whereHas('orders', fn ($q) => $q->where('status', OrderStatus::Paid)),
            'no_orders' => $query->doesntHave('orders'),
            default => null,
        };

        match ($sort) {
            'oldest' => $query->oldest('id'),
            'spent_desc' => $query->orderByDesc('total_spent')->latest('id'),
            'orders_desc' => $query->orderByDesc('orders_count')->latest('id'),
            'name_asc' => $query->orderBy('name'),
            default => $query->latest('id'),
        };

        $customers = $query->paginate(15)->withQueryString();

        // Mapear status da newsletter para a lista atual sem N+1
        $customerEmails = $customers->pluck('email')->all();
        $newsletterMap = NewsletterSubscriber::query()
            ->whereIn('email', $customerEmails)
            ->pluck('is_active', 'email')
            ->all();

        $metrics = [
            'total' => User::where('role', UserRole::Customer)->count(),
            'with_orders' => User::where('role', UserRole::Customer)->has('orders')->count(),
            'new_this_month' => User::where('role', UserRole::Customer)
                ->where('created_at', '>=', Carbon::now()->startOfMonth())
                ->count(),
            'total_spent' => (float) Order::where('status', OrderStatus::Paid)->sum('amount'),
        ];

        return view('admin.customers.index', [
            'customers' => $customers,
            'metrics' => $metrics,
            'search' => $search,
            'currentStatus' => $status,
            'currentSort' => $sort,
            'newsletterMap' => $newsletterMap,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', User::class);

        return view('admin.customers.create', [
            'customer' => new User,
            'roles' => UserRole::cases(),
            'isNewsletterSubscribed' => false,
        ]);
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $customer = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cpf' => $validated['cpf'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        if ($request->boolean('subscribe_newsletter')) {
            $this->newsletterService->subscribeCustomer($customer->email);
        }

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Cliente cadastrado com sucesso.');
    }

    public function show(User $customer): View
    {
        Gate::authorize('view', $customer);

        $customer->load(['orders' => function ($q): void {
            $q->with('product')->latest('id');
        }]);

        $paidOrders = $customer->orders->where('status', OrderStatus::Paid);
        $totalSpent = (float) $paidOrders->sum('amount');
        $paidCount = $paidOrders->count();

        $customerStats = [
            'total_orders' => $customer->orders->count(),
            'paid_orders' => $paidCount,
            'total_spent' => $totalSpent,
            'average_ticket' => $paidCount > 0 ? $totalSpent / $paidCount : 0.0,
            'first_order' => $customer->orders->last(),
            'last_order' => $customer->orders->first(),
        ];

        $newsletterSubscriber = NewsletterSubscriber::where('email', $customer->email)->first();

        return view('admin.customers.show', [
            'customer' => $customer,
            'stats' => $customerStats,
            'newsletterSubscriber' => $newsletterSubscriber,
        ]);
    }

    public function edit(User $customer): View
    {
        Gate::authorize('update', $customer);

        $newsletterSubscriber = NewsletterSubscriber::where('email', $customer->email)->first();

        return view('admin.customers.edit', [
            'customer' => $customer,
            'roles' => UserRole::cases(),
            'isNewsletterSubscribed' => (bool) ($newsletterSubscriber?->is_active ?? false),
        ]);
    }

    public function update(UpdateCustomerRequest $request, User $customer): RedirectResponse
    {
        $validated = $request->validated();

        $customer->name = $validated['name'];
        $customer->email = $validated['email'];
        $customer->cpf = $validated['cpf'] ?? null;
        $customer->phone = $validated['phone'] ?? null;
        $customer->role = $validated['role'];

        if (! empty($validated['password'])) {
            $customer->password = Hash::make($validated['password']);
        }

        $customer->save();

        if ($request->boolean('subscribe_newsletter')) {
            $this->newsletterService->subscribeCustomer($customer->email);
        } else {
            $this->newsletterService->unsubscribe($customer->email);
        }

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Dados do cliente atualizados com sucesso.');
    }

    public function destroy(User $customer): RedirectResponse
    {
        Gate::authorize('delete', $customer);

        if ($customer->id === auth()->id()) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Você não pode excluir sua própria conta de administrador.');
        }

        $hasPaidOrders = $customer->orders()->where('status', OrderStatus::Paid)->exists();
        if ($hasPaidOrders) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Não é permitido excluir clientes com histórico financeiro/pedidos pagos. Você pode editar seus dados se necessário.');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Cliente excluído com sucesso.');
    }
}
