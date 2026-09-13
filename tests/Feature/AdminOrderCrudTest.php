<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_order_routes(): void
    {
        $order = Order::factory()->create();

        $this->get(route('admin.orders.index'))->assertRedirect(route('login'));
        $this->get(route('admin.orders.create'))->assertRedirect(route('login'));
        $this->get(route('admin.orders.show', $order))->assertRedirect(route('login'));
        $this->get(route('admin.orders.edit', $order))->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_order_routes(): void
    {
        $customer = User::factory()->customer()->create();
        $order = Order::factory()->create();

        $this->actingAs($customer)->get(route('admin.orders.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.orders.create'))->assertForbidden();
        $this->actingAs($customer)->post(route('admin.orders.store'), [
            'user_id' => $customer->id,
            'product_id' => $order->product_id,
            'amount' => '49.90',
            'status' => 'paid',
        ])->assertForbidden();
        $this->actingAs($customer)->get(route('admin.orders.show', $order))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.orders.edit', $order))->assertForbidden();
        $this->actingAs($customer)->put(route('admin.orders.update', $order), [
            'user_id' => $customer->id,
            'product_id' => $order->product_id,
            'amount' => '49.90',
            'status' => 'paid',
        ])->assertForbidden();
        $this->actingAs($customer)->delete(route('admin.orders.destroy', $order))->assertForbidden();
    }

    public function test_admin_can_view_orders_index_with_metrics_and_filters(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->customer()->create(['name' => 'Carlos Silva', 'email' => 'carlos@example.com']);
        $product = Product::factory()->create(['title' => 'Sistema ERP Completo', 'price' => '150.00']);

        $paidOrder = Order::factory()->create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Paid,
            'amount' => '150.00',
            'payment_method' => 'Pix',
            'gateway_reference' => 'PIX-123456',
        ]);

        $pendingOrder = Order::factory()->create([
            'status' => OrderStatus::Pending,
            'amount' => '80.00',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));
        $response->assertOk();
        $response->assertSee('Gerenciador de Pedidos');
        $response->assertSee('#'.$paidOrder->id);
        $response->assertSee('#'.$pendingOrder->id);
        $response->assertSee('Carlos Silva');
        $response->assertSee('Sistema ERP Completo');

        // Test filter by status
        $paidOnlyResponse = $this->actingAs($admin)->get(route('admin.orders.index', ['status' => 'paid']));
        $paidOnlyResponse->assertOk();
        $paidOnlyResponse->assertSee('#'.$paidOrder->id);
        $paidOnlyResponse->assertDontSee('#'.$pendingOrder->id);

        // Test search query
        $searchResponse = $this->actingAs($admin)->get(route('admin.orders.index', ['search' => 'PIX-123456']));
        $searchResponse->assertOk();
        $searchResponse->assertSee('#'.$paidOrder->id);
        $searchResponse->assertDontSee('#'.$pendingOrder->id);
    }

    public function test_admin_can_view_order_show_page(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->customer()->create([
            'name' => 'Mariana Souza',
            'email' => 'mariana@example.com',
            'cpf' => '123.456.789-00',
            'phone' => '(82) 99999-8888',
        ]);
        $product = Product::factory()->create([
            'title' => 'Script de Automação WhatsApp',
            'price' => '99.90',
        ]);

        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'amount' => '99.90',
            'status' => OrderStatus::Paid,
            'payment_method' => 'Cartão de Crédito',
            'gateway_reference' => 'MP-88776655',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.show', $order));
        $response->assertOk();
        $response->assertSee('Pedido #'.$order->id);
        $response->assertSee('Mariana Souza');
        $response->assertSee('mariana@example.com');
        $response->assertSee('Script de Automação WhatsApp');
        $response->assertSee('MP-88776655');
    }

    public function test_admin_can_view_create_page_and_store_new_order(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->customer()->create();
        $product = Product::factory()->create(['price' => '120.00']);

        $response = $this->actingAs($admin)->get(route('admin.orders.create'));
        $response->assertOk();
        $response->assertSee('Cadastrar Novo Pedido');

        $storeResponse = $this->actingAs($admin)->post(route('admin.orders.store'), [
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'amount' => '120.00',
            'status' => 'paid',
            'payment_method' => 'Pix Direto',
            'gateway_reference' => 'PIX-MANUAL-001',
        ]);

        $order = Order::query()->latest('id')->first();
        $this->assertNotNull($order);
        $this->assertSame($customer->id, $order->user_id);
        $this->assertSame($product->id, $order->product_id);
        $this->assertSame('120.00', $order->amount);
        $this->assertSame(OrderStatus::Paid, $order->status);
        $this->assertSame('Pix Direto', $order->payment_method);
        $this->assertSame('PIX-MANUAL-001', $order->gateway_reference);

        $storeResponse->assertRedirect(route('admin.orders.show', $order));
        $storeResponse->assertSessionHas('success');
    }

    public function test_store_order_validation_fails_with_invalid_data(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.orders.store'), [
            'user_id' => '',
            'product_id' => '',
            'amount' => 'invalido',
            'status' => 'status_inexistente',
        ]);

        $response->assertSessionHasErrors(['user_id', 'product_id', 'amount', 'status']);
    }

    public function test_admin_can_update_an_order_and_change_status(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->customer()->create();
        $product = Product::factory()->create(['price' => '75.00']);

        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'amount' => '75.00',
            'status' => OrderStatus::Pending,
        ]);

        $editResponse = $this->actingAs($admin)->get(route('admin.orders.edit', $order));
        $editResponse->assertOk();
        $editResponse->assertSee('Editar Pedido #'.$order->id);

        $updateResponse = $this->actingAs($admin)->put(route('admin.orders.update', $order), [
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'amount' => '70.00',
            'status' => 'paid',
            'payment_method' => 'Transferência Bancária',
            'gateway_reference' => 'TRANSF-OK-99',
        ]);

        $updateResponse->assertRedirect(route('admin.orders.show', $order));
        $updateResponse->assertSessionHas('success');

        $order->refresh();
        $this->assertSame(OrderStatus::Paid, $order->status);
        $this->assertSame('70.00', $order->amount);
        $this->assertSame('Transferência Bancária', $order->payment_method);
        $this->assertSame('TRANSF-OK-99', $order->gateway_reference);
    }

    public function test_admin_can_delete_an_order(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.orders.destroy', $order));
        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
