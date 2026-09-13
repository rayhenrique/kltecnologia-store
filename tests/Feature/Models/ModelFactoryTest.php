<?php

namespace Tests\Feature\Models;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_factories_create_valid_states_and_relationships(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->inactive()->create();
        $order = Order::factory()->paid()->create();

        $this->assertSame(UserRole::Admin, $admin->role);
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($product->is_active);
        $this->assertNotEmpty($product->slug);
        $this->assertSame("covers/{$product->slug}.jpg", $product->cover_path);
        $this->assertSame("products/{$product->slug}.zip", $product->file_path);
        $this->assertSame(OrderStatus::Paid, $order->status);
        $this->assertSame($order->product->price, $order->amount);
        $this->assertNotNull($order->gateway_reference);
        $this->assertInstanceOf(User::class, $order->user);
        $this->assertInstanceOf(Product::class, $order->product);
    }
}
