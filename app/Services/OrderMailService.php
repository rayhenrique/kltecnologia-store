<?php

namespace App\Services;

use App\Mail\OrderPaidMail;
use App\Mail\OrderPendingMail;
use App\Mail\WelcomeCustomerMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OrderMailService
{
    /**
     * Dispara e-mail de boas-vindas ao novo cliente / primeira compra.
     */
    public function sendWelcomeEmail(User $user): bool
    {
        if (empty($user->email)) {
            return false;
        }

        try {
            Mail::to($user->email)->send(new WelcomeCustomerMail($user));

            Log::info('E-mail de boas-vindas enviado ao cliente com sucesso.', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return true;
        } catch (Throwable $exception) {
            Log::error('Erro ao enviar e-mail de boas-vindas ao cliente.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Dispara e-mail de pendência de pagamento (PIX / Boleto / Cartão em análise).
     *
     * @param  Collection<int, Order>|array<int, Order>|Order  $orders
     */
    public function sendOrderPendingEmail(User $user, Collection|array|Order $orders): bool
    {
        if (empty($user->email)) {
            return false;
        }

        $orderCollection = $this->normalizeOrders($orders);
        if ($orderCollection->isEmpty()) {
            return false;
        }

        try {
            Mail::to($user->email)->send(new OrderPendingMail($user, $orderCollection));

            Log::info('E-mail de pedido pendente enviado com sucesso.', [
                'user_id' => $user->id,
                'order_ids' => $orderCollection->pluck('id')->all(),
            ]);

            return true;
        } catch (Throwable $exception) {
            Log::error('Erro ao enviar e-mail de pedido pendente ao cliente.', [
                'user_id' => $user->id,
                'order_ids' => $orderCollection->pluck('id')->all(),
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Dispara e-mail de confirmação de compra / pagamento aprovado / liberação de downloads.
     *
     * @param  Collection<int, Order>|array<int, Order>|Order  $orders
     */
    public function sendOrderPaidEmail(User $user, Collection|array|Order $orders): bool
    {
        if (empty($user->email)) {
            return false;
        }

        $orderCollection = $this->normalizeOrders($orders);
        if ($orderCollection->isEmpty()) {
            return false;
        }

        try {
            Mail::to($user->email)->send(new OrderPaidMail($user, $orderCollection));

            Log::info('E-mail de pagamento confirmado enviado com sucesso.', [
                'user_id' => $user->id,
                'order_ids' => $orderCollection->pluck('id')->all(),
            ]);

            return true;
        } catch (Throwable $exception) {
            Log::error('Erro ao enviar e-mail de pagamento confirmado ao cliente.', [
                'user_id' => $user->id,
                'order_ids' => $orderCollection->pluck('id')->all(),
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * @param  Collection<int, Order>|array<int, Order>|Order  $orders
     * @return Collection<int, Order>
     */
    private function normalizeOrders(Collection|array|Order $orders): Collection
    {
        $collection = $orders instanceof Collection
            ? $orders
            : collect(is_array($orders) ? $orders : [$orders]);

        return $collection->map(function ($order) {
            if ($order instanceof Order && ! $order->relationLoaded('product')) {
                $order->load('product');
            }

            return $order;
        });
    }
}
