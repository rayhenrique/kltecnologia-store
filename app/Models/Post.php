<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory, HasUniqueSlug;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'category',
        'blog_category_id',
        'excerpt',
        'content',
        'cover_path',
        'is_published',
        'views_count',
        'published_at',
    ];

    /**
     * @return BelongsTo<BlogCategory, $this>
     */
    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_published' => true,
        'views_count' => 0,
    ];

    /**
     * @param  Builder<Post>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags((string) $this->content));

        return max(1, (int) ceil($wordCount / 200));
    }

    public function getCoverPathAttribute(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (preg_match('/\.(png|jpe?g)$/i', $value)) {
            $webp = preg_replace('/\.(png|jpe?g)$/i', '.webp', $value);
            if (file_exists(public_path(ltrim($webp, '/\\')))) {
                return $webp;
            }
        }

        return $value;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'views_count' => 'integer',
            'published_at' => 'datetime',
        ];
    }
}
