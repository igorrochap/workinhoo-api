<?php

namespace App\Services\Ibge;

use Illuminate\Support\Facades\Http;

final readonly class IbgeClient
{
    public function cidadesPorUF(string $uf): array
    {
        $endpoint = str_replace('{UF}', $uf, config('ibge.cidades_por_uf'));

        return Http::get($endpoint)->json();
    }
}
