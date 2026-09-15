<?php

namespace App\Http\Requests\Admin;

use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewsletterSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', NewsletterSubscriber::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('newsletter_subscribers', 'email')->whereNull('deleted_at'),
            ],
            'is_active' => ['nullable', 'boolean'],
            'subscribed_at' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'O endereço de e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está inscrito na newsletter.',
            'subscribed_at.date' => 'A data de inscrição deve ser uma data válida.',
        ];
    }
}
