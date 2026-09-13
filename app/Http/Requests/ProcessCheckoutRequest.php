<?php

namespace App\Http\Requests;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class ProcessCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $isGuest = $this->user() === null;
        $isFree = $this->isFreeOrder();

        $rules = [
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'items' => ['nullable', 'array'],
            'items.*' => ['integer', 'exists:products,id'],
            'coupon' => ['nullable', 'string', 'max:30'],
            'terms' => ['accepted'],
        ];

        if ($isGuest) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class];
            $rules['cpf'] = [$isFree ? 'nullable' : 'required', 'string', 'max:25'];
            $rules['phone'] = [$isFree ? 'nullable' : 'required', 'string', 'max:25'];
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        } else {
            /** @var User $currentUser */
            $currentUser = $this->user();
            $rules['cpf'] = [$isFree || filled($currentUser->cpf) ? 'nullable' : 'required', 'string', 'max:25'];
            $rules['phone'] = [$isFree || filled($currentUser->phone) ? 'nullable' : 'required', 'string', 'max:25'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome completo é obrigatório.',
            'email.required' => 'O endereço de e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado. Faça login para continuar.',
            'cpf.required' => 'O CPF é obrigatório para emissão do pedido e antifraude.',
            'phone.required' => 'O telefone/WhatsApp é obrigatório para suporte.',
            'password.required' => 'A senha é obrigatória para criar sua conta de acesso aos downloads.',
            'password.confirmed' => 'A confirmação de senha não confere.',
            'terms.accepted' => 'Você precisa ler e concordar com os Termos de Uso e a Política de Privacidade para finalizar o pedido.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $hasProductId = filled($this->input('product_id'));
            $items = (array) $this->input('items', []);
            $hasItems = count($items) > 0;

            if (! $hasProductId && ! $hasItems) {
                $validator->errors()->add('product_id', 'Nenhum produto foi selecionado para compra. Adicione um item ao carrinho ou selecione um produto.');
            }
        });
    }

    private function isFreeOrder(): bool
    {
        $productId = $this->input('product_id');
        $itemIds = (array) $this->input('items', []);
        $ids = array_filter(array_unique(array_merge($productId ? [(int) $productId] : [], array_map('intval', $itemIds))));
        if (empty($ids)) {
            return false;
        }

        $total = (float) Product::whereIn('id', $ids)->where('is_active', true)->sum('price');
        $coupon = strtoupper(trim((string) $this->input('coupon')));
        if ($coupon === 'FREE100' || $coupon === 'GRATIS100') {
            return true;
        }

        if ($coupon !== '') {
            $couponModel = Coupon::where('code', $coupon)->first();
            if ($couponModel) {
                $products = Product::whereIn('id', $ids)->where('is_active', true)->get();
                $eval = $couponModel->evaluate($products, $total);
                if ($eval['valid'] && $eval['discount_amount'] >= $total) {
                    return true;
                }
            }
        }

        return $total <= 0.0;
    }
}
