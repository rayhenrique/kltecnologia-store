<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'excerpt',
        'content',
        'cover_path',
        'is_published',
        'views_count',
        'published_at',
    ];

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
