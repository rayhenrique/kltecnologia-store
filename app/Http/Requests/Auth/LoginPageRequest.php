<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['redirect' => ['nullable', 'string', 'regex:/^\/produtos\/[a-z0-9-]+$/']];
    }
}
