<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MercadoPagoService
{
    public function createPreference(Order $order): array
    {
        return $this->createPreferenceForOrders([$order]);
    }

    /**
     * @param  iterable<Order>  $orders
     */
    public function createPreferenceForOrders(iterable $orders, ?User $user = null): array
    {
        $ordersList = is_array($orders) ? $orders : iterator_to_array($orders);
        if (empty($ordersList)) {
            throw new RuntimeException('Nenhum pedido fornecido para gerar preferência de pagamento.');
        }

        $firstOrder = $ordersList[0];
        $payerUser = $user ?? $firstOrder->user;

        $nameParts = explode(' ', trim((string) $payerUser->name), 2);
        $firstName = $nameParts[0] ?? (string) $payerUser->name;
        $lastName = $nameParts[1] ?? '';

        $payer = [
            'name' => $firstName,
            'surname' => $lastName,
            'email' => (string) $payerUser->email,
        ];

        $cleanPhone = (string) $payerUser->clean_phone;
        if ($cleanPhone !== '') {
            if (strlen($cleanPhone) >= 10) {
                $payer['phone'] = [
                    'area_code' => substr($cleanPhone, 0, 2),
                    'number' => substr($cleanPhone, 2),
                ];
            } else {
                $payer['phone'] = [
                    'area_code' => '',
                    'number' => $cleanPhone,
                ];
            }
        }

        $cleanCpf = (string) $payerUser->clean_cpf;
        if ($cleanCpf !== '') {
            $payer['identification'] = [
                'type' => strlen($cleanCpf) > 11 ? 'CNPJ' : 'CPF',
                'number' => $cleanCpf,
            ];
        }

        if ($payerUser->created_at) {
            $payer['date_created'] = $payerUser->created_at->toIso8601String();
        }

        $items = [];
        $orderIds = [];
        foreach ($ordersList as $order) {
            $orderIds[] = $order->id;
            $item = [
                'id' => (string) $order->product_id,
                'title' => $order->product->title,
                'description' => str($order->product->description)->limit(250)->toString(),
                'quantity' => 1,
                'currency_id' => 'BRL',
                'unit_price' => (float) $order->amount,
                'category_id' => 'software',
            ];

            if ($order->product->cover_path) {
                $item['picture_url'] = url($order->product->cover_path);
            }

            $items[] = $item;
        }

        $externalReference = count($orderIds) === 1 ? 'order:'.$orderIds[0] : 'orders:'.implode(',', $orderIds);
        $idempotencyKey = 'checkout-orders-'.implode('-', $orderIds);

        $payload = [
            'items' => $items,
            'payer' => $payer,
            'external_reference' => $externalReference,
            'statement_descriptor' => 'KL TECNOLOGIA',
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
            ->withHeader('X-Idempotency-Key', $idempotencyKey)
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
