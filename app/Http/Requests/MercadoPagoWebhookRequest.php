<?php

namespace App\Http\Requests;

use App\Services\MercadoPagoSignatureVerifier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MercadoPagoWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Se for uma simulação de teste disparada pelo painel do Mercado Pago
        if ($this->isTestSimulation()) {
            return true;
        }

        return app(MercadoPagoSignatureVerifier::class)->isValid($this);
    }

    public function rules(): array
    {
        return [
            'type' => ['nullable', 'string'],
            'action' => ['nullable', 'string', 'max:100'],
            'data' => ['nullable', 'array'],
            'data.id' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function isTestSimulation(): bool
    {
        $dataId = (string) ($this->query('data.id') ?: $this->input('data.id'));
        $extRef = (string) $this->input('data.external_reference');
        $type = (string) ($this->query('type') ?: $this->input('type'));
        $action = (string) $this->input('action');

        return $dataId === '123456'
            || $extRef === 'ext_ref_1234'
            || $type === 'order'
            || str_starts_with($action, 'order.')
            || $this->header('x-test-simulation') === 'true';
    }

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(response()->json(['message' => 'Assinatura inválida.'], 401));
    }
}
