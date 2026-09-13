<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_product_without_file_is_not_offered(): void
    {
        $product = Product::factory()->create([
            'title' => 'Produto ainda sem arquivo',
            'is_active' => true,
            'file_path' => null,
        ]);

        $this->get(route('storefront.index'))->assertOk()->assertDontSee($product->title);
        $this->get(route('storefront.show', $product))->assertNotFound();
    }

    public function test_storefront_lists_only_active_products(): void
    {
        $active = Product::factory()->create(['title' => 'Produto disponível', 'is_active' => true]);
        $inactive = Product::factory()->create(['title' => 'Produto oculto', 'is_active' => false]);
        $this->get(route('storefront.index'))->assertOk()->assertSee($active->title)->assertDontSee($inactive->title);
        $this->get(route('storefront.show', $inactive))->assertNotFound();
    }

    public function test_storefront_filters_products_by_search_term(): void
    {
        $matched = Product::factory()->create(['title' => 'Dashboard Laravel', 'is_active' => true]);
        $other = Product::factory()->create(['title' => 'Script Python', 'is_active' => true]);

        $response = $this->get(route('storefront.index', ['q' => 'Dashboard']));

        $response->assertOk()
            ->assertSee($matched->title)
            ->assertDontSee($other->title);
    }

    public function test_product_detail_page_renders_with_related_products_and_tabs(): void
    {
        $product = Product::factory()->create([
            'title' => 'Shop Mobile WhatsApp',
            'price' => 49.90,
            'is_active' => true,
            'description' => "Sistema de catálogo online.\n\n### Recursos do Sistema\n• Painel administrativo\n• Pedidos no WhatsApp",
        ]);

        $related = Product::factory()->create([
            'title' => 'Sistema Delivery Express',
            'price' => 69.90,
            'is_active' => true,
        ]);

        $response = $this->get(route('storefront.show', $product));

        $response->assertOk()
            ->assertSee('Shop Mobile WhatsApp')
            ->assertSee('49,90')
            ->assertSee('Sistema de catálogo online')
            ->assertSee('Recursos do Sistema')
            ->assertSee('Painel administrativo')
            ->assertSee('Sistema Delivery Express')
            ->assertSee('Por que comprar na KL Tecnologia?')
            ->assertSee('Perguntas Frequentes sobre a Compra');
    }

    public function test_storefront_displays_featured_products_in_featured_section(): void
    {
        $featured = Product::factory()->create([
            'title' => 'Produto Especial VIP',
            'is_active' => true,
            'is_featured' => true,
        ]);

        $normal = Product::factory()->create([
            'title' => 'Produto Comum Padrao',
            'is_active' => true,
            'is_featured' => false,
        ]);

        $response = $this->get(route('storefront.index'));
        $response->assertOk();

        $featuredProducts = $response->viewData('featuredProducts');
        $this->assertTrue($featuredProducts->contains('id', $featured->id));
        $this->assertFalse($featuredProducts->contains('id', $normal->id));
    }

    public function test_storefront_complete_catalog_shows_recently_created_or_updated_first(): void
    {
        $this->travelTo(now()->subDays(10));
        $older = Product::factory()->create([
            'title' => 'Primeiro Produto Criado',
            'is_active' => true,
        ]);

        $this->travelTo(now()->subDays(5));
        $newer = Product::factory()->create([
            'title' => 'Segundo Produto Criado',
            'is_active' => true,
        ]);

        $this->travelTo(now());
        $older->update(['description' => 'Atualização recente de conteúdo']);

        $response = $this->get(route('storefront.index'));
        $response->assertOk();

        $products = $response->viewData('products');
        $this->assertSame($older->id, $products->first()->id);
    }
}
