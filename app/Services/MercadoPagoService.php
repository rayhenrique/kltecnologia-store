<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MercadoPagoService
{
    public function createPreference(Order $order): array
    {
        $payload = [
            'items' => [[
                'id' => (string) $order->product_id,
                'title' => $order->product->title,
                'description' => str($order->product->description)->limit(250)->toString(),
                'quantity' => 1,
                'currency_id' => 'BRL',
                'unit_price' => (float) $order->amount,
            ]],
            'payer' => ['email' => $order->user->email],
            'external_reference' => 'order:'.$order->id,
            'back_urls' => [
                'success' => route('checkout.return', ['status' => 'success']),
                'failure' => route('checkout.return', ['status' => 'failure']),
                'pending' => route('checkout.return', ['status' => 'pending']),
            ],
            'auto_return' => 'approved',
        ];

        if (filled(config('services.mercado_pago.webhook_url'))) {
            $payload['notification_url'] = config('services.mercado_pago.webhook_url');
        }

        $response = $this->client()
            ->withHeader('X-Idempotency-Key', 'checkout-order-'.$order->id)
            ->post('/checkout/preferences', $payload)
            ->throw()
            ->json();

        if (! isset($response['id'], $response['init_point'])) {
            throw new RuntimeException('O Mercado Pago retornou uma preferência incompleta.');
        }

        return $response;
    }

    public function getPayment(string $paymentId): array
    {
        return $this->client()->get('/v1/payments/'.$paymentId)->throw()->json();
    }

    private function client(): PendingRequest
    {
        $token = (string) config('services.mercado_pago.access_token');
        if ($token === '') {
            throw new RuntimeException('MERCADO_PAGO_ACCESS_TOKEN não está configurado.');
        }

        return Http::baseUrl((string) config('services.mercado_pago.base_url'))
            ->withToken($token)
            ->acceptJson()
            ->asJson()
            ->timeout(15);
    }
}
