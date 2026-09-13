<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Product;
use App\Models\User;
use Throwable;

class CheckoutService
{
    public function __construct(private readonly MercadoPagoService $mercadoPago) {}

    public function start(User $user, Product $product): string
    {
        $order = $user->orders()->create([
            'product_id' => $product->id,
            'status' => OrderStatus::Pending,
            'amount' => $product->price,
        ]);

        try {
            $preference = $this->mercadoPago->createPreference($order->load(['product', 'user']));
            $order->update(['gateway_reference' => (string) $preference['id']]);

            return (string) $preference['init_point'];
        } catch (Throwable $exception) {
            $order->update(['status' => OrderStatus::Failed]);
            throw $exception;
        }
    }
}
