<?php

namespace App\Http\Controllers\Localizacao;

use App\Http\Resources\Localizacao\ListaBairroResource;
use App\Models\Localizacao\Bairro;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BairroController
{
    public function index(Request $request): JsonResource
    {
        $bairros = Bairro::porCidade($request->integer('cidade'), ['id', 'nome']);

        return ListaBairroResource::collection($bairros);
    }
}
