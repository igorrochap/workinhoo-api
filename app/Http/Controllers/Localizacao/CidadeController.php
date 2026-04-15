<?php

namespace App\Http\Controllers\Localizacao;

use App\Http\Resources\Localizacao\ListaCidadeResource;
use App\Models\Localizacao\Cidade;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CidadeController
{
    public function index(Request $request): JsonResource
    {
        $uf = strtoupper($request->string('uf'));
        $cidades = Cidade::porUF($uf);

        return ListaCidadeResource::collection($cidades);
    }
}
