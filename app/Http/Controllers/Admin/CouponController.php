<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCouponRequest;
use App\Http\Requests\Admin\UpdateCouponRequest;
use App\Models\Coupon;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Coupon::class);

        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $query = Coupon::query()->with('product')->latest('id');

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', Carbon::now());
                });
        } elseif ($status === 'expired') {
            $query->whereNotNull('expires_at')
                ->where('expires_at', '<', Carbon::now());
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $coupons = $query->paginate(15)->withQueryString();

        $metrics = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_active', true)
                ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', Carbon::now()))
                ->count(),
            'total_uses' => (int) Coupon::sum('times_used'),
        ];

        return view('admin.coupons.index', [
            'coupons' => $coupons,
            'search' => $search,
            'status' => $status,
            'metrics' => $metrics,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Coupon::class);

        $products = Product::query()->where('is_active', true)->orderBy('title')->get();

        return view('admin.coupons.create', [
            'products' => $products,
        ]);
    }

    public function store(StoreCouponRequest $request): RedirectResponse
    {
        Gate::authorize('create', Coupon::class);

        Coupon::create($request->validated());

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cupom de desconto criado com sucesso.');
    }

    public function edit(Coupon $coupon): View
    {
        Gate::authorize('update', $coupon);

        $products = Product::query()->where('is_active', true)->orderBy('title')->get();

        return view('admin.coupons.edit', [
            'coupon' => $coupon,
            'products' => $products,
        ]);
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon): RedirectResponse
    {
        Gate::authorize('update', $coupon);

        $coupon->update($request->validated());

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cupom de desconto atualizado com sucesso.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        Gate::authorize('delete', $coupon);

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cupom de desconto removido com sucesso.');
    }
}
