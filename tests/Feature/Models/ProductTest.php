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

    public function test_product_preserves_existing_slug_when_title_is_updated(): void
    {
        $product = Product::create($this->attributes());
        $originalSlug = $product->slug;

        $product->update(['title' => 'Kit Canva 2026 Novo Nome']);
        $product->refresh();

        $this->assertSame('Kit Canva 2026 Novo Nome', $product->title);
        $this->assertSame($originalSlug, $product->slug);
    }

    public function test_product_supports_seo_and_commercial_fields(): void
    {
        $product = Product::create(array_merge($this->attributes(), [
            'short_description' => 'Breve resumo do kit Canva.',
            'seo_title' => 'Kit Canva Profissional — O Melhor do Mercado',
            'meta_description' => 'Compre o kit canva profissional com entrega imediata.',
            'product_type' => 'Templates',
            'brand' => 'Design Studio',
            'features' => "Mais de 100 templates editáveis\nFontes inclusas",
            'requirements' => 'Conta gratuita no Canva',
            'license' => 'Uso comercial permitido para revenda de artes',
            'support_info' => 'Suporte por e-mail em até 24 horas',
            'demo_url' => 'https://demo.example.com/canva',
            'documentation_url' => 'https://docs.example.com/canva',
            'includes_source_code' => true,
            'lifetime_access' => true,
        ]));

        $this->assertSame('Breve resumo do kit Canva.', $product->short_description);
        $this->assertSame('Kit Canva Profissional — O Melhor do Mercado', $product->seo_title);
        $this->assertSame('Compre o kit canva profissional com entrega imediata.', $product->meta_description);
        $this->assertSame('Templates', $product->product_type);
        $this->assertSame('Design Studio', $product->brand);
        $this->assertSame('Conta gratuita no Canva', $product->requirements);
        $this->assertTrue($product->includes_source_code);
        $this->assertTrue($product->lifetime_access);
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
