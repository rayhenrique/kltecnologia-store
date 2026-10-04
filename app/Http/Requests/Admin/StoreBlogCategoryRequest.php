<?php

namespace App\Http\Requests\Admin;

use App\Models\BlogCategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreBlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', BlogCategory::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'seo_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'icon' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome da categoria do blog é obrigatório.',
            'name.max' => 'O nome da categoria não pode ultrapassar 100 caracteres.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalizedSlug = filled($this->slug) ? Str::slug((string) $this->slug) : null;

        $this->merge([
            'slug' => filled($normalizedSlug) ? $normalizedSlug : null,
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
