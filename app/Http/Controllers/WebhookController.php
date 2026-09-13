<?php

namespace App\Http\Controllers;

use App\Http\Requests\MercadoPagoWebhookRequest;
use App\Services\WebhookService;
use Illuminate\Http\JsonResponse;

class WebhookController extends Controller
{
    public function __invoke(MercadoPagoWebhookRequest $request, WebhookService $webhook): JsonResponse
    {
        // Se for o teste do painel do Mercado Pago ou evento não relacionado a pagamento
        if ($request->isTestSimulation() || ($request->input('type') !== 'payment' && $request->query('type') !== 'payment')) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Notificação de teste do Mercado Pago recebida com sucesso.',
            ], 200);
        }

        $paymentId = (string) ($request->validated('data.id') ?: $request->query('data.id'));

        if ($paymentId !== '') {
            $webhook->handlePayment($paymentId);
        }

        return response()->json(['received' => true], 200);
    }
}
