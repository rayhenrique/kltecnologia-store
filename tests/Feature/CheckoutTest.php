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

    public function test_checkout_page_renders_for_guest(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertOk()
            ->assertSee('Finalizar Compra')
            ->assertSee('Identificação')
            ->assertSee('Criação de Conta')
            ->assertSee('Mercado Pago Oficial');
    }

    public function test_checkout_page_renders_with_direct_product(): void
    {
        $product = Product::factory()->create([
            'title' => 'Script de Automação WhatsApp',
            'is_active' => true,
        ]);

        $response = $this->get(route('checkout.index', ['product' => $product->slug]));

        $response->assertOk()
            ->assertSee($product->title)
            ->assertSee('Finalizar Compra');
    }

    public function test_guest_can_process_checkout_creating_account_and_redirecting_to_mercado_pago(): void
    {
        config(['services.mercado_pago.access_token' => 'TEST-TOKEN']);
        Http::fake(['api.mercadopago.com/*' => Http::response(['id' => 'PREF-GUEST-1', 'init_point' => 'https://mercadopago.test/pay/guest'], 201)]);

        $product = Product::factory()->create(['price' => '49.90', 'is_active' => true]);

        $response = $this->post(route('checkout.process'), [
            'name' => 'Carlos Silva',
            'email' => 'carlos.silva@exemplo.com',
            'cpf' => '123.456.789-00',
            'phone' => '(11) 98888-7777',
            'password' => 'SenhaSegura123!',
            'password_confirmation' => 'SenhaSegura123!',
            'product_id' => $product->id,
        ]);

        $response->assertRedirect('https://mercadopago.test/pay/guest');

        $this->assertDatabaseHas('users', [
            'name' => 'Carlos Silva',
            'email' => 'carlos.silva@exemplo.com',
            'cpf' => '123.456.789-00',
            'phone' => '(11) 98888-7777',
        ]);

        $user = User::where('email', 'carlos.silva@exemplo.com')->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Pending->value,
            'amount' => '49.90',
            'gateway_reference' => 'PREF-GUEST-1',
        ]);
    }

    public function test_authenticated_customer_can_process_checkout_with_multiple_items_and_coupon(): void
    {
        config(['services.mercado_pago.access_token' => 'TEST-TOKEN']);
        Http::fake(['api.mercadopago.com/*' => Http::response(['id' => 'PREF-MULTI-1', 'init_point' => 'https://mercadopago.test/pay/multi'], 201)]);

        $customer = User::factory()->customer()->create([
            'cpf' => '111.222.333-44',
            'phone' => '(82) 99999-1111',
        ]);

        $productA = Product::factory()->create(['price' => '100.00', 'is_active' => true]);
        $productB = Product::factory()->create(['price' => '100.00', 'is_active' => true]);

        $response = $this->actingAs($customer)->post(route('checkout.process'), [
            'items' => [$productA->id, $productB->id],
            'coupon' => 'VIP10',
        ]);

        $response->assertRedirect('https://mercadopago.test/pay/multi');

        // Com 10% de desconto (VIP10), cada produto de 100.00 fica 90.00
        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'product_id' => $productA->id,
            'amount' => '90.00',
            'gateway_reference' => 'PREF-MULTI-1',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'product_id' => $productB->id,
            'amount' => '90.00',
            'gateway_reference' => 'PREF-MULTI-1',
        ]);
    }

    public function test_checkout_validation_requires_products_and_valid_account_data(): void
    {
        $response = $this->post(route('checkout.process'), [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'cpf', 'phone', 'password', 'product_id']);
    }

    public function test_checkout_return_redirects_to_customer_downloads_with_message(): void
    {
        $customer = User::factory()->customer()->create();

        $response = $this->actingAs($customer)->get(route('checkout.return', ['status' => 'success']));

        $response->assertRedirect(route('customer.downloads'))
            ->assertSessionHas('success');
    }

    public function test_guest_can_checkout_free_product_without_mercado_pago_and_without_cpf_phone(): void
    {
        Http::fake();

        $product = Product::factory()->create([
            'price' => '0.00',
            'is_active' => true,
        ]);

        $response = $this->post(route('checkout.process'), [
            'name' => 'Lucas Lead Grátis',
            'email' => 'lucas.lead@exemplo.com',
            'password' => 'SenhaForte123!',
            'password_confirmation' => 'SenhaForte123!',
            'product_id' => $product->id,
            // Notice: cpf and phone are omitted, as they are nullable for free orders
        ]);

        Http::assertNothingSent();

        $response->assertRedirect(route('customer.downloads'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Lucas Lead Grátis',
            'email' => 'lucas.lead@exemplo.com',
        ]);

        $user = User::where('email', 'lucas.lead@exemplo.com')->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Paid->value,
            'amount' => 0,
            'payment_method' => 'free',
        ]);
    }

    public function test_authenticated_customer_starts_free_checkout_via_store_route_without_mercado_pago(): void
    {
        Http::fake();

        $customer = User::factory()->customer()->create();
        $product = Product::factory()->create([
            'price' => '0.00',
            'is_active' => true,
        ]);

        $response = $this->actingAs($customer)->post(route('checkout.store', $product));

        Http::assertNothingSent();

        $response->assertRedirect(route('customer.downloads'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Paid->value,
            'amount' => 0,
            'payment_method' => 'free',
        ]);
    }

    public function test_checkout_with_100_percent_coupon_marks_orders_as_free_and_bypasses_mercado_pago(): void
    {
        Http::fake();

        $customer = User::factory()->customer()->create();
        $product = Product::factory()->create([
            'price' => '89.90',
            'is_active' => true,
        ]);

        $response = $this->actingAs($customer)->post(route('checkout.process'), [
            'product_id' => $product->id,
            'coupon' => 'FREE100',
        ]);

        Http::assertNothingSent();

        $response->assertRedirect(route('customer.downloads'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Paid->value,
            'amount' => '0.00',
            'payment_method' => 'free',
        ]);
    }
}
