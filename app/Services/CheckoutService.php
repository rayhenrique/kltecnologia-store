<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Throwable;

class CheckoutService
{
    public function __construct(
        private readonly MercadoPagoService $mercadoPago,
        private readonly OrderMailService $mailService,
        private readonly CouponUsageService $couponUsage,
        private readonly NewsletterService $newsletterService,
    ) {}

    /**
     * @return array{url: string, is_free: bool}
     */
    public function start(User $user, Product $product): array
    {
        if (! $product->is_active || blank($product->file_path)) {
            throw new RuntimeException('Este produto ainda não está disponível para compra.');
        }

        $isFirstPurchase = ! $user->orders()->exists();
        $this->newsletterService->subscribeCustomer($user->email, request()->ip(), request()->userAgent());

        if ((float) $product->price <= 0.0) {
            $order = $user->orders()->create([
                'product_id' => $product->id,
                'status' => OrderStatus::Paid,
                'amount' => '0.00',
                'payment_method' => 'free',
                'gateway_reference' => 'FREE-'.str()->uuid(),
            ]);

            if ($isFirstPurchase) {
                $this->mailService->sendWelcomeEmail($user);
            }
            $this->mailService->sendOrderPaidEmail($user, $order);

            return ['url' => route('customer.downloads'), 'is_free' => true];
        }

        $order = $user->orders()->create([
            'product_id' => $product->id,
            'status' => OrderStatus::Pending,
            'amount' => $product->price,
        ]);

        try {
            $preference = $this->mercadoPago->createPreference($order->load(['product', 'user']));
            $order->update(['gateway_reference' => (string) $preference['id']]);

            if ($isFirstPurchase) {
                $this->mailService->sendWelcomeEmail($user);
            }
            $this->mailService->sendOrderPendingEmail($user, $order);

            return ['url' => (string) $preference['init_point'], 'is_free' => false];
        } catch (Throwable $exception) {
            $order->update(['status' => OrderStatus::Failed]);

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{url: string, is_free: bool}
     */
    public function process(array $data, ?User $currentUser): array
    {
        $productIds = $this->productIdsFromData($data);
        if ($productIds === []) {
            throw new RuntimeException('Nenhum produto válido encontrado para compra.');
        }

        /**
         * @var array{user: User, user_created: bool, first_purchase: bool, products: Collection<int, Product>, orders: Collection<int, Order>, has_paid_order: bool} $checkout
         */
        $checkout = DB::transaction(function () use ($data, $currentUser, $productIds): array {
            $products = Product::query()
                ->whereIn('id', $productIds)
                ->availableForSale()
                ->lockForUpdate()
                ->get();

            if ($products->count() !== count($productIds)) {
                throw new RuntimeException('Um ou mais produtos não estão disponíveis para compra.');
            }

            $userCreated = $currentUser === null;
            $user = $currentUser ?? User::create([
                'name' => (string) $data['name'],
                'email' => (string) $data['email'],
                'cpf' => $data['cpf'] ?? null,
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make((string) $data['password']),
            ]);

            $isFirstPurchase = ! $user->orders()->exists();
            $this->updateCustomerContact($user, $data);

            $coupon = $this->resolveCoupon($data, $products);
            [$orders, $hasPaidOrder] = $this->createOrders($user, $products, $coupon);

            if ($coupon) {
                $couponAnchor = $orders->first(fn (Order $order): bool => $order->status === OrderStatus::Pending)
                    ?? $orders->firstOrFail();
                $this->couponUsage->reserve($coupon, $couponAnchor);
            }

            return [
                'user' => $user,
                'user_created' => $userCreated,
                'first_purchase' => $isFirstPurchase,
                'products' => $products,
                'orders' => $orders,
                'has_paid_order' => $hasPaidOrder,
            ];
        }, 3);

        $user = $checkout['user'];
        $orders = $checkout['orders'];
        $products = $checkout['products'];

        if ($checkout['user_created']) {
            event(new Registered($user));
            Auth::login($user);
        }

        $this->newsletterService->subscribeCustomer($user->email, request()->ip(), request()->userAgent());

        if ($checkout['first_purchase']) {
            $this->mailService->sendWelcomeEmail($user);
        }

        if (! $checkout['has_paid_order']) {
            $this->mailService->sendOrderPaidEmail($user, $orders);

            return ['url' => route('customer.downloads'), 'is_free' => true];
        }

        $paidOrders = $orders->filter(fn (Order $order): bool => $order->status === OrderStatus::Pending)->values();
        $freeOrders = $orders->filter(fn (Order $order): bool => $order->status === OrderStatus::Paid)->values();

        if ($freeOrders->isNotEmpty()) {
            $this->mailService->sendOrderPaidEmail($user, $freeOrders);
        }

        try {
            foreach ($paidOrders as $order) {
                $order->setRelation('product', $products->firstWhere('id', $order->product_id));
                $order->setRelation('user', $user);
            }

            $preference = $this->mercadoPago->createPreferenceForOrders($paidOrders->all(), $user);
            $gatewayReference = (string) $preference['id'];

            DB::transaction(function () use ($paidOrders, $gatewayReference): void {
                Order::query()
                    ->whereKey($paidOrders->pluck('id'))
                    ->update(['gateway_reference' => $gatewayReference]);
            });

            $this->mailService->sendOrderPendingEmail($user, $paidOrders);

            return ['url' => (string) $preference['init_point'], 'is_free' => false];
        } catch (Throwable $exception) {
            DB::transaction(function () use ($paidOrders): void {
                $lockedOrders = Order::query()
                    ->whereKey($paidOrders->pluck('id'))
                    ->lockForUpdate()
                    ->get();

                Order::query()->whereKey($lockedOrders->pluck('id'))->update(['status' => OrderStatus::Failed]);
                $this->couponUsage->release($lockedOrders);
            }, 3);

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function updateCustomerContact(User $user, array $data): void
    {
        $updates = [];
        if (filled($data['cpf'] ?? null) && $data['cpf'] !== $user->cpf) {
            $updates['cpf'] = $data['cpf'];
        }
        if (filled($data['phone'] ?? null) && $data['phone'] !== $user->phone) {
            $updates['phone'] = $data['phone'];
        }

        if ($updates !== []) {
            $user->update($updates);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  Collection<int, Product>  $products
     */
    private function resolveCoupon(array $data, Collection $products): ?Coupon
    {
        $code = strtoupper(trim((string) ($data['coupon'] ?? '')));
        if ($code === '') {
            return null;
        }

        $coupon = Coupon::query()->where('code', $code)->lockForUpdate()->first();
        if (! $coupon) {
            throw new RuntimeException('Cupom inválido ou expirado.');
        }

        $evaluation = $coupon->evaluate($products, (float) $products->sum('price'));
        if (! $evaluation['valid']) {
            throw new RuntimeException($evaluation['message']);
        }

        return $coupon;
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return array{Collection<int, Order>, bool}
     */
    private function createOrders(User $user, Collection $products, ?Coupon $coupon): array
    {
        $remainingFixedDiscount = $coupon
            && $coupon->discount_type === 'fixed'
            && $coupon->isApplicableToStorewide()
                ? (float) $coupon->discount_value
                : 0.0;

        $orders = collect();
        $hasPaidOrder = false;

        foreach ($products as $product) {
            $amount = (float) $product->price;

            if ($coupon && $amount > 0.0) {
                if ($coupon->product_id !== null && $coupon->product_id !== $product->id) {
                    // O produto não participa deste cupom específico.
                } elseif ($coupon->discount_type === 'percentage') {
                    $amount = max(0.0, round($amount * ((100 - (float) $coupon->discount_value) / 100), 2));
                } else {
                    $deduction = $coupon->isApplicableToStorewide()
                        ? min($amount, $remainingFixedDiscount)
                        : min($amount, (float) $coupon->discount_value);
                    $amount = max(0.0, round($amount - $deduction, 2));
                    $remainingFixedDiscount = max(0.0, $remainingFixedDiscount - $deduction);
                }
            }

            $isPaid = $amount <= 0.0;
            $hasPaidOrder = $hasPaidOrder || ! $isPaid;

            $orders->push($user->orders()->create([
                'product_id' => $product->id,
                'status' => $isPaid ? OrderStatus::Paid : OrderStatus::Pending,
                'amount' => number_format($amount, 2, '.', ''),
                'payment_method' => $isPaid ? 'free' : null,
                'gateway_reference' => $isPaid ? 'FREE-'.str()->uuid() : null,
            ]));
        }

        return [$orders, $hasPaidOrder];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return list<int>
     */
    private function productIdsFromData(array $data): array
    {
        $ids = [];

        if (filled($data['product_id'] ?? null)) {
            $ids[] = (int) $data['product_id'];
        }

        foreach ((array) ($data['items'] ?? []) as $item) {
            if (is_numeric($item)) {
                $ids[] = (int) $item;
            }
        }

        return array_values(array_unique(array_filter($ids)));
    }
}
