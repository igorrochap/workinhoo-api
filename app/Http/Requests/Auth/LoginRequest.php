<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'credencial' => 'required|email',
            'senha' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'credencial.required' => 'A credencial é obrigatória.',
            'credencial.email' => 'A credencial deve ser um email válido.',
            'senha.required' => 'A senha é obrigatória.',
        ];
    }
}
