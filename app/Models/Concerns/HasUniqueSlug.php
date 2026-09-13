<?php

namespace App\Models\Concerns;

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

            if (! $model->isDirty($sourceField) && filled($model->getAttribute('slug'))) {
                return;
            }

            $sourceValue = (string) ($model->getAttribute($sourceField) ?: 'item');
            $model->setAttribute('slug', $model->generateUniqueSlug($sourceValue));
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
