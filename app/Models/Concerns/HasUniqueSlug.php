<?php

namespace App\Models\Concerns;

use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\SlugRedirect;
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

            // Se o model já existe e já possui um slug:
            if ($model->exists && filled($model->getOriginal('slug'))) {
                // Se o slug não foi preenchido na alteração OU não foi modificado: preserva o original
                if (! filled($model->getAttribute('slug')) || ! $model->isDirty('slug')) {
                    $model->setAttribute('slug', (string) $model->getOriginal('slug'));

                    return;
                }

                // Se o novo valor é equivalente ao slug original quando normalizado: preserva o original
                if (Str::slug((string) $model->getAttribute('slug')) === (string) $model->getOriginal('slug')) {
                    $model->setAttribute('slug', (string) $model->getOriginal('slug'));

                    return;
                }
            }

            // Se um slug explícito foi fornecido (criação ou edição com novo slug):
            if (filled($model->getAttribute('slug'))) {
                $manualSlug = Str::slug((string) $model->getAttribute('slug'));
                if (filled($manualSlug)) {
                    $model->setAttribute('slug', $model->generateUniqueSlug($manualSlug));

                    return;
                }
            }

            // Fallback: se o slug estiver vazio (ou não informado), gera a partir de title/name
            $sourceValue = (string) ($model->getAttribute($sourceField) ?: 'item');
            $model->setAttribute('slug', $model->generateUniqueSlug($sourceValue));
        });

        static::saved(function (Model $model): void {
            $type = match (true) {
                $model instanceof Product => 'product',
                $model instanceof Post => 'post',
                $model instanceof Category => 'category',
                $model instanceof BlogCategory => 'blog_category',
                default => Str::snake(class_basename($model)),
            };

            $currentSlug = (string) $model->getAttribute('slug');

            if (filled($currentSlug)) {
                // Precedência do registro ativo: remove qualquer redirect que apontasse este slug para outro lugar
                SlugRedirect::where('model_type', $type)
                    ->where('old_slug', $currentSlug)
                    ->delete();
            }

            if ($model->wasChanged('slug') && filled($model->getOriginal('slug'))) {
                app(SlugRedirectService::class)->recordRedirect(
                    $type,
                    (string) $model->getOriginal('slug'),
                    $currentSlug
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
