<?php

namespace App\Http\Requests\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Order::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', new Enum(OrderStatus::class)],
            'payment_method' => ['nullable', 'string', 'max:60'],
            'gateway_reference' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Selecione um cliente para vincular ao pedido.',
            'user_id.exists' => 'O cliente selecionado não foi encontrado no sistema.',
            'product_id.required' => 'Selecione o produto adquirido.',
            'product_id.exists' => 'O produto selecionado é inválido.',
            'amount.required' => 'Informe o valor total do pedido.',
            'amount.numeric' => 'O valor deve ser um número válido.',
            'amount.min' => 'O valor do pedido não pode ser negativo.',
            'status.required' => 'Selecione o status do pedido.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('amount') && is_string($this->input('amount'))) {
            $raw = trim($this->input('amount'));
            if (str_contains($raw, ',') && str_contains($raw, '.')) {
                $raw = str_replace('.', '', $raw);
                $raw = str_replace(',', '.', $raw);
            } elseif (str_contains($raw, ',')) {
                $raw = str_replace(',', '.', $raw);
            }
            $raw = preg_replace('/[^0-9.]/', '', $raw);
            if ($raw !== '' && is_numeric($raw)) {
                $this->merge(['amount' => $raw]);
            }
        }
    }
}
