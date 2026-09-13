<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_page_can_be_rendered_and_lists_active_products(): void
    {
        $active = Product::factory()->create(['title' => 'Template E-commerce Pro', 'is_active' => true]);
        $inactive = Product::factory()->create(['title' => 'Projeto Rascunho', 'is_active' => false]);

        $response = $this->get(route('catalog.index'));

        $response->assertOk()
            ->assertSee($active->title)
            ->assertDontSee($inactive->title);
    }

    public function test_catalog_filters_by_search_term(): void
    {
        $matched = Product::factory()->create(['title' => 'Dashboard Financeiro Laravel', 'is_active' => true]);
        $other = Product::factory()->create(['title' => 'Bot WhatsApp Python', 'is_active' => true]);

        $response = $this->get(route('catalog.index', ['q' => 'Financeiro']));

        $response->assertOk()
            ->assertSee($matched->title)
            ->assertDontSee($other->title);
    }

    public function test_catalog_filters_by_price_range(): void
    {
        $cheap = Product::factory()->create(['title' => 'Mini Script', 'price' => 35.00, 'is_active' => true]);
        $expensive = Product::factory()->create(['title' => 'Sistema ERP Completo', 'price' => 450.00, 'is_active' => true]);

        $response = $this->get(route('catalog.index', ['min_price' => 100.00]));

        $response->assertOk()
            ->assertSee($expensive->title)
            ->assertDontSee($cheap->title);
    }

    public function test_catalog_sorts_by_price_ascending(): void
    {
        $productB = Product::factory()->create(['title' => 'Produto Caro', 'price' => 200.00, 'is_active' => true]);
        $productA = Product::factory()->create(['title' => 'Produto Barato', 'price' => 50.00, 'is_active' => true]);

        $response = $this->get(route('catalog.index', ['sort' => 'price_asc']));

        $response->assertOk();
        $response->assertSeeInOrder([$productA->title, $productB->title]);
    }
}
