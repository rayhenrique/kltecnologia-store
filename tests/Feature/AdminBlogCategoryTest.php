<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBlogCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_blog_category_routes(): void
    {
        $this->get(route('admin.blog-categories.index'))->assertRedirect(route('login'));
        $this->get(route('admin.blog-categories.create'))->assertRedirect(route('login'));
    }

    public function test_customers_cannot_access_blog_category_routes(): void
    {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)->get(route('admin.blog-categories.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.blog-categories.create'))->assertForbidden();
        $this->actingAs($customer)->post(route('admin.blog-categories.store'), [
            'name' => 'Categoria Bloqueada',
        ])->assertForbidden();
    }

    public function test_admin_can_view_blog_categories_list_and_search(): void
    {
        $admin = User::factory()->admin()->create();

        $category = BlogCategory::factory()->create([
            'name' => 'Inteligência Artificial & LLMs',
            'description' => 'Tudo sobre modelos de linguagem e prompts.',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.blog-categories.index'));
        $response->assertOk();
        $response->assertSee('Categorias do Blog');
        $response->assertSee('Inteligência Artificial & LLMs');

        // Test search
        $searchResponse = $this->actingAs($admin)->get(route('admin.blog-categories.index', ['q' => 'LLMs']));
        $searchResponse->assertOk();
        $searchResponse->assertSee('Inteligência Artificial & LLMs');

        $emptySearchResponse = $this->actingAs($admin)->get(route('admin.blog-categories.index', ['q' => 'TermoInexistenteXYZ']));
        $emptySearchResponse->assertOk();
        $emptySearchResponse->assertDontSee('Inteligência Artificial & LLMs');
    }

    public function test_admin_can_create_a_blog_category(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.blog-categories.store'), [
            'name' => 'Guias de Infraestrutura',
            'description' => 'Servidores, Docker e deploys em produção.',
            'icon' => 'shield-check',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.blog-categories.index'));
        $response->assertSessionHas('success');

        $category = BlogCategory::query()->where('name', 'Guias de Infraestrutura')->sole();
        $this->assertSame('guias-de-infraestrutura', $category->slug);
        $this->assertSame('shield-check', $category->icon);
        $this->assertTrue($category->is_active);
    }

    public function test_blog_category_validation_fails_without_name(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.blog-categories.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_a_blog_category(): void
    {
        $admin = User::factory()->admin()->create();
        $category = BlogCategory::factory()->create([
            'name' => 'Nome Inicial',
            'icon' => 'code',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.blog-categories.update', $category), [
            'name' => 'Nome Alterado',
            'icon' => 'rocket',
            'description' => 'Descrição atualizada com sucesso.',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.blog-categories.index'));
        $response->assertSessionHas('success');

        $category->refresh();
        $this->assertSame('Nome Alterado', $category->name);
        $this->assertSame('rocket', $category->icon);
        $this->assertSame('Descrição atualizada com sucesso.', $category->description);
    }

    public function test_admin_can_delete_a_blog_category_and_posts_remain(): void
    {
        $admin = User::factory()->admin()->create();
        $category = BlogCategory::factory()->create(['name' => 'Categoria Temporária']);

        $post = Post::factory()->create([
            'title' => 'Artigo sobre Tecnologia',
            'category' => $category->name,
            'blog_category_id' => $category->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.blog-categories.destroy', $category));
        $response->assertRedirect(route('admin.blog-categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('blog_categories', ['id' => $category->id]);

        $post->refresh();
        $this->assertNull($post->blog_category_id);
        $this->assertSame('Artigo sobre Tecnologia', $post->title);
    }
}
