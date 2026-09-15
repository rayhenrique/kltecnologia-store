<?php

namespace App\Services;

use App\Jobs\SendNewPostNewsletterJob;
use App\Jobs\SendNewProductNewsletterJob;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class NewsletterBroadcastService
{
    public function __construct(
        private readonly NewsletterService $newsletterService,
    ) {}

    /**
     * Dispara jobs na fila para todos os assinantes ativos sobre um novo produto.
     */
    public function broadcastNewProduct(Product $product): int
    {
        if (! $product->is_active) {
            return 0;
        }

        $subscribers = NewsletterSubscriber::query()
            ->where('is_active', true)
            ->get();

        $dispatchedCount = 0;
        $delayStep = (int) config('newsletter.delay_between_sends', 2);
        $accumulatedDelay = 0;

        foreach ($subscribers as $subscriber) {
            if ($this->newsletterService->alreadySentTo($subscriber->email, Product::class, $product->id)) {
                continue;
            }

            SendNewProductNewsletterJob::dispatch($product, $subscriber)
                ->delay(now()->addSeconds($accumulatedDelay));

            $accumulatedDelay += $delayStep;
            $dispatchedCount++;
        }

        Log::info("Newsletter Broadcast: {$dispatchedCount} jobs de produto (ID: {$product->id}) enfileirados com sucesso.");

        return $dispatchedCount;
    }

    /**
     * Dispara jobs na fila para todos os assinantes ativos sobre um novo artigo no blog.
     */
    public function broadcastNewPost(Post $post): int
    {
        if (! $post->is_published) {
            return 0;
        }

        $subscribers = NewsletterSubscriber::query()
            ->where('is_active', true)
            ->get();

        $dispatchedCount = 0;
        $delayStep = (int) config('newsletter.delay_between_sends', 2);
        $accumulatedDelay = 0;

        foreach ($subscribers as $subscriber) {
            if ($this->newsletterService->alreadySentTo($subscriber->email, Post::class, $post->id)) {
                continue;
            }

            SendNewPostNewsletterJob::dispatch($post, $subscriber)
                ->delay(now()->addSeconds($accumulatedDelay));

            $accumulatedDelay += $delayStep;
            $dispatchedCount++;
        }

        Log::info("Newsletter Broadcast: {$dispatchedCount} jobs de artigo (ID: {$post->id}) enfileirados com sucesso.");

        return $dispatchedCount;
    }
}
