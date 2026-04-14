<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            ...$this->regrasPrestador(),
        ];
    }

    public function messages(): array
    {
        return [
            ...$this->mensagensUsuario(),
            ...$this->mensagensPrestador(),
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

    private function regrasPrestador(): array
    {
        return [
            'prestador.descricao' => 'exclude_unless:usuario.is_prestador,true|required|string',
            'prestador.instagram' => 'exclude_unless:usuario.is_prestador,true|nullable|string|max:100',
            'prestador.cidade_id' => 'exclude_unless:usuario.is_prestador,true|required|integer|exists:cidades,id',
            'prestador.atende_cidade_toda' => 'exclude_unless:usuario.is_prestador,true|required|boolean',
            'prestador.latitude' => 'exclude_unless:usuario.is_prestador,true|nullable|numeric',
            'prestador.longitude' => 'exclude_unless:usuario.is_prestador,true|nullable|numeric',
            'prestador.especialidades' => 'exclude_unless:usuario.is_prestador,true|required|array|min:1',
            'prestador.especialidades.*' => 'array',
            'prestador.especialidades.*.*' => 'string',
            'prestador.bairros' => [
                'exclude_unless:usuario.is_prestador,true',
                Rule::requiredIf(fn () => ! $this->boolean('prestador.atende_cidade_toda')),
                'array',
                'min:1',
            ],
            'prestador.bairros.*' => 'integer|exists:bairros,id',
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

    private function mensagensPrestador(): array
    {
        return [
            'prestador.descricao.required' => 'A descrição do prestador é obrigatória',
            'prestador.instagram.max' => 'O Instagram deve ter no máximo 100 caracteres',
            'prestador.cidade_id.required' => 'A cidade do prestador é obrigatória',
            'prestador.cidade_id.integer' => 'A cidade informada é inválida',
            'prestador.cidade_id.exists' => 'A cidade informada não foi encontrada',
            'prestador.atende_cidade_toda.required' => 'Informe se o prestador atende a cidade toda',
            'prestador.atende_cidade_toda.boolean' => 'O campo atende cidade toda deve ser verdadeiro ou falso',
            'prestador.latitude.numeric' => 'A latitude deve ser um número',
            'prestador.longitude.numeric' => 'A longitude deve ser um número',
            'prestador.especialidades.required' => 'Informe ao menos uma especialidade',
            'prestador.especialidades.array' => 'As especialidades devem ser uma lista',
            'prestador.especialidades.min' => 'Informe ao menos uma especialidade',
            'prestador.especialidades.*.array' => 'Especialidade inválida',
            'prestador.especialidades.*.*.string' => 'Subcategoria inválida',
            'prestador.bairros.required' => 'Informe ao menos um bairro quando não atender a cidade toda',
            'prestador.bairros.array' => 'Os bairros devem ser uma lista',
            'prestador.bairros.min' => 'Informe ao menos um bairro quando não atender a cidade toda',
            'prestador.bairros.*.integer' => 'Bairro inválido',
            'prestador.bairros.*.exists' => 'Bairro não encontrado',
        ];
    }
}
