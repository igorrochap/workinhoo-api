<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...$this->regrasUsuario(),
        ];
    }

    public function messages(): array
    {
        return [
            ...$this->mensagensUsuario(),
        ];
    }

    private function regrasUsuario(): array
    {
        return [
            'usuario.nome' => 'required',
            'usuario.email' => 'required|email',
            'usuario.senha' => 'required',
            'usuario.contato' => 'required',
            'usuario.foto' => 'required|image|mimes:jpeg,png,jpg',
            'usuario.is_prestador' => 'required|boolean',
        ];
    }

    private function mensagensUsuario(): array
    {
        return [
            'usuario.nome.required' => 'O nome é obrigatório',
            'usuario.email.required' => 'O email é obrigatório',
            'usuario.email.email' => 'O email deve ser um email valido',
            'usuario.senha.required' => 'A senha é obrigatória',
            'usuario.contato.required' => 'O contato é obrigatório',
            'usuario.foto.required' => 'A foto é obrigatória',
            'usuario.foto.image' => 'O foto deve ser uma imagem',
            'usuario.foto.mimes' => 'O foto deve ser uma imagem',
            'usuario.is_prestador.required' => 'A sinalização se o usuário é prestador deve ser feita',
        ];
    }
}
