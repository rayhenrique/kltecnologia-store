<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Support\Collection;

class CouponUsageService
{
    public function reserve(Coupon $coupon, Order $order): void
    {
        $coupon->incrementUsage();

        $order->update([
            'coupon_id' => $coupon->id,
            'coupon_usage_counted_at' => now(),
            'coupon_usage_released_at' => null,
        ]);
    }

    /**
     * @param  Collection<int, Order>  $orders
     */
    public function release(Collection $orders): void
    {
        $anchor = $orders->first(fn (Order $order): bool => $order->coupon_id !== null
            && $order->coupon_usage_counted_at !== null
            && $order->coupon_usage_released_at === null);

        if (! $anchor) {
            return;
        }

        $coupon = Coupon::withTrashed()->lockForUpdate()->find($anchor->coupon_id);
        if ($coupon && $coupon->times_used > 0) {
            $coupon->decrement('times_used');
        }

        $anchor->update(['coupon_usage_released_at' => now()]);
    }

    /**
     * Reconta uma reserva previamente liberada quando o gateway aprova o pagamento depois.
     *
     * @param  Collection<int, Order>  $orders
     */
    public function restore(Collection $orders): void
    {
        $anchor = $orders->first(fn (Order $order): bool => $order->coupon_id !== null
            && $order->coupon_usage_counted_at !== null
            && $order->coupon_usage_released_at !== null);

        if (! $anchor) {
            return;
        }

        $coupon = Coupon::withTrashed()->lockForUpdate()->find($anchor->coupon_id);
        $coupon?->increment('times_used');
        $anchor->update(['coupon_usage_released_at' => null]);
    }
}
