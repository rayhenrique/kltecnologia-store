<?php

use App\Models\Post;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Product::query()
            ->whereNotNull('cover_path')
            ->where(function ($query): void {
                $query->where('cover_path', 'like', '%.png')
                    ->orWhere('cover_path', 'like', '%.jpg')
                    ->orWhere('cover_path', 'like', '%.jpeg');
            })
            ->get()
            ->each(function (Product $product): void {
                $webp = preg_replace('/\.(png|jpe?g)$/i', '.webp', (string) $product->getRawOriginal('cover_path'));
                if (file_exists(public_path(ltrim($webp, '/\\')))) {
                    $product->updateQuietly(['cover_path' => $webp]);
                }
            });

        Post::query()
            ->whereNotNull('cover_path')
            ->where(function ($query): void {
                $query->where('cover_path', 'like', '%.png')
                    ->orWhere('cover_path', 'like', '%.jpg')
                    ->orWhere('cover_path', 'like', '%.jpeg');
            })
            ->get()
            ->each(function (Post $post): void {
                $webp = preg_replace('/\.(png|jpe?g)$/i', '.webp', (string) $post->getRawOriginal('cover_path'));
                if (file_exists(public_path(ltrim($webp, '/\\')))) {
                    $post->updateQuietly(['cover_path' => $webp]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
