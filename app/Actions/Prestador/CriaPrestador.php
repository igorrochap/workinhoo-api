<?php

namespace App\Actions\Prestador;

use App\DTO\Prestador\NovoPrestadorDTO;
use App\Models\Prestador\Prestador;

final readonly class CriaPrestador
{
    public function executa(NovoPrestadorDTO $dto, int $usuarioID): Prestador
    {
        $prestador = Prestador::query()->create([...$dto->toArray(), 'usuario_id' => $usuarioID]);
        $this->adicionaEspecialidades($prestador, $dto->especialidades);
        $this->adicionaBairros($prestador, $dto->bairros);

        return $prestador;
    }

    private function adicionaEspecialidades(Prestador $prestador, array $especialidades): void
    {
        $pivot = array_map(function ($subcategorias) {
            return [
                'subcategorias' => empty($subcategorias) ? null : json_encode(array_values($subcategorias)),
            ];
        }, $especialidades);
        $prestador->especialidades()->attach($pivot);
    }

    private function adicionaBairros(Prestador $prestador, array $bairros): void
    {
        if (! $prestador->atende_cidade_toda) {
            $prestador->bairros()->attach($bairros);
        }
    }
}
