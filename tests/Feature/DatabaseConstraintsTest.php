<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseConstraintsTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_cannot_reference_a_missing_customer(): void
    {
        $productId = $this->createProduct();

        $this->expectException(QueryException::class);

        DB::table('orders')->insert([
            'user_id' => 999999,
            'product_id' => $productId,
            'amount' => '29.90',
        ]);
    }

    public function test_orders_cannot_reference_a_missing_product(): void
    {
        $user = User::factory()->create();

        $this->expectException(QueryException::class);

        DB::table('orders')->insert([
            'user_id' => $user->id,
            'product_id' => 999999,
            'amount' => '29.90',
        ]);
    }

    public function test_a_product_with_an_order_cannot_be_permanently_deleted(): void
    {
        $user = User::factory()->create();
        $productId = $this->createProduct();

        DB::table('orders')->insert([
            'user_id' => $user->id,
            'product_id' => $productId,
            'amount' => '29.90',
        ]);

        $this->expectException(QueryException::class);

        DB::table('products')->where('id', $productId)->delete();
    }

    public function test_deleting_a_customer_removes_their_orders_and_preserves_the_product(): void
    {
        $user = User::factory()->create();
        $productId = $this->createProduct();
        $orderId = DB::table('orders')->insertGetId([
            'user_id' => $user->id,
            'product_id' => $productId,
            'amount' => '29.90',
        ]);

        $user->delete();

        $this->assertDatabaseMissing('orders', ['id' => $orderId]);
        $this->assertDatabaseHas('products', ['id' => $productId]);
    }

    public function test_products_cannot_share_a_slug(): void
    {
        $this->createProduct();

        $this->expectException(QueryException::class);

        $this->createProduct();
    }

    private function createProduct(): int
    {
        return DB::table('products')->insertGetId([
            'title' => 'Template de teste',
            'slug' => 'template-de-teste',
            'description' => 'Produto temporário para validar integridade referencial.',
            'price' => '29.90',
            'file_path' => 'template-de-teste.zip',
        ]);
    }
}
