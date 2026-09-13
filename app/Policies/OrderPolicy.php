<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function download(User $user, Order $order): bool
    {
        return $order->user_id === $user->id && $order->status === OrderStatus::Paid;
    }
}
