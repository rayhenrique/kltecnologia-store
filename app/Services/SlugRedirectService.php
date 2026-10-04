<?php

namespace App\Services;

use App\Models\SlugRedirect;

class SlugRedirectService
{
    /**
     * Record a slug transition and prevent redirect chains.
     */
    public function recordRedirect(string $modelType, string $oldSlug, string $targetSlug): void
    {
        if ($oldSlug === $targetSlug || empty($oldSlug) || empty($targetSlug)) {
            return;
        }

        // 1. Remove qualquer redirect anterior onde old_slug seja o novo target_slug (evita loops A -> B e B -> A)
        SlugRedirect::where('model_type', $modelType)
            ->where('old_slug', $targetSlug)
            ->delete();

        // 2. Flatten previous chains: redirects que apontavam para $oldSlug agora apontam para $targetSlug
        SlugRedirect::where('model_type', $modelType)
            ->where('target_slug', $oldSlug)
            ->update(['target_slug' => $targetSlug]);

        // 3. Remove quaisquer self-redirects residuais
        SlugRedirect::where('model_type', $modelType)
            ->whereColumn('old_slug', 'target_slug')
            ->delete();

        // 4. Insert or update the redirect from $oldSlug to $targetSlug
        SlugRedirect::updateOrCreate(
            ['model_type' => $modelType, 'old_slug' => $oldSlug],
            ['target_slug' => $targetSlug]
        );
    }

    /**
     * Find target slug for a model type and old slug.
     */
    public function findTargetSlug(string $modelType, string $oldSlug): ?string
    {
        return SlugRedirect::where('model_type', $modelType)
            ->where('old_slug', $oldSlug)
            ->value('target_slug');
    }
}
