<?php

namespace App\Http\Requests;

use App\Services\MercadoPagoSignatureVerifier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MercadoPagoWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(MercadoPagoSignatureVerifier::class)->isValid($this);
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:payment'],
            'action' => ['nullable', 'string', 'max:100'],
            'data' => ['required', 'array'],
            'data.id' => ['required', 'string', 'max:100'],
        ];
    }

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(response()->json(['message' => 'Assinatura inválida.'], 401));
    }
}
