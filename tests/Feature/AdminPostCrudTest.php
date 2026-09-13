<?php

namespace Tests\Feature;

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
}
