<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['status' => ['required', 'in:success,failure,pending']];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['status' => $this->route('status')]);
    }
}
