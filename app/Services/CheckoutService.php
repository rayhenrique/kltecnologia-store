<?php

namespace App\Services;

use App\Enums\OrderStatus;
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
    public function __construct(private readonly MercadoPagoService $mercadoPago) {}

    /**
     * @return array{url: string, is_free: bool}
     */
    public function start(User $user, Product $product): array
    {
        if ((float) $product->price <= 0.0) {
            $user->orders()->create([
                'product_id' => $product->id,
                'status' => OrderStatus::Paid,
                'amount' => '0.00',
                'payment_method' => 'free',
                'gateway_reference' => 'FREE-'.uniqid(),
            ]);

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

        $discountPercent = $this->resolveDiscountPercent($data['coupon'] ?? null);
        $discountMultiplier = (100 - $discountPercent) / 100;

        /** @var list<Order> $orders */
        $orders = [];
        $hasPaidOrder = false;

        foreach ($products as $product) {
            $isProductFree = (float) $product->price <= 0.0;
            $amount = 0.0;

            if (! $isProductFree) {
                $amount = $discountPercent > 0
                    ? max(0.00, round(((float) $product->price) * $discountMultiplier, 2))
                    : (float) $product->price;
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

        // Se nenhum item gerar cobrança (total R$ 0,00), libera imediatamente sem chamar o Mercado Pago
        if (! $hasPaidOrder) {
            return [
                'url' => route('customer.downloads'),
                'is_free' => true,
            ];
        }

        // Caso haja itens pagos, envia somente os pedidos pendentes para o Mercado Pago
        $paidOrders = array_values(array_filter($orders, fn ($o) => $o->status === OrderStatus::Pending));

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
