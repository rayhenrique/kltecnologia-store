<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:30'],
            'featured' => ['nullable', 'string', 'max:20'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'product' => ['nullable', 'string', 'max:255'],
        ];
    }
}
