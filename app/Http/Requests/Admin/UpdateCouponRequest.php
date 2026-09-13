<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $coupon = $this->route('coupon');

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('coupons', 'code')->ignore($coupon),
            ],
            'description' => ['nullable', 'string', 'max:255'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => [
                'required',
                'numeric',
                'min:0.01',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($this->input('discount_type') === 'percentage' && (float) $value > 100) {
                        $fail('O desconto percentual não pode ser maior que 100%.');
                    }
                },
            ],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'O código do cupom é obrigatório.',
            'code.unique' => 'Já existe outro cupom cadastrado com este código.',
            'code.alpha_dash' => 'O código deve conter apenas letras, números, hífens ou sublinhados.',
            'discount_type.required' => 'Selecione o tipo de desconto.',
            'discount_value.required' => 'Informe o valor do desconto.',
            'discount_value.min' => 'O valor do desconto deve ser maior que zero.',
            'product_id.exists' => 'O produto selecionado é inválido.',
            'max_uses.min' => 'O limite de utilizações deve ser de no mínimo 1.',
            'expires_at.after_or_equal' => 'A data de expiração deve ser igual ou posterior à data de início.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->input('code'))),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
