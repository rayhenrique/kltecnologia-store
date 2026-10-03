<?php

namespace Tests\Feature\Commands;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ScrapePlwProductsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_scrape_command_parses_catalog_and_persists_product(): void
    {
        $mockCatalog = <<<'HTML'
        <div class="product-item column">
            <div class="product-preview-actions">
                <figure class="product-preview-image liquid">
                    <img src="https://vip.plwdesign.online/media/sample-sm.jpg" alt="Script de Teste Scraper" />
                </figure>
                <div class="preview-actions">
                    <div class="preview-action">
                        <a href="https://vip.plwdesign.online/produto/script-de-teste-scraper">Ver item</a>
                    </div>
                </div>
            </div>
            <div class="product-info">
                <a href="https://vip.plwdesign.online/produto/script-de-teste-scraper">
                    <p class="text-header">Script de Teste Scraper...</p>
                </a>
                <div class="product-club-price">
                    <p class="price-was-line">De: <del>R$ 299,00</del></p>
                    <p class="price-current">Por: <ins><span class="amount">R$ 199,90</span></ins></p>
                </div>
            </div>
        </div>
        HTML;

        $mockDetail = <<<'HTML'
        <html>
            <body>
                <h1>Script de Teste Scraper Completo</h1>
                <figure id="item-gallery-frame">
                    <img id="item-cover" src="https://vip.plwdesign.online/media/sample-full.jpg" />
                </figure>
                <div class="item-club-price">
                    <p class="price-was-line">De: <del>R$ 299,00</del></p>
                    <p class="price-current"><ins><span class="amount">R$ 199,90</span></ins></p>
                </div>
                <div class="item-copy">
                    <p>Descrição completa do produto digital de teste.</p>
                    <ul>
                        <li>Recurso 1</li>
                        <li>Recurso 2</li>
                    </ul>
                </div>
            </body>
        </html>
        HTML;

        Http::fake([
            'https://vip.plwdesign.online/loja?page=1' => Http::response($mockCatalog, 200),
            'https://vip.plwdesign.online/loja?page=*' => Http::response('', 200),
            'https://vip.plwdesign.online/produto/script-de-teste-scraper' => Http::response($mockDetail, 200),
            'https://vip.plwdesign.online/media/sample-full.jpg' => Http::response('fake-image-binary', 200),
        ]);

        $this->artisan('app:scrape-plw --page=1 --limit=1')
            ->assertSuccessful();

        $product = Product::where('title', 'Script de Teste Scraper Completo')->first();
        $this->assertNotNull($product);
        $this->assertEquals(199.90, (float) $product->price);
        $this->assertNull($product->file_path);
        $this->assertFalse($product->has_file);
        $this->assertStringContainsString('Recurso 1', $product->description);
    }

    public function test_running_scraper_again_preserves_manually_curated_seo_and_editorial_fields(): void
    {
        $existing = Product::factory()->create([
            'title' => 'Script de Teste Scraper Completo',
            'slug' => 'script-de-teste-scraper-completo',
            'description' => 'Descrição manual curada e otimizada por redator humano.',
            'short_description' => 'Resumo editorial manual.',
            'seo_title' => 'Script de Teste Exclusivo — KL Tecnologia',
            'meta_description' => 'Compre agora o script de teste curado manualmente.',
            'features' => 'Recursos exclusivos customizados.',
            'requirements' => 'PHP 8.2+, MySQL 8',
            'license' => 'Licença Comercial Única',
            'brand' => 'KL Tecnologia Studio',
            'product_type' => 'Script Web',
            'support_info' => 'Suporte VIP via WhatsApp',
            'price' => 299.00,
            'is_active' => true,
            'file_path' => 'digital_products/existing.zip',
        ]);

        $mockCatalog = <<<'HTML'
        <div class="product-item column">
            <figure class="product-preview-image">
                <img src="https://vip.plwdesign.online/media/sample.jpg" alt="Script de Teste Scraper Completo" />
            </figure>
            <div class="product-info">
                <a href="https://vip.plwdesign.online/produto/script-de-teste-scraper-completo">
                    <p class="text-header">Script de Teste Scraper Completo</p>
                </a>
            </div>
        </div>
        HTML;

        $mockDetail = <<<'HTML'
        <html>
            <body>
                <h1>Script de Teste Scraper Completo</h1>
                <div class="item-copy">
                    <p>Descrição bruta do fornecedor que NÃO deve sobrescrever.</p>
                </div>
            </body>
        </html>
        HTML;

        Http::fake([
            'https://vip.plwdesign.online/loja?page=1' => Http::response($mockCatalog, 200),
            'https://vip.plwdesign.online/loja?page=*' => Http::response('', 200),
            'https://vip.plwdesign.online/produto/script-de-teste-scraper-completo' => Http::response($mockDetail, 200),
        ]);

        $this->artisan('app:scrape-plw --page=1 --limit=1')
            ->assertSuccessful();

        $refreshed = $existing->fresh();
        $this->assertSame('Descrição manual curada e otimizada por redator humano.', $refreshed->description);
        $this->assertSame('Resumo editorial manual.', $refreshed->short_description);
        $this->assertSame('Script de Teste Exclusivo — KL Tecnologia', $refreshed->seo_title);
        $this->assertSame('Compre agora o script de teste curado manualmente.', $refreshed->meta_description);
        $this->assertSame('Recursos exclusivos customizados.', $refreshed->features);
        $this->assertSame('PHP 8.2+, MySQL 8', $refreshed->requirements);
        $this->assertSame('Licença Comercial Única', $refreshed->license);
        $this->assertSame('KL Tecnologia Studio', $refreshed->brand);
        $this->assertSame('Script Web', $refreshed->product_type);
        $this->assertSame('Suporte VIP via WhatsApp', $refreshed->support_info);
        $this->assertEquals(299.00, (float) $refreshed->price);
    }
}
