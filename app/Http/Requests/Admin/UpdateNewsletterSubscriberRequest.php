<?php

namespace App\Http\Requests\Admin;

use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsletterSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        $subscriber = $this->route('subscriber');

        return $this->user()?->can('update', $subscriber) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $subscriberId = $this->route('subscriber') instanceof NewsletterSubscriber
            ? $this->route('subscriber')->id
            : $this->route('subscriber');

        return [
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('newsletter_subscribers', 'email')
                    ->ignore($subscriberId)
                    ->whereNull('deleted_at'),
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
            'email.unique' => 'Este e-mail já está sendo utilizado por outro inscrito.',
            'subscribed_at.date' => 'A data de inscrição deve ser uma data válida.',
        ];
    }
}
