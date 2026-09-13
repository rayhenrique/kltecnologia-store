<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CouponValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_validate_storewide_percentage_coupon_successfully(): void
    {
        $product = Product::factory()->create(['price' => '100.00', 'is_active' => true]);

        Coupon::create([
            'code' => 'BLACK20',
            'discount_type' => 'percentage',
            'discount_value' => 20.00,
            'is_active' => true,
        ]);

        $response = $this->postJson(route('coupons.validate'), [
            'code' => 'black20',
            'items' => [$product->id],
        ]);

        $response->assertOk()
            ->assertJson([
                'valid' => true,
                'code' => 'BLACK20',
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'discount_amount' => 20.00,
                'is_free' => false,
            ]);
    }

    public function test_validate_product_specific_coupon_succeeds_for_matching_product(): void
    {
        $product = Product::factory()->create(['price' => '80.00', 'is_active' => true]);

        Coupon::create([
            'code' => 'SPECIFIC15',
            'discount_type' => 'fixed',
            'discount_value' => 15.00,
            'product_id' => $product->id,
            'is_active' => true,
        ]);

        $response = $this->postJson(route('coupons.validate'), [
            'code' => 'SPECIFIC15',
            'items' => [$product->id],
        ]);

        $response->assertOk()
            ->assertJson([
                'valid' => true,
                'code' => 'SPECIFIC15',
                'discount_type' => 'fixed',
                'discount_value' => 15.00,
                'discount_amount' => 15.00,
            ]);
    }

    public function test_validate_product_specific_coupon_fails_for_different_product(): void
    {
        $productA = Product::factory()->create(['price' => '80.00', 'is_active' => true]);
        $productB = Product::factory()->create(['price' => '90.00', 'is_active' => true]);

        Coupon::create([
            'code' => 'ONLYA',
            'discount_type' => 'percentage',
            'discount_value' => 50.00,
            'product_id' => $productA->id,
            'is_active' => true,
        ]);

        $response = $this->postJson(route('coupons.validate'), [
            'code' => 'ONLYA',
            'items' => [$productB->id],
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
            ]);
    }

    public function test_validate_expired_coupon_fails(): void
    {
        $product = Product::factory()->create(['price' => '100.00', 'is_active' => true]);

        Coupon::create([
            'code' => 'EXPIRED',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'expires_at' => Carbon::now()->subDay(),
            'is_active' => true,
        ]);

        $response = $this->postJson(route('coupons.validate'), [
            'code' => 'EXPIRED',
            'items' => [$product->id],
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
                'message' => 'Este cupom expirou.',
            ]);
    }

    public function test_validate_future_coupon_fails(): void
    {
        $product = Product::factory()->create(['price' => '100.00', 'is_active' => true]);

        Coupon::create([
            'code' => 'FUTURE',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'starts_at' => Carbon::now()->addDays(2),
            'is_active' => true,
        ]);

        $response = $this->postJson(route('coupons.validate'), [
            'code' => 'FUTURE',
            'items' => [$product->id],
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
            ]);
    }

    public function test_validate_usage_limit_reached_coupon_fails(): void
    {
        $product = Product::factory()->create(['price' => '100.00', 'is_active' => true]);

        Coupon::create([
            'code' => 'LIMIT5',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'max_uses' => 5,
            'times_used' => 5,
            'is_active' => true,
        ]);

        $response = $this->postJson(route('coupons.validate'), [
            'code' => 'LIMIT5',
            'items' => [$product->id],
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
                'message' => 'O limite máximo de utilizações deste cupom foi atingido.',
            ]);
    }

    public function test_validate_min_order_amount_fails_when_below_threshold(): void
    {
        $product = Product::factory()->create(['price' => '40.00', 'is_active' => true]);

        Coupon::create([
            'code' => 'MIN100',
            'discount_type' => 'fixed',
            'discount_value' => 20.00,
            'min_order_amount' => 100.00,
            'is_active' => true,
        ]);

        $response = $this->postJson(route('coupons.validate'), [
            'code' => 'MIN100',
            'items' => [$product->id],
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
            ]);
    }

    public function test_validate_inactive_coupon_fails(): void
    {
        $product = Product::factory()->create(['price' => '50.00', 'is_active' => true]);

        Coupon::create([
            'code' => 'INACTIVE',
            'discount_type' => 'percentage',
            'discount_value' => 15.00,
            'is_active' => false,
        ]);

        $response = $this->postJson(route('coupons.validate'), [
            'code' => 'INACTIVE',
            'items' => [$product->id],
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
                'message' => 'Este cupom está temporariamente inativo.',
            ]);
    }

    public function test_legacy_test_coupons_are_rejected_when_not_registered(): void
    {
        $product = Product::factory()->create(['price' => '100.00', 'is_active' => true]);

        $response = $this->postJson(route('coupons.validate'), [
            'code' => 'VIP10',
            'items' => [$product->id],
        ]);

        $response->assertUnprocessable()
            ->assertJson(['valid' => false]);
    }

    public function test_checkout_applies_dynamic_coupon_and_increments_usage(): void
    {
        $customer = User::factory()->customer()->create();
        $product = Product::factory()->create([
            'price' => '100.00',
            'is_active' => true,
        ]);

        $coupon = Coupon::create([
            'code' => 'DESCONTO100',
            'discount_type' => 'percentage',
            'discount_value' => 100.00,
            'times_used' => 0,
            'is_active' => true,
        ]);

        Http::assertNothingSent();

        $response = $this->actingAs($customer)->post(route('checkout.process'), [
            'product_id' => $product->id,
            'coupon' => 'DESCONTO100',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('customer.downloads'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Paid->value,
            'amount' => '0.00',
            'payment_method' => 'free',
        ]);

        $this->assertSame(1, $coupon->fresh()->times_used);
    }
}
