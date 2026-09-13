<?php

namespace App\Services;

use Illuminate\Http\Request;

class MercadoPagoSignatureVerifier
{
    public function isValid(Request $request): bool
    {
        $secret = (string) config('services.mercado_pago.webhook_secret');
        $signature = (string) $request->header('x-signature');
        $requestId = (string) $request->header('x-request-id');
        $dataId = (string) ($request->query('data.id') ?: $request->input('data.id'));

        if ($secret === '' || $signature === '' || $requestId === '' || $dataId === '') {
            return false;
        }

        $parts = collect(explode(',', $signature))->mapWithKeys(function (string $part): array {
            [$key, $value] = array_pad(explode('=', trim($part), 2), 2, '');

            return [$key => $value];
        });

        $timestamp = (string) $parts->get('ts');
        $provided = (string) $parts->get('v1');
        if ($timestamp === '' || $provided === '') {
            return false;
        }

        $manifest = 'id:'.strtolower($dataId).';request-id:'.$requestId.';ts:'.$timestamp.';';

        return hash_equals(hash_hmac('sha256', $manifest, $secret), $provided);
    }
}
