<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;

class CustomerDownloadService
{
    public function for(User $user): LengthAwarePaginator
    {
        $orders = $user->orders()->with('product')->where('status', OrderStatus::Paid)->latest()->paginate(12);

        return $orders->through(fn ($order): array => [
            'order' => $order,
            'download_url' => URL::temporarySignedRoute('customer.download', now()->addMinutes(10), ['order' => $order]),
        ]);
    }
}
