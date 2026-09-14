<?php

namespace Tests\Feature\Models;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_casts_price_and_active_status(): void
    {
        $product = Product::create($this->attributes());

        $this->assertSame('29.90', $product->price);
        $this->assertTrue($product->is_active);
    }

    public function test_product_generates_a_unique_slug_including_soft_deleted_records(): void
    {
        $firstProduct = Product::create($this->attributes());
        $secondProduct = Product::create($this->attributes());

        $firstProduct->delete();

        $thirdProduct = Product::create($this->attributes());

        $this->assertSame('kit-canva', $firstProduct->slug);
        $this->assertSame('kit-canva-2', $secondProduct->slug);
        $this->assertSame('kit-canva-3', $thirdProduct->slug);
    }

    public function test_product_resolves_webp_cover_if_available(): void
    {
        // Pick an existing webp cover in public/covers
        $product = Product::create(array_merge($this->attributes(), [
            'cover_path' => 'covers/agenda-plw-sistema-de-agendamentos-codigo-fonte.png',
        ]));

        $this->assertSame('covers/agenda-plw-sistema-de-agendamentos-codigo-fonte.webp', $product->cover_path);
    }

    /**
     * @return array<string, mixed>
     */
    private function attributes(): array
    {
        return [
            'title' => 'Kit Canva',
            'description' => 'Template digital para redes sociais.',
            'price' => '29.90',
            'file_path' => 'products/kit-canva.zip',
        ];
    }
}
