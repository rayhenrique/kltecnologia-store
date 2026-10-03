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

        $response->assertSee(route('catalog.category', $category->slug), false);
        $response->assertSee(route('blog.category', $blogCat->slug), false);
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

    public function test_clean_catalog_category_url_renders_and_sets_canonical(): void
    {
        $category = Category::query()->where('slug', 'scripts-php')->first() ?? Category::factory()->create(['slug' => 'scripts-php']);
        $category->update([
            'name' => 'Scripts PHP',
            'seo_title' => 'Comprar Scripts PHP Prontos | KL Tecnologia',
            'meta_description' => 'Scripts PHP com código aberto e entrega imediata.',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'category_id' => $category->id,
            'title' => 'Script de Automação',
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $response = $this->get('/catalogo/scripts-php');

        $response->assertStatus(200);
        $response->assertSee('Comprar Scripts PHP Prontos | KL Tecnologia');
        $response->assertSee('Scripts PHP com código aberto e entrega imediata.');
        $response->assertSee('<link rel="canonical" href="'.url('/catalogo/scripts-php').'">', false);
        $response->assertSee('<h1', false);
        $response->assertSee('Scripts PHP');
    }

    public function test_legacy_catalog_query_param_redirects_301_to_clean_url(): void
    {
        Category::query()->where('slug', 'sistemas-saas')->first() ?? Category::factory()->create([
            'slug' => 'sistemas-saas',
            'is_active' => true,
        ]);

        $response = $this->get('/catalogo?category=sistemas-saas');

        $response->assertRedirect('/catalogo/sistemas-saas');
        $response->assertStatus(301);
    }

    public function test_clean_blog_category_url_renders_and_legacy_redirects(): void
    {
        $blogCategory = BlogCategory::factory()->create([
            'name' => 'Tutoriais',
            'slug' => 'tutoriais',
            'is_active' => true,
        ]);

        Post::factory()->create([
            'blog_category_id' => $blogCategory->id,
            'title' => 'Tutorial de Laravel',
            'is_published' => true,
        ]);

        $responseClean = $this->get('/blog/categoria/tutoriais');
        $responseClean->assertStatus(200);
        $responseClean->assertSee('Tutorial de Laravel');
        $responseClean->assertSee('<link rel="canonical" href="'.url('/blog/categoria/tutoriais').'">', false);

        $responseLegacy = $this->get('/blog?categoria=tutoriais');
        $responseLegacy->assertRedirect('/blog/categoria/tutoriais');
        $responseLegacy->assertStatus(301);
    }

    public function test_filter_and_search_query_strings_inject_noindex_follow(): void
    {
        $responseSearch = $this->get('/catalogo?q=laravel');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('<meta name="robots" content="noindex, follow">', false);
        $responseSearch->assertSee('<link rel="canonical" href="'.url('/catalogo').'">', false);

        $responseSort = $this->get('/catalogo?sort=price_asc');
        $responseSort->assertStatus(200);
        $responseSort->assertSee('<meta name="robots" content="noindex, follow">', false);
    }

    public function test_product_detail_page_does_not_contain_fake_reviews_or_generic_tech(): void
    {
        $product = Product::factory()->create([
            'title' => 'Bot Python Telegram',
            'description' => 'Robô para automação de mensagens.',
            'includes_source_code' => false,
            'lifetime_access' => true,
            'license' => 'Licença Individual 1 Domínio',
            'requirements' => 'Python 3.11+, VPS Linux',
            'features' => 'Painel Web, Webhook integrado',
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $response = $this->get(route('storefront.show', $product->slug));

        $response->assertStatus(200);
        $response->assertDontSee('48 avaliações de clientes verificados');
        $response->assertDontSee('100% avaliaram como excelente');
        $response->assertDontSee('aggregateRating');
        $response->assertDontSee('review');
        $response->assertDontSee('Código Fonte Incluso');
        $response->assertSee('Licença Individual 1 Domínio');
        $response->assertSee('Python 3.11+, VPS Linux');
        $response->assertSee('Painel Web');
    }

    public function test_slug_redirect_works_when_product_slug_changes(): void
    {
        $product = Product::factory()->create([
            'title' => 'Script Antigo',
            'slug' => 'script-antigo',
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        // Manually update slug to trigger redirect creation
        $product->slug = 'script-novo';
        $product->save();

        $response = $this->get('/produtos/script-antigo');

        $response->assertStatus(301);
        $response->assertRedirect(route('storefront.show', 'script-novo'));
    }
}
