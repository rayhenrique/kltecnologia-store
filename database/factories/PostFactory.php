<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        return [
            'title' => $title,
            'category' => fake()->randomElement(['Tutoriais & Dicas', 'Sistemas PHP', 'SaaS & Negócios', 'Marketing Digital']),
            'excerpt' => fake()->paragraph(2),
            'content' => '<h2>'.fake()->sentence().'</h2><p>'.fake()->paragraphs(3, true).'</p>',
            'cover_path' => null,
            'is_published' => true,
            'views_count' => fake()->numberBetween(0, 500),
            'published_at' => now(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
