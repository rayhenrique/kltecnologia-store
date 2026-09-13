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
}
