<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'gateway_reference' => null,
            'status' => OrderStatus::Pending,
            'amount' => fn (array $attributes) => Product::query()->findOrFail($attributes['product_id'])->price,
            'payment_method' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'gateway_reference' => fake()->unique()->numerify('MP-##########'),
            'status' => OrderStatus::Paid,
            'payment_method' => fake()->randomElement(['pix', 'credit_card']),
        ]);
    }
}
