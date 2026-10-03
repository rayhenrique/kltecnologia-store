<?php

namespace App\Models\Concerns;

use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Services\SlugRedirectService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

trait HasUniqueSlug
{
    public static function bootHasUniqueSlug(): void
    {
        static::saving(function (Model $model): void {
            $sourceField = filled($model->getAttribute('title')) || $model->isDirty('title') ? 'title' : 'name';

            if ($model->exists && filled($model->getOriginal('slug')) && ! $model->isDirty('slug')) {
                return;
            }

            if (! $model->isDirty($sourceField) && filled($model->getAttribute('slug'))) {
                return;
            }

            $sourceValue = (string) ($model->getAttribute($sourceField) ?: 'item');
            $model->setAttribute('slug', $model->generateUniqueSlug($sourceValue));
        });

        static::saved(function (Model $model): void {
            if ($model->wasChanged('slug') && filled($model->getOriginal('slug'))) {
                $type = match (true) {
                    $model instanceof Product => 'product',
                    $model instanceof Post => 'post',
                    $model instanceof Category => 'category',
                    $model instanceof BlogCategory => 'blog_category',
                    default => Str::snake(class_basename($model)),
                };

                app(SlugRedirectService::class)->recordRedirect(
                    $type,
                    (string) $model->getOriginal('slug'),
                    (string) $model->getAttribute('slug')
                );
            }
        });
    }

    private function generateUniqueSlug(string $source): string
    {
        $baseSlug = Str::slug($source) ?: 'item';
        $slug = $baseSlug;
        $suffix = 2;

        while ($this->slugAlreadyExists($slug)) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function slugAlreadyExists(string $slug): bool
    {
        /** @var Builder<Model> $query */
        $query = static::query();

        if (in_array(SoftDeletes::class, class_uses_recursive(static::class), true)) {
            $query->withTrashed();
        }

        $query->where('slug', $slug);

        if ($this->exists) {
            $query->where($this->getKeyName(), '!=', $this->getKey());
        }

        return $query->exists();
    }
}
