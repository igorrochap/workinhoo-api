<?php

namespace App\Http\Resources\Auth;

use App\Models\Usuario\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Usuario */
class UsuarioAutenticadoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'nome' => $this->nome,
            'email' => $this->email,
            'is_prestador' => $this->is_prestador,
        ];
    }
}
