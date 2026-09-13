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
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Throwable;

class CheckoutService
{
    public function __construct(
        private readonly MercadoPagoService $mercadoPago,
        private readonly OrderMailService $mailService
    ) {}

    /**
     * @return array{url: string, is_free: bool}
     */
    public function start(User $user, Product $product): array
    {
        $isFirstPurchase = ($user->orders()->count() === 0);

        if ((float) $product->price <= 0.0) {
            $order = $user->orders()->create([
                'product_id' => $product->id,
                'status' => OrderStatus::Paid,
                'amount' => '0.00',
                'payment_method' => 'free',
                'gateway_reference' => 'FREE-'.uniqid(),
            ]);

            if ($isFirstPurchase) {
                $this->mailService->sendWelcomeEmail($user);
            }
            $this->mailService->sendOrderPaidEmail($user, $order);

            return [
                'url' => route('customer.downloads'),
                'is_free' => true,
            ];
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

            return [
                'url' => (string) $preference['init_point'],
                'is_free' => false,
            ];
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
        $user = $currentUser;
        $isFirstPurchase = ($user === null) || ($user->orders()->count() === 0);

        if ($user === null) {
            $user = User::create([
                'name' => (string) $data['name'],
                'email' => (string) $data['email'],
                'cpf' => $data['cpf'] ?? null,
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make((string) $data['password']),
            ]);

            event(new Registered($user));
            Auth::login($user);
        } else {
            $updates = [];
            if (filled($data['cpf'] ?? null) && $data['cpf'] !== $user->cpf) {
                $updates['cpf'] = $data['cpf'];
            }
            if (filled($data['phone'] ?? null) && $data['phone'] !== $user->phone) {
                $updates['phone'] = $data['phone'];
            }
            if (! empty($updates)) {
                $user->update($updates);
            }
        }

        $products = $this->resolveProducts($data);
        if ($products->isEmpty()) {
            throw new RuntimeException('Nenhum produto válido encontrado para compra.');
        }

        $subtotal = (float) $products->sum('price');
        $couponCode = strtoupper(trim((string) ($data['coupon'] ?? '')));
        $couponModel = null;
        $legacyDiscountPercent = 0;

        if ($couponCode !== '') {
            $foundCoupon = Coupon::where('code', $couponCode)->first();
            if ($foundCoupon) {
                $eval = $foundCoupon->evaluate($products, $subtotal);
                if ($eval['valid']) {
                    $couponModel = $foundCoupon;
                }
            } else {
                $legacyDiscountPercent = $this->resolveDiscountPercent($couponCode);
            }
        }

        $remainingFixedDiscount = 0.0;
        if ($couponModel && $couponModel->discount_type === 'fixed' && $couponModel->isApplicableToStorewide()) {
            $remainingFixedDiscount = (float) $couponModel->discount_value;
        }

        /** @var list<Order> $orders */
        $orders = [];
        $hasPaidOrder = false;

        foreach ($products as $product) {
            $isProductFree = (float) $product->price <= 0.0;
            $amount = 0.0;

            if (! $isProductFree) {
                if ($couponModel) {
                    if ($couponModel->product_id !== null) {
                        if ($couponModel->product_id === $product->id) {
                            if ($couponModel->discount_type === 'percentage') {
                                $discountMultiplier = (100 - (float) $couponModel->discount_value) / 100;
                                $amount = max(0.00, round(((float) $product->price) * $discountMultiplier, 2));
                            } else {
                                $amount = max(0.00, round(((float) $product->price) - (float) $couponModel->discount_value, 2));
                            }
                        } else {
                            $amount = (float) $product->price;
                        }
                    } else {
                        if ($couponModel->discount_type === 'percentage') {
                            $discountMultiplier = (100 - (float) $couponModel->discount_value) / 100;
                            $amount = max(0.00, round(((float) $product->price) * $discountMultiplier, 2));
                        } else {
                            $deduct = min((float) $product->price, $remainingFixedDiscount);
                            $amount = max(0.00, round(((float) $product->price) - $deduct, 2));
                            $remainingFixedDiscount = max(0.00, $remainingFixedDiscount - $deduct);
                        }
                    }
                } elseif ($legacyDiscountPercent > 0) {
                    $discountMultiplier = (100 - $legacyDiscountPercent) / 100;
                    $amount = max(0.00, round(((float) $product->price) * $discountMultiplier, 2));
                } else {
                    $amount = (float) $product->price;
                }
            }

            if ($amount > 0.0) {
                $hasPaidOrder = true;
                $orders[] = $user->orders()->create([
                    'product_id' => $product->id,
                    'status' => OrderStatus::Pending,
                    'amount' => $amount,
                ]);
            } else {
                $orders[] = $user->orders()->create([
                    'product_id' => $product->id,
                    'status' => OrderStatus::Paid,
                    'amount' => '0.00',
                    'payment_method' => 'free',
                    'gateway_reference' => 'FREE-'.uniqid(),
                ]);
            }
        }

        if ($couponModel) {
            $couponModel->incrementUsage();
        }

        if ($isFirstPurchase) {
            $this->mailService->sendWelcomeEmail($user);
        }

        // Se nenhum item gerar cobrança (total R$ 0,00), libera imediatamente sem chamar o Mercado Pago
        if (! $hasPaidOrder) {
            $this->mailService->sendOrderPaidEmail($user, $orders);

            return [
                'url' => route('customer.downloads'),
                'is_free' => true,
            ];
        }

        // Caso haja itens pagos, envia somente os pedidos pendentes para o Mercado Pago
        $paidOrders = array_values(array_filter($orders, fn ($o) => $o->status === OrderStatus::Pending));
        $freeOrders = array_values(array_filter($orders, fn ($o) => $o->status === OrderStatus::Paid));
        if (! empty($freeOrders)) {
            $this->mailService->sendOrderPaidEmail($user, $freeOrders);
        }

        try {
            foreach ($paidOrders as $order) {
                $order->setRelation('product', $products->firstWhere('id', $order->product_id));
                $order->setRelation('user', $user);
            }

            $preference = $this->mercadoPago->createPreferenceForOrders($paidOrders, $user);
            $gatewayReference = (string) $preference['id'];

            foreach ($paidOrders as $order) {
                $order->update(['gateway_reference' => $gatewayReference]);
            }

            $this->mailService->sendOrderPendingEmail($user, $paidOrders);

            return [
                'url' => (string) $preference['init_point'],
                'is_free' => false,
            ];
        } catch (Throwable $exception) {
            foreach ($paidOrders as $order) {
                $order->update(['status' => OrderStatus::Failed]);
            }

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return Collection<int, Product>
     */
    private function resolveProducts(array $data): Collection
    {
        $productIds = [];

        if (filled($data['product_id'] ?? null)) {
            $productIds[] = (int) $data['product_id'];
        }

        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                if (is_numeric($item)) {
                    $productIds[] = (int) $item;
                }
            }
        }

        $productIds = array_values(array_unique($productIds));
        if (empty($productIds)) {
            return collect();
        }

        return Product::whereIn('id', $productIds)->where('is_active', true)->get();
    }

    private function resolveDiscountPercent(?string $coupon): int
    {
        $code = strtoupper(trim((string) $coupon));
        if ($code === 'FREE100' || $code === 'GRATIS100') {
            return 100;
        }
        if ($code === 'VIP10' || $code === 'KL10') {
            return 10;
        }
        if ($code === 'KL2026' || $code === 'PROMO15') {
            return 15;
        }

        return 0;
    }
}
