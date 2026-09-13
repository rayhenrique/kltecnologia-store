<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_renders_and_lists_published_posts(): void
    {
        $publishedPost = Post::factory()->create([
            'title' => 'Primeiro Post Oficial',
            'is_published' => true,
        ]);

        $draftPost = Post::factory()->draft()->create([
            'title' => 'Post Rascunho Secreto',
        ]);

        $response = $this->get(route('blog.index'));

        $response->assertOk();
        $response->assertSee('Primeiro Post Oficial');
        $response->assertDontSee('Post Rascunho Secreto');
    }

    public function test_blog_can_be_filtered_by_category(): void
    {
        $phpPost = Post::factory()->create([
            'title' => 'Aprenda Laravel 12 do Zero',
            'category' => 'PHP & Laravel',
        ]);

        $marketingPost = Post::factory()->create([
            'title' => 'Guia de Vendas e Tráfego Pago',
            'category' => 'Marketing Digital',
        ]);

        $response = $this->get(route('blog.index', ['categoria' => 'PHP & Laravel']));

        $response->assertOk();
        $response->assertSee('Aprenda Laravel 12 do Zero');
        $response->assertDontSee('Guia de Vendas e Tráfego Pago');
    }

    public function test_blog_can_be_searched_by_keyword(): void
    {
        $matchingPost = Post::factory()->create([
            'title' => 'Script de Integração Mercado Pago',
        ]);

        $otherPost = Post::factory()->create([
            'title' => 'Como Criar um Bot de Discord',
        ]);

        $response = $this->get(route('blog.index', ['q' => 'Mercado Pago']));

        $response->assertOk();
        $response->assertSee('Script de Integração Mercado Pago');
        $response->assertDontSee('Como Criar um Bot de Discord');
    }

    public function test_blog_show_renders_published_post_and_increments_views(): void
    {
        $post = Post::factory()->create([
            'title' => 'Como Escalar seu SaaS em 2026',
            'views_count' => 10,
            'is_published' => true,
        ]);

        $response = $this->get(route('blog.show', $post));

        $response->assertOk();
        $response->assertSee('Como Escalar seu SaaS em 2026');

        $this->assertSame(11, $post->fresh()->views_count);

        // Refreshing within the same session should NOT increment again
        $this->get(route('blog.show', $post));
        $this->assertSame(11, $post->fresh()->views_count);
    }

    public function test_draft_post_returns_404_for_guests_and_200_for_admin(): void
    {
        $draftPost = Post::factory()->draft()->create([
            'title' => 'Artigo Ainda Não Publicado',
        ]);

        // Guest gets 404
        $this->get(route('blog.show', $draftPost))->assertNotFound();

        // Admin gets 200
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)
            ->get(route('blog.show', $draftPost))
            ->assertOk()
            ->assertSee('Artigo Ainda Não Publicado');
    }
}
