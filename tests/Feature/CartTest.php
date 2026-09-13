<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_can_be_rendered(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertOk()
            ->assertSee('Carrinho de Compras')
            ->assertSee('Seu carrinho está vazio')
            ->assertSee('Resumo do Pedido');
    }

    public function test_cart_page_displays_recommended_active_products(): void
    {
        $product = Product::factory()->create([
            'title' => 'Sistema ERP em Laravel',
            'is_active' => true,
        ]);

        $inactiveProduct = Product::factory()->create([
            'title' => 'Produto Desativado',
            'is_active' => false,
        ]);

        $response = $this->get(route('cart.index'));

        $response->assertOk()
            ->assertSee($product->title)
            ->assertDontSee($inactiveProduct->title);
    }

    public function test_storefront_links_to_cart_page(): void
    {
        $response = $this->get(route('storefront.index'));

        $response->assertOk()
            ->assertSee(route('cart.index'));
    }

    public function test_cart_page_links_to_checkout_page(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertOk()
            ->assertSee(route('checkout.index'));
    }
}
