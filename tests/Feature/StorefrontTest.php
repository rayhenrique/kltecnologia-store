<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

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
}
