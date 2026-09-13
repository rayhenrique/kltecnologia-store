<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_signed_webhook_marks_matching_order_as_paid_idempotently(): void
    {
        config(['services.mercado_pago.access_token' => 'TEST-TOKEN', 'services.mercado_pago.webhook_secret' => 'webhook-secret']);
        $order = Order::factory()->create(['status' => OrderStatus::Pending, 'amount' => '89.90']);
        Http::fake(['api.mercadopago.com/v1/payments/PAY123' => Http::response(['id' => 123, 'status' => 'approved', 'external_reference' => 'order:'.$order->id, 'transaction_amount' => 89.90, 'payment_type_id' => 'credit_card'])]);
        $headers = $this->signedHeaders('PAY123');
        $payload = ['type' => 'payment', 'action' => 'payment.updated', 'data' => ['id' => 'PAY123']];

        $this->withHeaders($headers)->postJson('/webhooks/mercado-pago?data.id=PAY123&type=payment', $payload)->assertOk();
        $firstUpdatedAt = $order->fresh()->updated_at;
        $this->withHeaders($headers)->postJson('/webhooks/mercado-pago?data.id=PAY123&type=payment', $payload)->assertOk();

        $order->refresh();
        $this->assertSame(OrderStatus::Paid, $order->status);
        $this->assertSame('PAY123', $order->gateway_reference);
        $this->assertSame('credit_card', $order->payment_method);
        $this->assertTrue($firstUpdatedAt->equalTo($order->updated_at));
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        config(['services.mercado_pago.webhook_secret' => 'webhook-secret']);
        $this->withHeaders(['x-signature' => 'ts=1,v1=invalid', 'x-request-id' => 'request-123'])
            ->postJson('/webhooks/mercado-pago?data.id=PAY123', ['type' => 'payment', 'data' => ['id' => 'PAY123']])
            ->assertUnauthorized();
    }

    private function signedHeaders(string $dataId): array
    {
        $timestamp = '1742505638683';
        $requestId = 'request-123';
        $manifest = 'id:'.strtolower($dataId).';request-id:'.$requestId.';ts:'.$timestamp.';';

        return ['x-signature' => 'ts='.$timestamp.',v1='.hash_hmac('sha256', $manifest, 'webhook-secret'), 'x-request-id' => $requestId];
    }
}
