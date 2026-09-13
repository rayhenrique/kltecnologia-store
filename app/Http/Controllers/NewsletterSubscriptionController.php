<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterSubscriberRequest;
use App\Models\NewsletterSubscriber;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class NewsletterSubscriptionController extends Controller
{
    public function store(StoreNewsletterSubscriberRequest $request): JsonResponse|RedirectResponse
    {
        $email = strtolower(trim((string) $request->validated('email')));

        $subscriber = NewsletterSubscriber::withTrashed()->where('email', $email)->first();

        if ($subscriber) {
            if ($subscriber->trashed()) {
                $subscriber->restore();
            }

            if (! $subscriber->is_active) {
                $subscriber->update([
                    'is_active' => true,
                    'subscribed_at' => Carbon::now(),
                    'unsubscribed_at' => null,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            $message = 'Inscrição confirmada! Você já está em nossa lista de novidades.';
        } else {
            NewsletterSubscriber::create([
                'email' => $email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_active' => true,
                'subscribed_at' => Carbon::now(),
            ]);

            $message = 'Inscrição realizada com sucesso! Você receberá nossos lançamentos e materiais exclusivos.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
