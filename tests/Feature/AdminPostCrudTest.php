<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminPostCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin_posts(): void
    {
        $this->get(route('admin.posts.index'))->assertRedirect(route('login'));
        $this->get(route('admin.posts.create'))->assertRedirect(route('login'));
    }

    public function test_regular_customer_cannot_access_admin_posts(): void
    {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)->get(route('admin.posts.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.posts.create'))->assertForbidden();
        $this->actingAs($customer)->post(route('admin.posts.store'), [
            'title' => 'Invasão',
            'content' => 'Tentativa de criar post sem permissão.',
        ])->assertForbidden();
    }

    public function test_admin_can_view_posts_index_and_create_page(): void
    {
        $admin = User::factory()->admin()->create();
        Post::factory()->create(['title' => 'Artigo Cadastrado']);

        $response = $this->actingAs($admin)->get(route('admin.posts.index'));
        $response->assertOk();
        $response->assertSee('Artigo Cadastrado');

        $this->actingAs($admin)->get(route('admin.posts.create'))->assertOk();
    }

    public function test_admin_can_create_a_new_post_with_cover(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Novo Artigo de Teste',
            'category' => 'Tutoriais & Dicas',
            'excerpt' => 'Resumo do artigo criado pelo teste.',
            'content' => '<h2>Subtítulo</h2><p>Conteúdo explicativo completo.</p>',
            'is_published' => '1',
            'cover' => UploadedFile::fake()->image('capa-post.jpg', 1200, 630),
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $response->assertSessionHas('success');

        $post = Post::query()->where('title', 'Novo Artigo de Teste')->sole();
        $this->assertSame('novo-artigo-de-teste', $post->slug);
        $this->assertSame('Tutoriais & Dicas', $post->category);
        $this->assertTrue($post->is_published);
        $this->assertNotNull($post->cover_path);
        $this->assertFileExists(public_path($post->cover_path));

        // Clean up created file
        File::delete(public_path($post->cover_path));
    }

    public function test_admin_can_update_a_post(): void
    {
        $admin = User::factory()->admin()->create();
        $post = Post::factory()->create([
            'title' => 'Título Antigo',
            'category' => 'Geral',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.posts.update', $post), [
            'title' => 'Título Atualizado',
            'category' => 'PHP & Laravel',
            'content' => '<p>Conteúdo atualizado.</p>',
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $response->assertSessionHas('success');

        $this->assertSame('Título Atualizado', $post->fresh()->title);
        $this->assertSame('PHP & Laravel', $post->fresh()->category);
    }

    public function test_admin_can_delete_a_post(): void
    {
        $admin = User::factory()->admin()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.posts.destroy', $post));

        $response->assertRedirect(route('admin.posts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_admin_creates_post_persisting_seo_title_and_meta_description(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Artigo com SEO Customizado',
            'seo_title' => 'Melhor Tutorial Laravel 2026',
            'meta_description' => 'Aprenda tudo sobre arquitetura moderna de software com Laravel.',
            'category' => 'Tutoriais',
            'content' => '<p>Conteúdo do artigo com SEO.</p>',
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $post = Post::where('title', 'Artigo com SEO Customizado')->sole();
        $this->assertSame('Melhor Tutorial Laravel 2026', $post->seo_title);
        $this->assertSame('Aprenda tudo sobre arquitetura moderna de software com Laravel.', $post->meta_description);
    }

    public function test_admin_updates_post_altering_seo_title_and_meta_description(): void
    {
        $admin = User::factory()->admin()->create();
        $post = Post::factory()->create([
            'title' => 'Artigo Inicial',
            'seo_title' => 'SEO Antigo',
            'meta_description' => 'Desc Antiga',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.posts.update', $post), [
            'title' => 'Artigo Inicial',
            'seo_title' => 'SEO Novo e Otimizado',
            'meta_description' => 'Nova descrição meta altamente relevante.',
            'category' => 'Geral',
            'content' => '<p>Conteúdo mantido.</p>',
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $this->assertSame('SEO Novo e Otimizado', $post->fresh()->seo_title);
        $this->assertSame('Nova descrição meta altamente relevante.', $post->fresh()->meta_description);
    }

    public function test_admin_can_clear_seo_fields_on_post_update(): void
    {
        $admin = User::factory()->admin()->create();
        $post = Post::factory()->create([
            'title' => 'Artigo com SEO para Limpar',
            'seo_title' => 'SEO para ser removido',
            'meta_description' => 'Meta description para ser removida',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.posts.update', $post), [
            'title' => 'Artigo com SEO para Limpar',
            'seo_title' => '',
            'meta_description' => '',
            'category' => 'Geral',
            'content' => '<p>Conteúdo mantido.</p>',
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $this->assertNull($post->fresh()->seo_title);
        $this->assertNull($post->fresh()->meta_description);
    }

    public function test_post_validation_standardized_limits_for_store_and_update(): void
    {
        $admin = User::factory()->admin()->create();
        $post = Post::factory()->create();

        // 1. Store: exceeds 70 chars for seo_title and 160 for meta_description
        $responseStore = $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Post Limite Teste',
            'seo_title' => str_repeat('a', 71),
            'meta_description' => str_repeat('b', 161),
            'content' => '<p>Conteúdo</p>',
        ]);
        $responseStore->assertSessionHasErrors(['seo_title', 'meta_description']);

        // 2. Update: exceeds 70 chars for seo_title and 160 for meta_description
        $responseUpdate = $this->actingAs($admin)->put(route('admin.posts.update', $post), [
            'title' => 'Post Limite Teste',
            'seo_title' => str_repeat('a', 71),
            'meta_description' => str_repeat('b', 161),
            'content' => '<p>Conteúdo</p>',
        ]);
        $responseUpdate->assertSessionHasErrors(['seo_title', 'meta_description']);
    }

    public function test_admin_can_remove_category_from_post(): void
    {
        $admin = User::factory()->admin()->create();
        $blogCategory = BlogCategory::factory()->create([
            'name' => 'Tutoriais Avançados',
            'is_active' => true,
        ]);

        $post = Post::factory()->create([
            'title' => 'Artigo com Categoria Inicial',
            'blog_category_id' => $blogCategory->id,
            'category' => $blogCategory->name,
        ]);

        $this->assertSame($blogCategory->id, $post->blog_category_id);
        $this->assertSame('Tutoriais Avançados', $post->blogCategory->name);

        // Atualização enviando blog_category_id vazio ("" ou null)
        $response = $this->actingAs($admin)->put(route('admin.posts.update', $post), [
            'title' => 'Artigo com Categoria Removida',
            'blog_category_id' => '',
            'content' => '<p>Conteúdo mantido sem categoria.</p>',
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $response->assertSessionHas('success');

        $freshPost = $post->fresh();
        $this->assertNull($freshPost->blog_category_id);
        $this->assertNull($freshPost->category);
        $this->assertNull($freshPost->blogCategory);
        $this->assertNotSame('Tutoriais Avançados', $freshPost->category);
    }
}
