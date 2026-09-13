<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Product::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'category' => ['nullable', 'string', 'max:100'],
            'version' => ['nullable', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:10000'],
            'price' => ['required', 'decimal:0,2', 'min:0', 'max:99999999.99'],
            'is_active' => ['nullable', 'boolean'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'file' => ['nullable', 'file', 'mimes:zip,pdf,doc,docx,xls,xlsx,ppt,pptx,rar,7z', 'max:102400'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }
}
