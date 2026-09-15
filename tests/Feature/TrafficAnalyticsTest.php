<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PageView;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrafficAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_visit_records_a_page_view(): void
    {
        $this->get(route('storefront.index'))
            ->assertStatus(200);

        $this->assertDatabaseHas('page_views', [
            'url' => '/',
            'route_name' => 'storefront.index',
            'viewable_type' => null,
            'viewable_id' => null,
        ]);
    }

    public function test_product_detail_page_visit_records_a_page_view_linked_to_product(): void
    {
        $product = Product::factory()->create([
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $this->get(route('storefront.show', $product->slug))
            ->assertStatus(200);

        $this->assertDatabaseHas('page_views', [
            'route_name' => 'storefront.show',
            'viewable_type' => $product->getMorphClass(),
            'viewable_id' => $product->id,
        ]);
    }

    public function test_blog_post_page_visit_records_a_page_view_linked_to_post_and_increments_views(): void
    {
        $post = Post::factory()->create([
            'is_published' => true,
            'views_count' => 5,
        ]);

        $this->get(route('blog.show', $post->slug))
            ->assertStatus(200);

        $this->assertDatabaseHas('page_views', [
            'route_name' => 'blog.show',
            'viewable_type' => $post->getMorphClass(),
            'viewable_id' => $post->id,
        ]);

        $this->assertSame(6, $post->fresh()->views_count);
    }

    public function test_admin_authenticated_visit_is_not_tracked(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('storefront.index'))
            ->assertStatus(200);

        $this->assertDatabaseCount('page_views', 0);
    }

    public function test_non_get_or_admin_routes_are_not_tracked(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertStatus(200);

        $this->get('/sitemap.xml');
        $this->get('/robots.txt');

        $this->assertDatabaseCount('page_views', 0);
    }

    public function test_admin_dashboard_displays_traffic_metrics(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create([
            'title' => 'Script SaaS Laravel Pro',
            'is_active' => true,
            'file_path' => 'digital_products/sample.zip',
        ]);

        $post = Post::factory()->create([
            'title' => 'Artigo Exclusivo de Tecnologia',
            'is_published' => true,
        ]);

        // Simula acessos de visitantes
        PageView::create([
            'url' => '/produtos/'.$product->slug,
            'route_name' => 'storefront.show',
            'viewable_type' => $product->getMorphClass(),
            'viewable_id' => $product->id,
            'visitor_hash' => 'hash_test_1',
            'device_type' => 'desktop',
            'visited_at' => now(),
        ]);

        PageView::create([
            'url' => '/blog/'.$post->slug,
            'route_name' => 'blog.show',
            'viewable_type' => $post->getMorphClass(),
            'viewable_id' => $post->id,
            'visitor_hash' => 'hash_test_2',
            'device_type' => 'mobile',
            'visited_at' => now(),
        ]);

        $order = Order::factory()->paid()->create([
            'product_id' => $product->id,
            'created_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Métricas de tráfego');
        $response->assertSee('audiência');
        $response->assertSee('Visitas Hoje');
        $response->assertSee('Visitantes no Mês');
        $response->assertSee('Taxa de Conversão');
        $response->assertSee('Script SaaS Laravel Pro');
        $response->assertSee('Artigo Exclusivo de Tecnologia');
        $response->assertSee('Evolução diária de visualizações');
    }
}
