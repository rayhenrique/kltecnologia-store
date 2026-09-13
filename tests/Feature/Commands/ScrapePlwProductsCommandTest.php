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
                    <p class="price-was-line">De: <del>R$ 199,90</del></p>
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
                    <p class="price-was-line">De: <del>R$ 199,90</del></p>
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
}
