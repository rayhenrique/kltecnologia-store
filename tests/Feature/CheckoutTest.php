<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_customer_starts_checkout(): void
    {
        config(['services.mercado_pago.access_token' => 'TEST-TOKEN']);
        Http::fake(['api.mercadopago.com/*' => Http::response(['id' => 'PREF-123', 'init_point' => 'https://mercadopago.test/pay'], 201)]);
        $customer = User::factory()->customer()->create();
        $product = Product::factory()->create(['price' => '39.90', 'is_active' => true]);

        $this->actingAs($customer)->post(route('checkout.store', $product))->assertRedirect('https://mercadopago.test/pay');
        $this->assertDatabaseHas('orders', ['user_id' => $customer->id, 'product_id' => $product->id, 'status' => OrderStatus::Pending->value, 'amount' => '39.90', 'gateway_reference' => 'PREF-123']);
        Http::assertSent(fn ($request) => $request->hasHeader('X-Idempotency-Key') && $request['external_reference'] === 'order:1');
    }

    public function test_inactive_product_cannot_be_purchased(): void
    {
        $product = Product::factory()->inactive()->create();
        $this->actingAs(User::factory()->customer()->create())->post(route('checkout.store', $product))->assertNotFound();
        $this->assertDatabaseCount('orders', 0);
    }
}
