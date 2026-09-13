<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidateCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $availableProduct = Rule::exists('products', 'id')->where(fn ($query) => $query
            ->where('is_active', true)
            ->whereNotNull('file_path')
            ->where('file_path', '!=', ''));

        return [
            'code' => ['required', 'string', 'max:50'],
            'product_id' => ['nullable', 'integer', $availableProduct],
            'items' => ['nullable', 'array'],
            'items.*' => ['integer', $availableProduct],
        ];
    }
}
