<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoAndSitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_returns_successful_xml_response(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', (string) $response->headers->get('Content-Type'));
        $response->assertSee('<urlset', false);
        $response->assertSee('http://www.sitemaps.org/schemas/sitemap/0.9', false);
        $response->assertSee(route('storefront.index'), false);
        $response->assertSee(route('catalog.index'), false);
        $response->assertSee(route('blog.index'), false);
        $response->assertSee(route('privacy.index'), false);
        $response->assertSee(route('terms.index'), false);
    }

    public function test_sitemap_includes_active_products_and_excludes_invalid_ones(): void
    {
        $validProduct = Product::factory()->create([
            'title' => 'Sistema Válido para Venda',
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $inactiveProduct = Product::factory()->create([
            'title' => 'Sistema Desativado',
            'is_active' => false,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $productWithoutFile = Product::factory()->create([
            'title' => 'Sistema Sem Arquivo',
            'is_active' => true,
            'file_path' => null,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertSee(route('storefront.show', $validProduct->slug), false);
        $response->assertDontSee(route('storefront.show', $inactiveProduct->slug), false);
        $response->assertDontSee(route('storefront.show', $productWithoutFile->slug), false);
    }

    public function test_sitemap_includes_categories_and_published_posts(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'file_path' => 'digital_products/test.zip',
        ]);

        $blogCat = BlogCategory::factory()->create(['is_active' => true]);
        $publishedPost = Post::factory()->create([
            'blog_category_id' => $blogCat->id,
            'is_published' => true,
        ]);

        $draftPost = Post::factory()->create([
            'blog_category_id' => $blogCat->id,
            'is_published' => false,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertSee(route('catalog.index', ['categoria' => $category->slug]), false);
        $response->assertSee(route('blog.index', ['categoria' => $blogCat->slug]), false);
        $response->assertSee(route('blog.show', $publishedPost->slug), false);
        $response->assertDontSee(route('blog.show', $draftPost->slug), false);
    }

    public function test_robots_txt_contains_sitemap_and_disallowed_paths(): void
    {
        $robotsContent = file_get_contents(public_path('robots.txt'));

        $this->assertNotFalse($robotsContent);
        $this->assertStringContainsString('Disallow: /admin/', $robotsContent);
        $this->assertStringContainsString('Disallow: /checkout', $robotsContent);
        $this->assertStringContainsString('Disallow: /carrinho', $robotsContent);
        $this->assertStringContainsString('Disallow: /favoritos', $robotsContent);
        $this->assertStringContainsString('Disallow: /customer/', $robotsContent);
        $this->assertStringContainsString('Sitemap: https://kltecnologia.com/sitemap.xml', $robotsContent);
    }

    public function test_product_detail_page_renders_json_ld_schema_and_meta_tags(): void
    {
        $product = Product::factory()->create([
            'title' => 'Script de E-commerce Laravel',
            'description' => 'Sistema completo com checkout integrado e Mercado Pago.',
            'price' => 199.90,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $response = $this->get(route('storefront.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type": "Product"', false);
        $response->assertSee('"name": "Script de E-commerce Laravel"', false);
        $response->assertSee('"priceCurrency": "BRL"', false);
        $response->assertSee('"price": "199.90"', false);
        $response->assertSee('https://schema.org/InStock', false);
        $response->assertSee('"@type": "BreadcrumbList"', false);
        $response->assertSee('content="product"', false);
        $response->assertSee('content="'.route('storefront.show', $product->slug).'"', false);
    }

    public function test_blog_post_page_renders_json_ld_schema_and_meta_tags(): void
    {
        $post = Post::factory()->create([
            'title' => 'Como Vender Scripts Prontos em 2026',
            'excerpt' => 'Guia definitivo para empreendedores digitais.',
            'content' => '<p>Artigo detalhado sobre o mercado de scripts prontos.</p>',
            'is_published' => true,
        ]);

        $response = $this->get(route('blog.show', $post->slug));

        $response->assertStatus(200);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type": "Article"', false);
        $response->assertSee('"headline": "Como Vender Scripts Prontos em 2026"', false);
        $response->assertSee('"name": "KL Tecnologia"', false);
        $response->assertSee('"@type": "BreadcrumbList"', false);
        $response->assertSee('content="article"', false);
        $response->assertSee('content="'.route('blog.show', $post->slug).'"', false);
    }

    public function test_storefront_renders_google_verification_and_analytics_when_configured(): void
    {
        config([
            'services.google.site_verification' => 'test-verification-code-12345',
            'services.google.analytics_id' => 'G-ABC123XYZ',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<meta name="google-site-verification" content="test-verification-code-12345">', false);
        $response->assertSee('https://www.googletagmanager.com/gtag/js?id=G-ABC123XYZ', false);
        $response->assertSee("gtag('config', 'G-ABC123XYZ');", false);
    }
}
