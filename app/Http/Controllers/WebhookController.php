<?php

namespace App\Http\Controllers;

use App\Http\Requests\MercadoPagoWebhookRequest;
use App\Services\WebhookService;
use Illuminate\Http\JsonResponse;

class WebhookController extends Controller
{
    public function __invoke(MercadoPagoWebhookRequest $request, WebhookService $webhook): JsonResponse
    {
        $webhook->handlePayment((string) $request->validated('data.id'));

        return response()->json(['received' => true]);
    }
}
