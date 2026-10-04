<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontHardeningFactualContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_without_related_articles_hides_blog_section(): void
    {
        $category = Category::factory()->create([
            'name' => 'Scripts Exclusivos Inéditos',
            'slug' => 'scripts-exclusivos-ineditos',
        ]);

        $product = Product::factory()->create([
            'title' => 'Script de Automação',
            'category_id' => $category->id,
            'category' => $category->name,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        // Create a published post in an unrelated category
        Post::factory()->create([
            'title' => 'Guia de Inteligência Artificial',
            'category' => 'Inteligência Artificial',
        ]);

        $response = $this->get(route('storefront.show', $product->slug));
        $response->assertOk();
        $response->assertDontSee('Do Blog & Guias Técnicos');
        $response->assertDontSee('Guia de Inteligência Artificial');
    }

    public function test_product_with_related_articles_displays_blog_section(): void
    {
        $category = Category::factory()->create([
            'name' => 'Sistemas Comerciais',
            'slug' => 'sistemas-comerciais',
        ]);

        $product = Product::factory()->create([
            'title' => 'Software de Frente de Caixa',
            'category_id' => $category->id,
            'category' => $category->name,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        Post::factory()->create([
            'title' => 'Como Configurar Sistemas Comerciais com Segurança',
            'category' => 'Sistemas Comerciais',
        ]);

        $response = $this->get(route('storefront.show', $product->slug));
        $response->assertOk();
        $response->assertSee('Do Blog & Guias Técnicos', false);
        $response->assertSee('Como Configurar Sistemas Comerciais com Segurança');
    }

    public function test_product_page_does_not_display_version_if_null(): void
    {
        $product = Product::factory()->create([
            'title' => 'Template Sem Versão',
            'version' => null,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $response = $this->get(route('storefront.show', $product->slug));
        $response->assertOk();
        $response->assertDontSee('<dt class="text-slate-500 font-medium">Versão</dt>', false);
        $response->assertDontSee('<dd class="font-semibold text-slate-900">1.0</dd>', false);
    }

    public function test_product_page_displays_version_when_filled(): void
    {
        $product = Product::factory()->create([
            'title' => 'Template Com Versão',
            'version' => '3.5.2',
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $response = $this->get(route('storefront.show', $product->slug));
        $response->assertOk();
        $response->assertSee('<dt class="text-slate-500 font-medium">Versão</dt>', false);
        $response->assertSee('3.5.2');
    }

    public function test_product_page_displays_anuncio_atualizado_label(): void
    {
        $product = Product::factory()->create([
            'title' => 'Produto com Data',
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $response = $this->get(route('storefront.show', $product->slug));
        $response->assertOk();
        $response->assertSee('Anúncio atualizado');
        $response->assertSee($product->updated_at->format('d/m/Y'));
        $response->assertDontSee('Última atualização do sistema');
    }

    public function test_product_page_displays_codigo_fonte_incluso_when_source_code_flag_is_true(): void
    {
        $product = Product::factory()->create([
            'title' => 'Sistema com Fontes',
            'includes_source_code' => true,
            'license' => null,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $response = $this->get(route('storefront.show', $product->slug));
        $response->assertOk();
        $response->assertSee('★ Código Fonte Incluso');
        $response->assertSee('Código Fonte Incluso');
        $response->assertDontSee('Código Fonte Aberto');
        $response->assertDontSee('Código-fonte aberto');
    }

    public function test_product_page_factual_trust_badges_and_copy(): void
    {
        $product = Product::factory()->create([
            'title' => 'Produto Geral',
            'short_description' => null,
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $response = $this->get(route('storefront.show', $product->slug));
        $response->assertOk();

        // Must see factual trust badges
        $response->assertSee('Atendimento ao Cliente');
        $response->assertSee('Download Digital');
        $response->assertSee('Arquivo digital disponibilizado conforme as informações cadastradas neste produto.');

        // Must not see unverified claims
        $response->assertDontSee('Arquivos Verificados');
        $response->assertDontSee('Downloads diretos, completos e testados para máxima confiabilidade.');
        $response->assertDontSee('Arquivos verificados e prontos para utilização.');
    }

    public function test_blog_post_displays_factual_author_and_cta_without_unverified_claims(): void
    {
        $post = Post::factory()->create([
            'title' => 'Artigo Exemplo para Teste Editorial',
            'content' => '<p>Conteúdo do post editorial factual.</p>',
        ]);

        $response = $this->get(route('blog.show', $post->slug));
        $response->assertOk();

        // Factual bio and CTA
        $response->assertSee('A KL Tecnologia reúne sistemas, scripts, templates e outros produtos digitais');
        $response->assertSee('Procurando sistemas e produtos digitais?');
        $response->assertSee('Acesse nosso catálogo de sistemas, scripts, templates e outros produtos digitais para download imediato.');

        // Must not see unverified general claims
        $response->assertDontSee('scripts autorais');
        $response->assertDontSee('pioneira');
        $response->assertDontSee('centenas de sistemas');
        $response->assertDontSee('sistemas prontos e validados');
    }
}
