<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_coupon_routes(): void
    {
        $this->get(route('admin.coupons.index'))->assertRedirect(route('login'));
        $this->get(route('admin.coupons.create'))->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_coupon_routes(): void
    {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)->get(route('admin.coupons.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.coupons.create'))->assertForbidden();
        $this->actingAs($customer)->post(route('admin.coupons.store'), [
            'code' => 'HACK100',
            'discount_type' => 'percentage',
            'discount_value' => 100,
        ])->assertForbidden();
    }

    public function test_admin_can_view_coupons_list_and_create_page(): void
    {
        $admin = User::factory()->admin()->create();
        $coupon = Coupon::create([
            'code' => 'TESTE10',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.coupons.index'));
        $response->assertOk();
        $response->assertSee('TESTE10');
        $response->assertSee('Cupons de Desconto');

        $this->actingAs($admin)->get(route('admin.coupons.create'))->assertOk();
    }

    public function test_admin_can_create_a_storewide_percentage_coupon(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.coupons.store'), [
            'code' => 'promo25',
            'description' => 'Desconto especial de primavera',
            'discount_type' => 'percentage',
            'discount_value' => 25.00,
            'min_order_amount' => 50.00,
            'max_uses' => 100,
            'starts_at' => Carbon::now()->subDay()->format('Y-m-d\TH:i'),
            'expires_at' => Carbon::now()->addDays(15)->format('Y-m-d\TH:i'),
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $response->assertSessionHas('success');

        $coupon = Coupon::query()->where('code', 'PROMO25')->sole();
        $this->assertSame('percentage', $coupon->discount_type);
        $this->assertEquals(25.00, (float) $coupon->discount_value);
        $this->assertEquals(50.00, (float) $coupon->min_order_amount);
        $this->assertSame(100, $coupon->max_uses);
        $this->assertNull($coupon->product_id);
        $this->assertTrue($coupon->is_active);
    }

    public function test_admin_can_create_a_product_specific_fixed_coupon(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'title' => 'Sistema Financeiro SaaS',
            'price' => '150.00',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.coupons.store'), [
            'code' => 'FINANCEIRO30',
            'description' => 'R$ 30 OFF no sistema financeiro',
            'discount_type' => 'fixed',
            'discount_value' => 30.00,
            'product_id' => $product->id,
            'max_uses' => null,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $response->assertSessionHas('success');

        $coupon = Coupon::query()->where('code', 'FINANCEIRO30')->sole();
        $this->assertSame('fixed', $coupon->discount_type);
        $this->assertEquals(30.00, (float) $coupon->discount_value);
        $this->assertSame($product->id, $coupon->product_id);
        $this->assertNull($coupon->max_uses);
    }

    public function test_admin_can_update_a_coupon(): void
    {
        $admin = User::factory()->admin()->create();
        $coupon = Coupon::create([
            'code' => 'ANTIGO10',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'max_uses' => 50,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.coupons.update', $coupon), [
            'code' => 'NOVO20',
            'description' => 'Atualizado para 20%',
            'discount_type' => 'percentage',
            'discount_value' => 20.00,
            'max_uses' => 200,
            'is_active' => '0',
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $response->assertSessionHas('success');

        $updated = $coupon->fresh();
        $this->assertSame('NOVO20', $updated->code);
        $this->assertEquals(20.00, (float) $updated->discount_value);
        $this->assertSame(200, $updated->max_uses);
        $this->assertFalse($updated->is_active);
    }

    public function test_admin_can_delete_a_coupon(): void
    {
        $admin = User::factory()->admin()->create();
        $coupon = Coupon::create([
            'code' => 'DELETARME',
            'discount_type' => 'fixed',
            'discount_value' => 5.00,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.coupons.destroy', $coupon));

        $response->assertRedirect(route('admin.coupons.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('coupons', ['id' => $coupon->id]);
    }

    public function test_coupon_validation_fails_with_percentage_above_100(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.coupons.store'), [
            'code' => 'INVALID150',
            'discount_type' => 'percentage',
            'discount_value' => 150.00,
        ]);

        $response->assertSessionHasErrors(['discount_value']);
    }

    public function test_coupon_validation_fails_with_expires_at_before_starts_at(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.coupons.store'), [
            'code' => 'INVALIDDATE',
            'discount_type' => 'fixed',
            'discount_value' => 10.00,
            'starts_at' => Carbon::now()->addDays(5)->format('Y-m-d\TH:i'),
            'expires_at' => Carbon::now()->addDays(2)->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrors(['expires_at']);
    }
}
