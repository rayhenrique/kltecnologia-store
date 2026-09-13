<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    public function __construct(private readonly MercadoPagoService $mercadoPago) {}

    public function handlePayment(string $paymentId): void
    {
        $payment = $this->mercadoPago->getPayment($paymentId);
        $orderId = $this->orderIdFromReference((string) ($payment['external_reference'] ?? ''));

        if (! $orderId) {
            Log::warning('Webhook Mercado Pago sem referência de pedido válida.', ['payment_id' => $paymentId]);

            return;
        }

        DB::transaction(function () use ($orderId, $payment, $paymentId): void {
            $order = Order::query()->lockForUpdate()->find($orderId);
            if (! $order || $order->status === OrderStatus::Paid) {
                return;
            }

            $received = number_format((float) ($payment['transaction_amount'] ?? -1), 2, '.', '');
            $expected = number_format((float) $order->amount, 2, '.', '');
            if ($received !== $expected) {
                Log::warning('Valor divergente em webhook Mercado Pago.', ['order_id' => $orderId]);

                return;
            }

            $status = match ($payment['status'] ?? null) {
                'approved' => OrderStatus::Paid,
                'rejected' => OrderStatus::Failed,
                'cancelled', 'cancelled_by_collector', 'refunded', 'charged_back' => OrderStatus::Canceled,
                default => OrderStatus::Pending,
            };

            $paymentMethod = $payment['payment_type_id'] ?? null;
            if ($order->status === $status
                && $order->gateway_reference === $paymentId
                && $order->payment_method === $paymentMethod) {
                return;
            }

            $order->update([
                'status' => $status,
                'gateway_reference' => $paymentId,
                'payment_method' => $paymentMethod,
            ]);
        });
    }

    private function orderIdFromReference(string $reference): ?int
    {
        return preg_match('/^order:(\d+)$/', $reference, $matches) ? (int) $matches[1] : null;
    }
}
