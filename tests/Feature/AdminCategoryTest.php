<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_category_routes(): void
    {
        $this->get(route('admin.categories.index'))->assertRedirect(route('login'));
        $this->get(route('admin.categories.create'))->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_category_routes(): void
    {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)->get(route('admin.categories.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.categories.create'))->assertForbidden();
        $this->actingAs($customer)->post(route('admin.categories.store'), [
            'name' => 'Categoria Proibida',
        ])->assertForbidden();
    }

    public function test_admin_can_view_categories_list_and_create_page(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create([
            'name' => 'Sistemas e Softwares',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.categories.index'));
        $response->assertOk();
        $response->assertSee('Sistemas e Softwares');

        $this->actingAs($admin)->get(route('admin.categories.create'))->assertOk();
    }

    public function test_admin_can_create_a_category(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Automações Comerciais',
            'description' => 'Scripts para automatizar mensagens e cobranças.',
            'icon' => 'bot',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $category = Category::query()->where('name', 'Automações Comerciais')->sole();
        $this->assertSame('automacoes-comerciais', $category->slug);
        $this->assertSame('bot', $category->icon);
        $this->assertTrue($category->is_active);
    }

    public function test_admin_can_update_a_category(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create([
            'name' => 'Nome Antigo',
            'icon' => 'code',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.categories.update', $category), [
            'name' => 'Nome Renovado',
            'icon' => 'cloud',
            'description' => 'Descrição atualizada.',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertSame('Nome Renovado', $category->fresh()->name);
        $this->assertSame('cloud', $category->fresh()->icon);
    }

    public function test_admin_can_delete_a_category_and_products_remain_intact(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['name' => 'Temporária']);

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'category' => $category->name,
        ]);

        $this->assertSame($category->id, $product->category_id);

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertNull($product->fresh()->category_id);
    }

    public function test_admin_can_assign_category_when_creating_product(): void
    {
        Storage::fake('digital_products');
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['name' => 'Sistemas Laravel']);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'title' => 'Sistema ERP Completo',
            'category_id' => $category->id,
            'category' => 'Sistemas Laravel',
            'version' => '2.5.0',
            'description' => 'Sistema ERP completo pronto para uso corporativo.',
            'price' => '199.90',
            'is_active' => '1',
            'file' => UploadedFile::fake()->create('erp.zip', 500, 'application/zip'),
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::query()->where('title', 'Sistema ERP Completo')->sole();
        $this->assertSame($category->id, $product->category_id);
        $this->assertSame('Sistemas Laravel', $product->category);
        $this->assertSame('2.5.0', $product->version);
    }
}
