<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class DownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_paid_order_owner_can_download_private_file(): void
    {
        Storage::fake('digital_products');
        $order = Order::factory()->paid()->create();
        Storage::disk('digital_products')->put($order->product->file_path, 'conteudo-protegido');
        $url = URL::temporarySignedRoute('customer.download', now()->addMinute(), ['order' => $order]);
        $this->actingAs($order->user)->get($url)->assertOk()->assertHeader('content-disposition');
    }

    public function test_download_is_blocked_without_payment(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::Pending]);
        $url = URL::temporarySignedRoute('customer.download', now()->addMinute(), ['order' => $order]);
        $this->actingAs($order->user)->get($url)->assertForbidden();
    }

    public function test_download_is_blocked_for_another_customer_and_without_signature(): void
    {
        $order = Order::factory()->paid()->create();
        $other = User::factory()->customer()->create();
        $url = URL::temporarySignedRoute('customer.download', now()->addMinute(), ['order' => $order]);
        $this->actingAs($other)->get($url)->assertForbidden();
        $this->actingAs($order->user)->get(route('customer.download', $order))->assertForbidden();
    }
}
