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

        // 1. Flatten previous chains: any redirects that previously pointed to $oldSlug now point directly to $targetSlug
        SlugRedirect::where('model_type', $modelType)
            ->where('target_slug', $oldSlug)
            ->update(['target_slug' => $targetSlug]);

        // 2. Insert or update the redirect from $oldSlug to $targetSlug
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
