<?php

namespace App\Http\Controllers;

use App\Services\NewsletterService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterUnsubscribeController extends Controller
{
    /**
     * Cancela a inscrição do usuário na newsletter com validação de token seguro (HMAC).
     */
    public function unsubscribe(Request $request, NewsletterService $newsletterService): View
    {
        $email = (string) $request->query('email', '');
        $token = (string) $request->query('token', '');

        if (blank($email) || blank($token) || ! $newsletterService->verifyUnsubscribeToken($email, $token)) {
            abort(403, 'Link de descadastramento inválido ou expirado.');
        }

        $newsletterService->unsubscribe($email);

        return view('newsletter.unsubscribed', [
            'email' => $email,
        ]);
    }
}
