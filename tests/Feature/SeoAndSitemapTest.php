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
        $this->assertStringContainsString('Disallow: /customer/', $robotsContent);
        $this->assertStringContainsString('Sitemap: https://kltecnologia.com/sitemap.xml', $robotsContent);

        // Public transactional pages that carry meta noindex must NOT be disallowed in robots.txt
        $this->assertStringNotContainsString('Disallow: /checkout', $robotsContent);
        $this->assertStringNotContainsString('Disallow: /carrinho', $robotsContent);
        $this->assertStringNotContainsString('Disallow: /favoritos', $robotsContent);
        $this->assertStringNotContainsString('Disallow: /login', $robotsContent);
        $this->assertStringNotContainsString('Disallow: /register', $robotsContent);
        $this->assertStringNotContainsString('Disallow: /password/', $robotsContent);
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

        // Testa variante ?category=
        $responseCategory = $this->get('/catalogo?category=sistemas-saas');
        $responseCategory->assertRedirect('/catalogo/sistemas-saas');
        $responseCategory->assertStatus(301);

        // Testa variante legada ?categoria=
        $responseCategoria = $this->get('/catalogo?categoria=sistemas-saas');
        $responseCategoria->assertRedirect('/catalogo/sistemas-saas');
        $responseCategoria->assertStatus(301);
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

    public function test_exact_title_tags_for_all_entities_without_brand_duplication(): void
    {
        // 1. Produto sem seo_title
        $productWithoutSeo = Product::factory()->create([
            'title' => 'Sistema Financeiro Pro',
            'seo_title' => null,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);
        $resProductNoSeo = $this->get(route('storefront.show', $productWithoutSeo->slug));
        $resProductNoSeo->assertSee('<title>Sistema Financeiro Pro — KL Tecnologia</title>', false);
        $resProductNoSeo->assertDontSee('KL Tecnologia | KL Tecnologia');
        $resProductNoSeo->assertDontSee('KL Tecnologia — KL Tecnologia');

        // 2. Produto com seo_title
        $productWithSeo = Product::factory()->create([
            'title' => 'Sistema Financeiro Pro',
            'seo_title' => 'Software Financeiro Completo com PIX',
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);
        $resProductWithSeo = $this->get(route('storefront.show', $productWithSeo->slug));
        $resProductWithSeo->assertSee('<title>Software Financeiro Completo com PIX</title>', false);
        $resProductWithSeo->assertDontSee('KL Tecnologia | KL Tecnologia');

        // 3. Categoria sem seo_title
        $catNoSeo = Category::factory()->create([
            'name' => 'Automações Web',
            'slug' => 'automacoes-web',
            'seo_title' => null,
            'is_active' => true,
        ]);
        $resCatNoSeo = $this->get(route('catalog.category', $catNoSeo->slug));
        $resCatNoSeo->assertSee('<title>Automações Web — KL Tecnologia</title>', false);
        $resCatNoSeo->assertDontSee('KL Tecnologia | KL Tecnologia');

        // 4. Categoria com seo_title
        $catWithSeo = Category::factory()->create([
            'name' => 'Scripts PHP',
            'slug' => 'scripts-php-seo',
            'seo_title' => 'Scripts PHP Profissionais para Venda',
            'is_active' => true,
        ]);
        $resCatWithSeo = $this->get(route('catalog.category', $catWithSeo->slug));
        $resCatWithSeo->assertSee('<title>Scripts PHP Profissionais para Venda</title>', false);
        $resCatWithSeo->assertDontSee('KL Tecnologia | KL Tecnologia');

        // 5. Post sem seo_title
        $postNoSeo = Post::factory()->create([
            'title' => 'Como Criar um SaaS em 30 Dias',
            'seo_title' => null,
            'is_published' => true,
        ]);
        $resPostNoSeo = $this->get(route('blog.show', $postNoSeo->slug));
        $resPostNoSeo->assertSee('<title>Como Criar um SaaS em 30 Dias — Blog KL Tecnologia</title>', false);
        $resPostNoSeo->assertDontSee('KL Tecnologia | KL Tecnologia');

        // 6. Post com seo_title
        $postWithSeo = Post::factory()->create([
            'title' => 'Como Criar um SaaS em 30 Dias',
            'seo_title' => 'Guia Definitivo SaaS 2026',
            'is_published' => true,
        ]);
        $resPostWithSeo = $this->get(route('blog.show', $postWithSeo->slug));
        $resPostWithSeo->assertSee('<title>Guia Definitivo SaaS 2026</title>', false);
        $resPostWithSeo->assertDontSee('KL Tecnologia | KL Tecnologia');
    }

    public function test_product_detail_page_brand_and_license_display_and_no_fictitious_values(): void
    {
        // Produto COM brand e license cadastradas
        $productWithSpecs = Product::factory()->create([
            'title' => 'Script com Especificações Reais',
            'brand' => 'TechStudio Brasil',
            'license' => 'GPLv3 Comercial',
            'price' => 149.00,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $resSpecs = $this->get(route('storefront.show', $productWithSpecs->slug));
        $resSpecs->assertOk();
        $resSpecs->assertSee('TechStudio Brasil');
        $resSpecs->assertSee('GPLv3 Comercial');

        // Produto SEM brand e license cadastradas
        $productEmptySpecs = Product::factory()->create([
            'title' => 'Script Sem Campos Opcionais',
            'brand' => null,
            'license' => null,
            'price' => 149.00,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $resEmpty = $this->get(route('storefront.show', $productEmptySpecs->slug));
        $resEmpty->assertOk();
        // Não pode gerar marcas ou licenças fictícias
        $resEmpty->assertDontSee('Marca / Autor');
        $resEmpty->assertDontSee('Uso Vitalício');
        $resEmpty->assertDontSee('Comercial');
        $resEmpty->assertDontSee('De: R$');
        $resEmpty->assertDontSee('47,00');
    }

    public function test_product_detail_page_pricing_is_factual_without_strikethrough_or_untrue_lifetime_claims(): void
    {
        // 1. Produto pago
        $paidProduct = Product::factory()->create([
            'title' => 'Sistema Pago Real',
            'price' => 197.00,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $resPaid = $this->get(route('storefront.show', $paidProduct->slug));
        $resPaid->assertOk();
        $resPaid->assertSee('R$ 197,00');
        $resPaid->assertDontSee('<del', false);
        $resPaid->assertDontSee('De: R$');

        // 2. Produto grátis sem acesso vitalício
        $freeProductNoLifetime = Product::factory()->create([
            'title' => 'E-book Grátis Básico',
            'price' => 0.00,
            'lifetime_access' => false,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $resFreeNoLifetime = $this->get(route('storefront.show', $freeProductNoLifetime->slug));
        $resFreeNoLifetime->assertOk();
        $resFreeNoLifetime->assertSee('GRÁTIS');
        $resFreeNoLifetime->assertDontSee('De: R$');
        $resFreeNoLifetime->assertDontSee('Sem cobrança. Acesso instantâneo e vitalício após o cadastro.');
        $resFreeNoLifetime->assertSee('Sem cobrança. Acesso instantâneo após o cadastro.');

        // 3. Produto grátis COM acesso vitalício
        $freeProductWithLifetime = Product::factory()->create([
            'title' => 'Template Grátis Vitalício',
            'price' => 0.00,
            'lifetime_access' => true,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $resFreeLifetime = $this->get(route('storefront.show', $freeProductWithLifetime->slug));
        $resFreeLifetime->assertOk();
        $resFreeLifetime->assertSee('Sem cobrança. Acesso instantâneo e vitalício após o cadastro.');
    }

    public function test_pagination_canonical_on_catalog_and_blog_pages(): void
    {
        // Catálogo página 1
        $resCatalogP1 = $this->get('/catalogo');
        $resCatalogP1->assertSee('<link rel="canonical" href="'.url('/catalogo').'">', false);
        $resCatalogP1->assertSee('<meta name="robots" content="index, follow">', false);

        // Catálogo página 2
        $resCatalogP2 = $this->get('/catalogo?page=2');
        $resCatalogP2->assertSee('<link rel="canonical" href="'.url('/catalogo?page=2').'">', false);
        $resCatalogP2->assertSee('<meta name="robots" content="index, follow">', false);

        // Categoria página 2
        $cat = Category::factory()->create(['is_active' => true]);
        $resCatP2 = $this->get(route('catalog.category', $cat->slug).'?page=2');
        $resCatP2->assertSee('<link rel="canonical" href="'.route('catalog.category', $cat->slug).'?page=2">', false);
        $resCatP2->assertSee('<meta name="robots" content="index, follow">', false);

        // Blog página 2
        $resBlogP2 = $this->get(route('blog.index').'?page=2');
        $resBlogP2->assertSee('<link rel="canonical" href="'.route('blog.index').'?page=2">', false);
        $resBlogP2->assertSee('<meta name="robots" content="index, follow">', false);

        // Blog Categoria página 2
        $blogCat = BlogCategory::factory()->create(['is_active' => true]);
        $resBlogCatP2 = $this->get(route('blog.category', $blogCat->slug).'?page=2');
        $resBlogCatP2->assertSee('<link rel="canonical" href="'.route('blog.category', $blogCat->slug).'?page=2">', false);
        $resBlogCatP2->assertSee('<meta name="robots" content="index, follow">', false);

        // Busca com paginação deve manter noindex, follow e canonicalizar para landing page base
        $resSearchPag = $this->get('/catalogo?q=laravel&page=2');
        $resSearchPag->assertSee('<meta name="robots" content="noindex, follow">', false);
        $resSearchPag->assertSee('<link rel="canonical" href="'.url('/catalogo').'">', false);
    }

    public function test_product_json_ld_omits_null_properties_and_preserves_required_structure(): void
    {
        $product = Product::factory()->create([
            'title' => 'Produto Sem Imagem ou Marca',
            'brand' => null,
            'cover_path' => null,
            'category_id' => null,
            'category' => null,
            'price' => 79.90,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $response = $this->get(route('storefront.show', $product->slug));
        $response->assertOk();

        $content = $response->getContent();
        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
        $this->assertNotEmpty($matches[1]);
        $productSchema = null;
        foreach ($matches[1] as $json) {
            $data = json_decode($json, true);
            if (isset($data['@graph'])) {
                $productSchema = collect($data['@graph'])->firstWhere('@type', 'Product');
                if ($productSchema) {
                    break;
                }
            }
        }

        $this->assertNotNull($productSchema);
        $this->assertSame('Produto Sem Imagem ou Marca', $productSchema['name']);
        $this->assertArrayNotHasKey('brand', $productSchema);
        $this->assertArrayNotHasKey('image', $productSchema);
        $this->assertArrayNotHasKey('category', $productSchema);
        $this->assertSame('79.90', $productSchema['offers']['price']);
        $this->assertSame('BRL', $productSchema['offers']['priceCurrency']);
        $this->assertSame('KL-'.$product->id, $productSchema['sku']);
        $this->assertArrayNotHasKey('aggregateRating', $productSchema);
        $this->assertArrayNotHasKey('review', $productSchema);
    }

    public function test_article_json_ld_omits_image_when_cover_is_absent_and_keeps_publisher_logo(): void
    {
        $post = Post::factory()->create([
            'title' => 'Artigo Sem Imagem de Capa',
            'cover_path' => null,
            'is_published' => true,
        ]);

        $response = $this->get(route('blog.show', $post->slug));
        $response->assertOk();

        $content = $response->getContent();
        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
        $this->assertNotEmpty($matches[1]);
        $article = null;
        foreach ($matches[1] as $json) {
            $data = json_decode($json, true);
            if (isset($data['@graph'])) {
                $article = collect($data['@graph'])->firstWhere('@type', 'Article');
                if ($article) {
                    break;
                }
            }
        }

        $this->assertNotNull($article);
        $this->assertSame('Artigo Sem Imagem de Capa', $article['headline']);
        $this->assertArrayNotHasKey('image', $article);
        $this->assertSame('KL Tecnologia', $article['publisher']['name']);
        $this->assertStringContainsString('logo-kltecnologia.png', $article['publisher']['logo']['url']);
    }

    public function test_sitemap_does_not_contain_artificial_dates_for_static_pages(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertOk();

        $content = $response->getContent();
        $this->assertStringNotContainsString(now()->startOfMonth()->toAtomString(), $content);

        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml);

        $privacyUrl = null;
        $termsUrl = null;

        foreach ($xml->url as $url) {
            $loc = (string) $url->loc;
            if ($loc === route('privacy.index')) {
                $privacyUrl = $url;
            }
            if ($loc === route('terms.index')) {
                $termsUrl = $url;
            }
        }

        $this->assertNotNull($privacyUrl, 'URL da Política de Privacidade deve estar presente no sitemap.');
        $this->assertFalse(isset($privacyUrl->lastmod), 'Política de Privacidade não deve ter lastmod no sitemap.');
        $this->assertSame('monthly', (string) $privacyUrl->changefreq);
        $this->assertSame('0.3', (string) $privacyUrl->priority);

        $this->assertNotNull($termsUrl, 'URL dos Termos de Uso deve estar presente no sitemap.');
        $this->assertFalse(isset($termsUrl->lastmod), 'Termos de Uso não deve ter lastmod no sitemap.');
        $this->assertSame('monthly', (string) $termsUrl->changefreq);
        $this->assertSame('0.3', (string) $termsUrl->priority);
    }
}
