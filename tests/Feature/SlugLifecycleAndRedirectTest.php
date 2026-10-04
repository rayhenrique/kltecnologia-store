<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SlugLifecycleAndRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_slug_change_returns_200_for_new_and_301_for_old_slug(): void
    {
        $product = Product::factory()->create([
            'title' => 'Sistema ERP Antigo',
            'slug' => 'sistema-erp-antigo',
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $this->get('/produtos/sistema-erp-antigo')->assertOk();

        // Change slug
        $product->slug = 'sistema-erp-novo';
        $product->save();

        $this->get('/produtos/sistema-erp-novo')->assertOk();

        $oldResponse = $this->get('/produtos/sistema-erp-antigo');
        $oldResponse->assertStatus(301);
        $oldResponse->assertRedirect(route('storefront.show', 'sistema-erp-novo'));
    }

    public function test_category_slug_change_returns_200_for_new_and_301_for_old_slug(): void
    {
        $category = Category::factory()->create([
            'name' => 'Categoria Antiga',
            'slug' => 'categoria-antiga',
            'is_active' => true,
        ]);

        $this->get('/catalogo/categoria-antiga')->assertOk();

        // Change slug
        $category->slug = 'categoria-nova';
        $category->save();

        $this->get('/catalogo/categoria-nova')->assertOk();

        $oldResponse = $this->get('/catalogo/categoria-antiga');
        $oldResponse->assertStatus(301);
        $oldResponse->assertRedirect(route('catalog.category', 'categoria-nova'));
    }

    public function test_post_slug_change_returns_200_for_new_and_301_for_old_slug(): void
    {
        $post = Post::factory()->create([
            'title' => 'Artigo Antigo',
            'slug' => 'artigo-antigo',
        ]);

        $this->get('/blog/artigo-antigo')->assertOk();

        // Change slug
        $post->slug = 'artigo-novo';
        $post->save();

        $this->get('/blog/artigo-novo')->assertOk();

        $oldResponse = $this->get('/blog/artigo-antigo');
        $oldResponse->assertStatus(301);
        $oldResponse->assertRedirect(route('blog.show', 'artigo-novo'));
    }

    public function test_blog_category_slug_change_returns_200_for_new_and_301_for_old_slug(): void
    {
        $blogCategory = BlogCategory::factory()->create([
            'name' => 'Tutoriais Antigos',
            'slug' => 'tutoriais-antigos',
            'is_active' => true,
        ]);

        $this->get('/blog/categoria/tutoriais-antigos')->assertOk();

        // Change slug
        $blogCategory->slug = 'tutoriais-novos';
        $blogCategory->save();

        $this->get('/blog/categoria/tutoriais-novos')->assertOk();

        $oldResponse = $this->get('/blog/categoria/tutoriais-antigos');
        $oldResponse->assertStatus(301);
        $oldResponse->assertRedirect(route('blog.category', 'tutoriais-novos'));
    }

    public function test_editing_only_title_or_name_does_not_change_existing_slug(): void
    {
        $product = Product::factory()->create([
            'title' => 'Nome Inicial do Produto',
            'slug' => 'slug-personalizado-inicial',
        ]);

        $product->title = 'Nome Completamente Alterado do Produto';
        $product->save();

        $this->assertEquals('slug-personalizado-inicial', $product->fresh()->slug);

        $category = Category::factory()->create([
            'name' => 'Nome Inicial Categoria',
            'slug' => 'cat-personalizada-inicial',
        ]);

        $category->name = 'Nome Alterado Categoria';
        $category->save();

        $this->assertEquals('cat-personalizada-inicial', $category->fresh()->slug);

        $post = Post::factory()->create([
            'title' => 'Título Inicial do Post',
            'slug' => 'post-personalizado-inicial',
        ]);

        $post->title = 'Título Alterado do Post';
        $post->save();

        $this->assertEquals('post-personalizado-inicial', $post->fresh()->slug);

        $blogCategory = BlogCategory::factory()->create([
            'name' => 'Nome Inicial Blog Cat',
            'slug' => 'bcat-personalizada-inicial',
        ]);

        $blogCategory->name = 'Nome Alterado Blog Cat';
        $blogCategory->save();

        $this->assertEquals('bcat-personalizada-inicial', $blogCategory->fresh()->slug);
    }

    public function test_manual_slug_is_normalized_with_str_slug(): void
    {
        $product = Product::factory()->create([
            'title' => 'Qualquer Titulo',
            'slug' => 'Slug Manual Com Acentuação & Espaços!',
        ]);

        $this->assertEquals('slug-manual-com-acentuacao-espacos', $product->slug);

        $category = Category::factory()->create([
            'name' => 'Qualquer Categoria',
            'slug' => 'Categoria Especial / 2026',
        ]);

        $this->assertEquals('categoria-especial-2026', $category->slug);
    }

    public function test_duplicate_slug_gets_unique_suffix_and_does_not_overwrite_existing(): void
    {
        $first = Product::factory()->create([
            'title' => 'Sistema ERP',
            'slug' => 'sistema-erp',
        ]);

        $second = Product::factory()->create([
            'title' => 'Outro Sistema ERP',
            'slug' => 'sistema-erp',
        ]);

        $this->assertEquals('sistema-erp', $first->slug);
        $this->assertEquals('sistema-erp-2', $second->slug);
    }

    public function test_redirect_chain_is_flattened_and_avoids_loops(): void
    {
        $product = Product::factory()->create([
            'title' => 'Script Evolutivo',
            'slug' => 'slug-a',
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        // A -> B
        $product->slug = 'slug-b';
        $product->save();

        // B -> C
        $product->slug = 'slug-c';
        $product->save();

        // Both slug-a and slug-b should redirect directly to slug-c (flattened)
        $resA = $this->get('/produtos/slug-a');
        $resA->assertStatus(301);
        $resA->assertRedirect(route('storefront.show', 'slug-c'));

        $resB = $this->get('/produtos/slug-b');
        $resB->assertStatus(301);
        $resB->assertRedirect(route('storefront.show', 'slug-c'));

        // C should respond 200
        $this->get('/produtos/slug-c')->assertOk();

        // No self-redirects exist
        $this->assertDatabaseMissing('slug_redirects', [
            'model_type' => 'product',
            'old_slug' => 'slug-c',
        ]);
    }

    public function test_reusing_historical_redirect_slug_gives_active_record_precedence(): void
    {
        $product = Product::factory()->create([
            'title' => 'Agenda Online',
            'slug' => 'agenda-online',
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        // Transition: agenda-online -> agenda-pro
        $product->slug = 'agenda-pro';
        $product->save();

        $this->assertDatabaseHas('slug_redirects', [
            'model_type' => 'product',
            'old_slug' => 'agenda-online',
            'target_slug' => 'agenda-pro',
        ]);

        // Now revert back to agenda-online: active record must take precedence
        $product->slug = 'agenda-online';
        $product->save();

        // Active slug responds 200 directly
        $this->get('/produtos/agenda-online')->assertOk();

        // And no redirect points agenda-online away
        $this->assertDatabaseMissing('slug_redirects', [
            'model_type' => 'product',
            'old_slug' => 'agenda-online',
        ]);
    }

    public function test_never_existing_slug_returns_404_for_all_entities(): void
    {
        $this->get('/produtos/slug-totalmente-inexistente')->assertNotFound();
        $this->get('/catalogo/slug-totalmente-inexistente')->assertNotFound();
        $this->get('/blog/slug-totalmente-inexistente')->assertNotFound();
        $this->get('/blog/categoria/slug-totalmente-inexistente')->assertNotFound();
    }

    public function test_admin_can_manually_control_slug_in_admin_controllers(): void
    {
        $admin = User::factory()->admin()->create();

        // 1. Product manual slug on create
        $productResponse = $this->actingAs($admin)->post(route('admin.products.store'), [
            'title' => 'Gestor Imobiliário Web',
            'slug' => 'meu-slug-manual-imobiliario',
            'price' => 99.90,
            'description' => 'Descrição completa do gestor imobiliário para testes.',
            'category' => 'Sistemas Web',
            'is_active' => 1,
            'cover' => UploadedFile::fake()->image('cover.jpg', 600, 600),
            'file' => UploadedFile::fake()->create('sistema.zip', 1024),
        ]);
        $productResponse->assertRedirect(route('admin.products.index'));
        $createdProduct = Product::where('slug', 'meu-slug-manual-imobiliario')->first();
        $this->assertNotNull($createdProduct);

        // Product manual slug on update
        $updateResponse = $this->actingAs($admin)->put(route('admin.products.update', $createdProduct), [
            'title' => 'Gestor Imobiliário Web Pro',
            'slug' => 'imobiliario-pro-atualizado',
            'price' => 129.90,
            'description' => 'Descrição completa atualizada.',
            'category' => 'Sistemas Web',
            'is_active' => 1,
        ]);
        $updateResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $createdProduct->id,
            'slug' => 'imobiliario-pro-atualizado',
        ]);
        $this->assertDatabaseHas('slug_redirects', [
            'model_type' => 'product',
            'old_slug' => 'meu-slug-manual-imobiliario',
            'target_slug' => 'imobiliario-pro-atualizado',
        ]);

        // 2. Category manual slug on create & update
        $catResponse = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Templates WordPress',
            'slug' => 'wp-templates-custom',
            'is_active' => 1,
        ]);
        $catResponse->assertRedirect(route('admin.categories.index'));
        $createdCat = Category::where('slug', 'wp-templates-custom')->first();
        $this->assertNotNull($createdCat);

        $catUpdate = $this->actingAs($admin)->put(route('admin.categories.update', $createdCat), [
            'name' => 'Templates WordPress Premium',
            'slug' => 'wp-templates-premium',
            'is_active' => 1,
        ]);
        $catUpdate->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $createdCat->id,
            'slug' => 'wp-templates-premium',
        ]);

        // 3. Post manual slug on create & update
        $postResponse = $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Guia Laravel 13',
            'slug' => 'guia-completo-laravel-13',
            'content' => '<p>Conteúdo completo do artigo sobre Laravel 13.</p>',
            'is_published' => 1,
        ]);
        $postResponse->assertRedirect(route('admin.posts.index'));
        $createdPost = Post::where('slug', 'guia-completo-laravel-13')->first();
        $this->assertNotNull($createdPost);

        $postUpdate = $this->actingAs($admin)->put(route('admin.posts.update', $createdPost), [
            'title' => 'Guia Definitivo Laravel 13',
            'slug' => 'guia-definitivo-laravel-13',
            'content' => '<p>Conteúdo atualizado.</p>',
            'is_published' => 1,
        ]);
        $postUpdate->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseHas('posts', [
            'id' => $createdPost->id,
            'slug' => 'guia-definitivo-laravel-13',
        ]);

        // 4. BlogCategory manual slug on create & update
        $blogCatResponse = $this->actingAs($admin)->post(route('admin.blog-categories.store'), [
            'name' => 'Tutoriais de PHP',
            'slug' => 'php-tutoriais-slug',
            'is_active' => 1,
        ]);
        $blogCatResponse->assertRedirect(route('admin.blog-categories.index'));
        $createdBlogCat = BlogCategory::where('slug', 'php-tutoriais-slug')->first();
        $this->assertNotNull($createdBlogCat);

        $blogCatUpdate = $this->actingAs($admin)->put(route('admin.blog-categories.update', $createdBlogCat), [
            'name' => 'Tutoriais Avançados de PHP',
            'slug' => 'php-avancado-slug',
            'is_active' => 1,
        ]);
        $blogCatUpdate->assertRedirect(route('admin.blog-categories.index'));
        $this->assertDatabaseHas('blog_categories', [
            'id' => $createdBlogCat->id,
            'slug' => 'php-avancado-slug',
        ]);
    }
}
