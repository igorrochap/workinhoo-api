<?php

namespace App\Http\Controllers\Prestador;

use App\Http\Resources\Prestador\ListaEspecialidadeResource;
use App\Models\Prestador\Especialidade;
use Illuminate\Http\Resources\Json\JsonResource;

class EspecialidadeController
{
    public function index(): JsonResource
    {
        return ListaEspecialidadeResource::collection(Especialidade::query()->orderBy('nome')->get());
    }
}
