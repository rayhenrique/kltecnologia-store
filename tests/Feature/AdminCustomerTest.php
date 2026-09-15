<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCustomerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->customer = User::factory()->customer()->create();
    }

    public function test_guest_cannot_access_customer_admin_routes(): void
    {
        $this->get(route('admin.customers.index'))->assertRedirect(route('login'));
        $this->get(route('admin.customers.create'))->assertRedirect(route('login'));
        $this->get(route('admin.customers.show', $this->customer))->assertRedirect(route('login'));
        $this->get(route('admin.customers.edit', $this->customer))->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_customer_admin_routes(): void
    {
        $this->actingAs($this->customer)->get(route('admin.customers.index'))->assertForbidden();
        $this->actingAs($this->customer)->get(route('admin.customers.create'))->assertForbidden();
        $this->actingAs($this->customer)->get(route('admin.customers.show', $this->customer))->assertForbidden();
        $this->actingAs($this->customer)->get(route('admin.customers.edit', $this->customer))->assertForbidden();
    }

    public function test_admin_can_view_customers_list_and_metrics(): void
    {
        $c1 = User::factory()->customer()->create(['name' => 'Ana Paula', 'email' => 'ana@example.com']);
        $product = Product::factory()->create(['price' => '100.00', 'is_active' => true]);

        Order::create([
            'user_id' => $c1->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Paid,
            'amount' => '100.00',
            'gateway_reference' => 'TEST-123',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.customers.index'));

        $response->assertOk()
            ->assertSee('Gerenciador de Clientes')
            ->assertSee('Ana Paula')
            ->assertSee('ana@example.com')
            ->assertSee('R$ 100,00');
    }

    public function test_admin_can_search_customers_by_name_and_email(): void
    {
        User::factory()->customer()->create(['name' => 'Roberto Carlos', 'email' => 'roberto@example.com']);
        User::factory()->customer()->create(['name' => 'Fernanda Lima', 'email' => 'fernanda@example.com']);

        $response = $this->actingAs($this->admin)->get(route('admin.customers.index', ['q' => 'Roberto']));

        $response->assertOk()
            ->assertSee('Roberto Carlos')
            ->assertDontSee('Fernanda Lima');
    }

    public function test_admin_can_filter_customers_by_status(): void
    {
        $customerWithOrder = User::factory()->customer()->create(['name' => 'Comprador Ativo']);
        $customerWithoutOrder = User::factory()->customer()->create(['name' => 'Sem Compras']);
        $product = Product::factory()->create(['price' => '50.00']);

        Order::create([
            'user_id' => $customerWithOrder->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Paid,
            'amount' => '50.00',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.customers.index', ['status' => 'with_paid_orders']));

        $response->assertOk()
            ->assertSee('Comprador Ativo')
            ->assertDontSee('Sem Compras');
    }

    public function test_admin_can_view_customer_details_with_order_history(): void
    {
        $customer = User::factory()->customer()->create([
            'name' => 'Lucas Mendes',
            'email' => 'lucas@example.com',
            'phone' => '(11) 98888-7777',
            'cpf' => '123.456.789-00',
        ]);

        $product = Product::factory()->create(['title' => 'Sistema ERP Laravel', 'price' => '150.00']);

        $order = Order::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Paid,
            'amount' => '150.00',
            'gateway_reference' => 'ERP-123',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.customers.show', $customer));

        $response->assertOk()
            ->assertSee('Lucas Mendes')
            ->assertSee('lucas@example.com')
            ->assertSee('123.456.789-00')
            ->assertSee('(11) 98888-7777')
            ->assertSee('Sistema ERP Laravel')
            ->assertSee('#'.$order->id)
            ->assertSee('R$ 150,00');
    }

    public function test_admin_can_create_new_customer(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.customers.store'), [
            'name' => 'Novo Cliente Admin',
            'email' => 'novocliente@example.com',
            'cpf' => '999.888.777-66',
            'phone' => '(11) 99999-8888',
            'password' => 'SenhaSegura123!',
            'role' => UserRole::Customer->value,
            'subscribe_newsletter' => '1',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'name' => 'Novo Cliente Admin',
            'email' => 'novocliente@example.com',
            'role' => UserRole::Customer->value,
        ]);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'novocliente@example.com',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_customer(): void
    {
        $customer = User::factory()->customer()->create([
            'name' => 'Nome Antigo',
            'email' => 'antigo@example.com',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.customers.update', $customer), [
            'name' => 'Nome Atualizado',
            'email' => 'atualizado@example.com',
            'cpf' => '111.222.333-44',
            'phone' => '(21) 97777-8888',
            'role' => UserRole::Customer->value,
        ]);

        $response->assertRedirect(route('admin.customers.show', $customer));

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'name' => 'Nome Atualizado',
            'email' => 'atualizado@example.com',
        ]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.customers.destroy', $this->admin));

        $response->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
        ]);
    }

    public function test_admin_cannot_delete_customer_with_paid_orders(): void
    {
        $customer = User::factory()->customer()->create();
        $product = Product::factory()->create(['price' => '50.00']);

        Order::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Paid,
            'amount' => '50.00',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.customers.destroy', $customer));

        $response->assertRedirect(route('admin.customers.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
        ]);
    }

    public function test_admin_can_delete_customer_without_orders(): void
    {
        $customer = User::factory()->customer()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.customers.destroy', $customer));

        $response->assertRedirect(route('admin.customers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $customer->id,
        ]);
    }
}
