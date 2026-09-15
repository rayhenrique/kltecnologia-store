<?php

namespace App\Services;

use App\Models\NewsletterSendLog;
use App\Models\NewsletterSubscriber;
use Carbon\Carbon;

class NewsletterService
{
    /**
     * Inscreve ou reativa um cliente que realizou uma compra.
     */
    public function subscribeCustomer(string $email, ?string $ip = null, ?string $userAgent = null): NewsletterSubscriber
    {
        $normalizedEmail = strtolower(trim($email));

        $subscriber = NewsletterSubscriber::withTrashed()->where('email', $normalizedEmail)->first();

        if ($subscriber) {
            if ($subscriber->trashed()) {
                $subscriber->restore();
            }

            if (! $subscriber->is_active) {
                $subscriber->update([
                    'is_active' => true,
                    'subscribed_at' => Carbon::now(),
                    'unsubscribed_at' => null,
                    'ip_address' => $ip ?? $subscriber->ip_address,
                    'user_agent' => $userAgent ?? $subscriber->user_agent,
                ]);
            }

            return $subscriber->fresh();
        }

        return NewsletterSubscriber::create([
            'email' => $normalizedEmail,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'is_active' => true,
            'subscribed_at' => Carbon::now(),
        ]);
    }

    /**
     * Cancela a inscrição de um e-mail.
     */
    public function unsubscribe(string $email): bool
    {
        $normalizedEmail = strtolower(trim($email));

        $subscriber = NewsletterSubscriber::where('email', $normalizedEmail)->first();

        if (! $subscriber) {
            return false;
        }

        $subscriber->update([
            'is_active' => false,
            'unsubscribed_at' => Carbon::now(),
        ]);

        return true;
    }

    /**
     * Gera URL segura de cancelamento de inscrição em 1 clique.
     */
    public function generateUnsubscribeUrl(string $email): string
    {
        $normalizedEmail = strtolower(trim($email));
        $token = hash_hmac('sha256', $normalizedEmail, (string) config('app.key'));

        return route('newsletter.unsubscribe', [
            'email' => $normalizedEmail,
            'token' => $token,
        ]);
    }

    /**
     * Valida o token HMAC de cancelamento de inscrição.
     */
    public function verifyUnsubscribeToken(string $email, string $token): bool
    {
        $normalizedEmail = strtolower(trim($email));
        $expectedToken = hash_hmac('sha256', $normalizedEmail, (string) config('app.key'));

        return hash_equals($expectedToken, $token);
    }

    /**
     * Retorna a quantidade de e-mails de newsletter enviados hoje.
     */
    public function getTodaySentCount(): int
    {
        return (int) NewsletterSendLog::whereDate('sent_at', Carbon::today())->count();
    }

    /**
     * Verifica se a cota diária do Gmail foi atingida.
     */
    public function hasReachedDailyLimit(): bool
    {
        $limit = (int) config('newsletter.daily_limit', 100);

        return $this->getTodaySentCount() >= $limit;
    }

    /**
     * Registra o log de envio bem-sucedido.
     */
    public function recordSendLog(string $email, ?string $notifiableType = null, ?int $notifiableId = null, string $status = 'sent', ?string $errorMessage = null): NewsletterSendLog
    {
        return NewsletterSendLog::create([
            'email' => strtolower(trim($email)),
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiableId,
            'sent_at' => Carbon::now(),
            'status' => $status,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Verifica se um e-mail já recebeu notificação para este modelo específico.
     */
    public function alreadySentTo(string $email, string $notifiableType, int $notifiableId): bool
    {
        return NewsletterSendLog::where('email', strtolower(trim($email)))
            ->where('notifiable_type', $notifiableType)
            ->where('notifiable_id', $notifiableId)
            ->where('status', 'sent')
            ->exists();
    }
}
