<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

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
            'slug' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'category' => ['nullable', 'string', 'max:100'],
            'version' => ['nullable', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:10000'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'seo_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'product_type' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:150'],
            'features' => ['nullable', 'string', 'max:5000'],
            'requirements' => ['nullable', 'string', 'max:5000'],
            'license' => ['nullable', 'string', 'max:2000'],
            'support_info' => ['nullable', 'string', 'max:2000'],
            'demo_url' => ['nullable', 'url', 'max:500'],
            'documentation_url' => ['nullable', 'url', 'max:500'],
            'includes_source_code' => ['nullable', 'boolean'],
            'lifetime_access' => ['nullable', 'boolean'],
            'price' => ['required', 'decimal:0,2', 'min:0', 'max:99999999.99'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'file' => ['nullable', 'file', 'mimes:zip,pdf,doc,docx,xls,xlsx,ppt,pptx,rar,7z,tar,gz', 'max:524288'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalizedSlug = filled($this->slug) ? Str::slug((string) $this->slug) : null;

        $this->merge([
            'slug' => filled($normalizedSlug) ? $normalizedSlug : null,
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
            'includes_source_code' => $this->boolean('includes_source_code'),
            'lifetime_access' => $this->boolean('lifetime_access'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($this->boolean('is_active') && ! $this->hasFile('file')) {
                $validator->errors()->add('file', 'Envie o arquivo digital antes de ativar o produto.');
            }
        });
    }
}
