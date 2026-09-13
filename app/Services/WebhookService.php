<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    public function __construct(
        private readonly MercadoPagoService $mercadoPago,
        private readonly OrderMailService $mailService
    ) {}

    public function handlePayment(string $paymentId): void
    {
        $payment = $this->mercadoPago->getPayment($paymentId);
        $orderIds = $this->orderIdsFromReference((string) ($payment['external_reference'] ?? ''));

        if (empty($orderIds)) {
            Log::warning('Webhook Mercado Pago sem referência de pedido válida.', ['payment_id' => $paymentId]);

            return;
        }

        $paidOrders = null;
        $orderUser = null;

        DB::transaction(function () use ($orderIds, $payment, $paymentId, &$paidOrders, &$orderUser): void {
            $orders = Order::query()->lockForUpdate()->whereIn('id', $orderIds)->with(['user', 'product'])->get();
            if ($orders->isEmpty() || $orders->every(fn ($o) => $o->status === OrderStatus::Paid)) {
                return;
            }

            $received = number_format((float) ($payment['transaction_amount'] ?? -1), 2, '.', '');
            $expected = number_format((float) $orders->sum('amount'), 2, '.', '');
            if ($received !== $expected) {
                Log::warning('Valor divergente em webhook Mercado Pago.', ['order_ids' => $orderIds]);

                return;
            }

            $status = match ($payment['status'] ?? null) {
                'approved' => OrderStatus::Paid,
                'rejected' => OrderStatus::Failed,
                'cancelled', 'cancelled_by_collector', 'refunded', 'charged_back' => OrderStatus::Canceled,
                default => OrderStatus::Pending,
            };

            $paymentMethod = $payment['payment_type_id'] ?? null;

            foreach ($orders as $order) {
                if ($order->status === $status
                    && $order->gateway_reference === $paymentId
                    && $order->payment_method === $paymentMethod) {
                    continue;
                }

                $order->update([
                    'status' => $status,
                    'gateway_reference' => $paymentId,
                    'payment_method' => $paymentMethod,
                ]);
            }

            if ($status === OrderStatus::Paid) {
                $paidOrders = $orders;
                $orderUser = $orders->first()?->user;
            }
        });

        if ($paidOrders !== null && $orderUser !== null) {
            $this->mailService->sendOrderPaidEmail($orderUser, $paidOrders);
        }
    }

    private function orderIdsFromReference(string $reference): array
    {
        if (preg_match('/^orders?:([0-9,]+)$/', $reference, $matches)) {
            return array_map('intval', explode(',', $matches[1]));
        }

        return [];
    }
}
