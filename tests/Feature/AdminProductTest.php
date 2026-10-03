<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product_with_private_file(): void
    {
        Storage::fake('digital_products');
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'title' => 'Kit Planejamento Digital', 'description' => 'Arquivos prontos para organizar projetos.',
            'price' => '49.90', 'is_active' => '1',
            'file' => UploadedFile::fake()->create('kit.pdf', 128, 'application/pdf'),
        ]);

        $response->assertRedirect(route('admin.products.index'))->assertSessionHas('success');
        $product = Product::query()->sole();
        $this->assertSame('kit-planejamento-digital', $product->slug);
        $this->assertTrue($product->is_active);
        Storage::disk('digital_products')->assertExists($product->file_path);
    }

    public function test_customer_cannot_create_product(): void
    {
        Storage::fake('digital_products');
        $response = $this->actingAs(User::factory()->customer()->create())->post(route('admin.products.store'), [
            'title' => 'Produto indevido', 'description' => 'Não deve ser criado.', 'price' => '10.00',
            'file' => UploadedFile::fake()->create('produto.pdf', 10, 'application/pdf'),
        ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('products', 0);
    }

    public function test_admin_can_upload_a_public_cover(): void
    {
        Storage::fake('digital_products');
        $response = $this->actingAs(User::factory()->admin()->create())->post(route('admin.products.store'), [
            'title' => 'Produto com capa', 'description' => 'Descrição do produto com capa.', 'price' => '29.90',
            'is_active' => '1', 'cover' => UploadedFile::fake()->image('capa.jpg', 800, 600),
            'file' => UploadedFile::fake()->create('produto.pdf', 10, 'application/pdf'),
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $coverPath = Product::query()->sole()->cover_path;
        $this->assertNotNull($coverPath);
        $this->assertFileExists(public_path($coverPath));
        File::delete(public_path($coverPath));
    }

    public function test_admin_can_create_free_product_with_zero_price(): void
    {
        Storage::fake('digital_products');
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'title' => 'Ebook Grátis de Automação',
            'description' => 'Guia gratuito para novos clientes.',
            'price' => '0.00',
            'is_active' => '1',
            'file' => UploadedFile::fake()->create('ebook.pdf', 50, 'application/pdf'),
        ]);

        $response->assertRedirect(route('admin.products.index'))->assertSessionHas('success');
        $product = Product::where('slug', 'ebook-gratis-de-automacao')->first();
        $this->assertNotNull($product);
        $this->assertEquals(0.00, (float) $product->price);
    }

    public function test_admin_cannot_activate_product_without_digital_file(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'title' => 'Produto sem Entrega',
            'description' => 'Este produto ainda não possui arquivo.',
            'price' => '49.90',
            'is_active' => '1',
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseMissing('products', ['title' => 'Produto sem Entrega']);
    }

    public function test_admin_can_create_featured_product(): void
    {
        Storage::fake('digital_products');
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'title' => 'Sistema ERP em Destaque',
            'description' => 'Sistema premium para exibição na home.',
            'price' => '199.90',
            'is_active' => '1',
            'is_featured' => '1',
            'file' => UploadedFile::fake()->create('erp.zip', 50, 'application/zip'),
        ]);

        $response->assertRedirect(route('admin.products.index'))->assertSessionHas('success');
        $product = Product::where('slug', 'sistema-erp-em-destaque')->first();
        $this->assertNotNull($product);
        $this->assertTrue($product->is_featured);
    }

    public function test_admin_can_update_product_featured_status(): void
    {
        Storage::fake('digital_products');
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'is_featured' => false,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.products.update', $product), [
            'title' => $product->title,
            'description' => $product->description,
            'price' => (string) $product->price,
            'is_active' => '1',
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin.products.index'))->assertSessionHas('success');
        $this->assertTrue($product->fresh()->is_featured);
    }

    public function test_admin_can_update_product_without_previous_file_and_attach_file_with_spaces(): void
    {
        Storage::fake('digital_products');
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'file_path' => null,
            'is_active' => false,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.products.update', $product), [
            'title' => $product->title,
            'description' => $product->description,
            'price' => (string) $product->price,
            'is_active' => '1',
            'file' => UploadedFile::fake()->create('manual do produto 2026 versao final.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('admin.products.index'))->assertSessionHas('success');
        $freshProduct = $product->fresh();
        $this->assertTrue($freshProduct->is_active);
        $this->assertNotNull($freshProduct->file_path);
        Storage::disk('digital_products')->assertExists($freshProduct->file_path);
    }

    public function test_admin_can_update_product_via_ajax_json_request(): void
    {
        Storage::fake('digital_products');
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'file_path' => null,
            'is_active' => false,
        ]);

        $response = $this->actingAs($admin)->putJson(route('admin.products.update', $product), [
            'title' => $product->title,
            'description' => $product->description,
            'price' => (string) $product->price,
            'is_active' => '1',
            'file' => UploadedFile::fake()->create('sistema-completo.zip', 200, 'application/zip'),
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'redirect' => route('admin.products.index'),
        ]);
        $this->assertTrue($product->fresh()->is_active);
    }

    public function test_admin_can_view_products_list_with_metrics(): void
    {
        $admin = User::factory()->admin()->create();

        Product::factory()->create(['title' => 'Produto Ativo 1', 'is_active' => true, 'is_featured' => true]);
        Product::factory()->create(['title' => 'Produto Ativo 2', 'is_active' => true, 'is_featured' => false]);
        Product::factory()->create(['title' => 'Produto Inativo', 'is_active' => false, 'is_featured' => false]);

        $response = $this->actingAs($admin)->get(route('admin.products.index'));

        $response->assertOk();
        $response->assertViewHas('metrics', function (array $metrics) {
            return $metrics['total'] === 3
                && $metrics['active'] === 2
                && $metrics['inactive'] === 1
                && $metrics['featured'] === 1;
        });
        $response->assertSee('Produto Ativo 1');
        $response->assertSee('Produto Ativo 2');
        $response->assertSee('Produto Inativo');
    }

    public function test_admin_can_search_products_by_title_or_slug(): void
    {
        $admin = User::factory()->admin()->create();

        Product::factory()->create(['title' => 'Script Laravel Multi-Tenancy', 'slug' => 'script-laravel-multi-tenancy']);
        Product::factory()->create(['title' => 'Template Vue Dashboard', 'slug' => 'template-vue-dashboard']);

        // Test with parameter 'search'
        $response1 = $this->actingAs($admin)->get(route('admin.products.index', ['search' => 'Laravel']));
        $response1->assertOk();
        $response1->assertSee('Script Laravel Multi-Tenancy');
        $response1->assertDontSee('Template Vue Dashboard');

        // Test with parameter 'q'
        $response2 = $this->actingAs($admin)->get(route('admin.products.index', ['q' => 'Vue']));
        $response2->assertOk();
        $response2->assertSee('Template Vue Dashboard');
        $response2->assertDontSee('Script Laravel Multi-Tenancy');
    }

    public function test_admin_can_filter_products_by_status(): void
    {
        $admin = User::factory()->admin()->create();

        Product::factory()->create(['title' => 'Produto Apenas Ativo', 'is_active' => true]);
        Product::factory()->create(['title' => 'Produto Apenas Desativado', 'is_active' => false]);

        // Filter active
        $responseActive = $this->actingAs($admin)->get(route('admin.products.index', ['status' => 'active']));
        $responseActive->assertOk();
        $responseActive->assertSee('Produto Apenas Ativo');
        $responseActive->assertDontSee('Produto Apenas Desativado');

        // Filter inactive
        $responseInactive = $this->actingAs($admin)->get(route('admin.products.index', ['status' => 'inactive']));
        $responseInactive->assertOk();
        $responseInactive->assertSee('Produto Apenas Desativado');
        $responseInactive->assertDontSee('Produto Apenas Ativo');
    }

    public function test_admin_can_filter_products_by_featured(): void
    {
        $admin = User::factory()->admin()->create();

        Product::factory()->create(['title' => 'Produto Destaque Especial', 'is_featured' => true]);
        Product::factory()->create(['title' => 'Produto Comum Padrão', 'is_featured' => false]);

        $response = $this->actingAs($admin)->get(route('admin.products.index', ['featured' => 'featured']));
        $response->assertOk();
        $response->assertSee('Produto Destaque Especial');
        $response->assertDontSee('Produto Comum Padrão');
    }

    public function test_admin_can_create_and_edit_product_with_seo_and_commercial_fields(): void
    {
        Storage::fake('digital_products');
        $admin = User::factory()->admin()->create();

        // Check create form renders SEO section
        $createFormResponse = $this->actingAs($admin)->get(route('admin.products.create'));
        $createFormResponse->assertOk();
        $createFormResponse->assertSee('SEO & Apresentação Comercial');
        $createFormResponse->assertSee('Título SEO (Google)');
        $createFormResponse->assertSee('Meta Description');
        $createFormResponse->assertSee('Pré-visualização do Google');

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'title' => 'Sistema ERP Completo',
            'description' => 'Sistema de gestão empresarial integrado.',
            'short_description' => 'ERP robusto para controle financeiro e estoque.',
            'seo_title' => 'Sistema ERP Completo para Gestão',
            'meta_description' => 'Conheça o sistema ERP completo com suporte e documentação.',
            'product_type' => 'Sistema SaaS',
            'brand' => 'TechSoft',
            'features' => 'Módulo Financeiro, Controle de Estoque, Emissor de NF-e',
            'requirements' => 'PHP 8.2+, MySQL 8.0, 1GB RAM',
            'license' => 'Licença perpétua para 1 empresa',
            'support_info' => 'Suporte técnico por 6 meses via chamado',
            'demo_url' => 'https://erp.example.com/demo',
            'documentation_url' => 'https://erp.example.com/docs',
            'includes_source_code' => '1',
            'lifetime_access' => '1',
            'price' => '499.00',
            'is_active' => '1',
            'file' => UploadedFile::fake()->create('erp.zip', 1024, 'application/zip'),
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('title', 'Sistema ERP Completo')->firstOrFail();
        $this->assertSame('ERP robusto para controle financeiro e estoque.', $product->short_description);
        $this->assertSame('Sistema ERP Completo para Gestão', $product->seo_title);
        $this->assertSame('Conheça o sistema ERP completo com suporte e documentação.', $product->meta_description);
        $this->assertSame('Sistema SaaS', $product->product_type);
        $this->assertSame('TechSoft', $product->brand);
        $this->assertTrue($product->includes_source_code);
        $this->assertTrue($product->lifetime_access);
        $this->assertSame('https://erp.example.com/demo', $product->demo_url);
        $this->assertSame('https://erp.example.com/docs', $product->documentation_url);

        // Check edit form renders with current values
        $editFormResponse = $this->actingAs($admin)->get(route('admin.products.edit', $product));
        $editFormResponse->assertOk();
        $editFormResponse->assertSee('Sistema ERP Completo para Gestão');
        $editFormResponse->assertSee('TechSoft');

        // Update product
        $updateResponse = $this->actingAs($admin)->put(route('admin.products.update', $product), [
            'title' => 'Sistema ERP Completo v2',
            'description' => 'Sistema de gestão empresarial integrado versão 2.',
            'short_description' => 'ERP versão 2 com novas telas.',
            'seo_title' => 'Sistema ERP Completo v2 Atualizado',
            'meta_description' => 'Versão 2 do ERP completo.',
            'product_type' => 'Sistema Web',
            'brand' => 'TechSoft Group',
            'features' => 'Todas as funções anteriores mais PIX integrado',
            'requirements' => 'PHP 8.3+, MySQL 8.0',
            'license' => 'Licença vitalícia',
            'support_info' => 'Suporte vitalício',
            'demo_url' => 'https://erp2.example.com/demo',
            'documentation_url' => 'https://erp2.example.com/docs',
            'includes_source_code' => '0',
            'lifetime_access' => '1',
            'price' => '599.00',
            'is_active' => '1',
        ]);

        $updateResponse->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertSame('Sistema ERP Completo v2', $product->title);
        $this->assertSame('ERP versão 2 com novas telas.', $product->short_description);
        $this->assertSame('TechSoft Group', $product->brand);
        $this->assertFalse($product->includes_source_code);
        $this->assertTrue($product->lifetime_access);
        $this->assertSame('sistema-erp-completo', $product->slug); // slug preserved!
    }
}
