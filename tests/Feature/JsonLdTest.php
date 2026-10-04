<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JsonLdTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_renders_valid_json_ld_with_schema_context(): void
    {
        $response = $this->get(route('storefront.index'));

        $response->assertOk();
        $this->assertValidJsonLd($response->getContent(), 1);
    }

    public function test_product_renders_valid_json_ld_with_schema_context(): void
    {
        $product = Product::factory()->create([
            'is_active' => true,
            'file_path' => 'sample.zip',
        ]);

        $response = $this->get(route('storefront.show', $product->slug));

        $response->assertOk();
        $this->assertValidJsonLd($response->getContent(), 2);
    }

    public function test_post_renders_valid_json_ld_with_schema_context(): void
    {
        $post = Post::factory()->create(['is_published' => true]);

        $response = $this->get(route('blog.show', $post->slug));

        $response->assertOk();
        $this->assertValidJsonLd($response->getContent(), 2);
    }

    private function assertValidJsonLd(string $html, int $expectedCount): void
    {
        preg_match_all(
            '~<script\b[^>]*\btype\s*=\s*["\']application/ld\+json["\'][^>]*>(.*?)</script\s*>~is',
            $html,
            $matches,
        );

        $this->assertCount($expectedCount, $matches[1]);

        foreach ($matches[1] as $index => $json) {
            $schema = json_decode($json, true);

            $this->assertSame(JSON_ERROR_NONE, json_last_error(), 'JSON-LD block '.$index.': '.json_last_error_msg());
            $this->assertIsArray($schema);
            $this->assertSame('https://schema.org', $schema['@context'] ?? null, 'JSON-LD block '.$index);
            $this->assertStringNotContainsString('<?php', $json);
        }
    }
}
