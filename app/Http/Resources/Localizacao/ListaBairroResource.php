<?php

namespace App\Http\Resources\Localizacao;

use App\Models\Localizacao\Bairro;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Bairro */
class ListaBairroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
        ];
    }
}
