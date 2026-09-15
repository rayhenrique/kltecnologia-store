<?php

namespace App\Jobs;

use App\Mail\NewPostNewsletterMail;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Services\NewsletterService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendNewPostNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * O número de tentativas permitidas.
     */
    public int $tries = 3;

    public function __construct(
        public Post $post,
        public NewsletterSubscriber $subscriber,
    ) {}

    public function handle(NewsletterService $newsletterService): void
    {
        // Ignora se o assinante não existir ou estiver inativo
        if (! $this->subscriber->exists || ! $this->subscriber->is_active) {
            return;
        }

        // Idempotência: não envia mais de uma vez para o mesmo e-mail sobre este artigo
        if ($newsletterService->alreadySentTo($this->subscriber->email, Post::class, $this->post->id)) {
            return;
        }

        // Controle de limite diário (ex: 100/dia para Gmail)
        if ($newsletterService->hasReachedDailyLimit()) {
            $secondsUntilTomorrow = max(60, Carbon::tomorrow()->addMinutes(5)->diffInSeconds(Carbon::now()));

            Log::info("Limite diário de newsletter atingido ({$newsletterService->getTodaySentCount()}/dia). Adiou job de post para {$this->subscriber->email} em {$secondsUntilTomorrow}s.");

            $this->release($secondsUntilTomorrow);

            return;
        }

        try {
            $unsubscribeUrl = $newsletterService->generateUnsubscribeUrl($this->subscriber->email);

            Mail::to($this->subscriber->email)->send(
                new NewPostNewsletterMail($this->post, $this->subscriber->email, $unsubscribeUrl)
            );

            $newsletterService->recordSendLog(
                email: $this->subscriber->email,
                notifiableType: Post::class,
                notifiableId: $this->post->id,
                status: 'sent',
            );
        } catch (Throwable $e) {
            Log::error("Falha ao enviar newsletter de artigo para {$this->subscriber->email}: {$e->getMessage()}", [
                'post_id' => $this->post->id,
            ]);

            throw $e;
        }
    }
}
