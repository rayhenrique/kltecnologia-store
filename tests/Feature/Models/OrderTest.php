<?php

namespace Tests\Feature\Models;

use App\Enums\OrderStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_casts_and_relationships_are_configured(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'title' => 'Kit Canva',
            'description' => 'Template digital para redes sociais.',
            'price' => '29.90',
            'file_path' => 'products/kit-canva.zip',
        ]);

        $order = $user->orders()->create([
            'product_id' => $product->id,
            'amount' => $product->price,
        ]);

        $this->assertSame(OrderStatus::Pending, $order->status);
        $this->assertSame('29.90', $order->amount);
        $this->assertTrue($order->user->is($user));
        $this->assertTrue($order->product->is($product));
        $this->assertTrue($product->orders()->firstOrFail()->is($order));
    }
}
