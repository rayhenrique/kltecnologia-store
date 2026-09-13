<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_favorites_page_can_be_rendered(): void
    {
        $response = $this->get(route('favorites.index'));

        $response->assertOk()
            ->assertSee('Meus Favoritos')
            ->assertSee('Sua lista de favoritos está vazia');
    }

    public function test_favorites_page_displays_recommended_active_products(): void
    {
        $product = Product::factory()->create([
            'title' => 'Sistema ERP em Laravel',
            'is_active' => true,
        ]);

        $inactiveProduct = Product::factory()->create([
            'title' => 'Produto Desativado nos Favoritos',
            'is_active' => false,
        ]);

        $response = $this->get(route('favorites.index'));

        $response->assertOk()
            ->assertSee($product->title)
            ->assertDontSee($inactiveProduct->title);
    }

    public function test_favorites_items_endpoint_returns_matching_active_products(): void
    {
        $p1 = Product::factory()->create([
            'title' => 'Sistema de Barbearia',
            'price' => 97.00,
            'is_active' => true,
        ]);

        $p2 = Product::factory()->create([
            'title' => 'Sistema Grátis de Portfólio',
            'price' => 0.00,
            'is_active' => true,
        ]);

        $inactive = Product::factory()->create([
            'title' => 'Sistema Antigo Inativo',
            'price' => 49.00,
            'is_active' => false,
        ]);

        $response = $this->postJson(route('favorites.items'), [
            'ids' => [$p1->id, $p2->id, $inactive->id],
        ]);

        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'id' => $p1->id,
                'title' => 'Sistema de Barbearia',
                'formatted_price' => 'R$ 97,00',
                'is_free' => false,
            ])
            ->assertJsonFragment([
                'id' => $p2->id,
                'title' => 'Sistema Grátis de Portfólio',
                'formatted_price' => 'GRÁTIS',
                'is_free' => true,
            ])
            ->assertJsonMissing([
                'id' => $inactive->id,
            ]);
    }

    public function test_favorites_items_endpoint_handles_empty_or_null_ids(): void
    {
        $response1 = $this->postJson(route('favorites.items'), [
            'ids' => [],
        ]);

        $response1->assertOk()
            ->assertExactJson([]);

        $response2 = $this->postJson(route('favorites.items'));

        $response2->assertOk()
            ->assertExactJson([]);
    }

    public function test_storefront_links_to_favorites_page(): void
    {
        $response = $this->get(route('storefront.index'));

        $response->assertOk()
            ->assertSee(route('favorites.index'));
    }
}
