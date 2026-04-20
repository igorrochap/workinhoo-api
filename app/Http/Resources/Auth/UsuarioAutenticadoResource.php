<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
