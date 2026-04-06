<?php

namespace App\Actions\Localizacao\Bairro;

use App\DTO\Localizacao\Bairro\NovoBairroDTO;
use App\Models\Localizacao\Bairro;
use App\Models\Localizacao\Cidade;
use App\Models\Localizacao\Estado;
use App\Support\GeraSlug;
use Illuminate\Support\Facades\DB;

final readonly class ImportaBairros
{
    public function __construct(
        private GeraSlug $geraSlug,
    ) {}

    public function executa(string $uf): void
    {
        $estado = Estado::query()->where('uf', strtoupper($uf))->firstOrFail();

        if (Bairro::query()->whereHas('cidade', fn ($q) => $q->where('estado_id', $estado->id))->exists()) {
            throw new \DomainException('Os bairros do estado informado já foram carregados à aplicação');
        }

        $cidades = Cidade::query()
            ->where('estado_id', $estado->id)
            ->pluck('id', 'codigo_ibge');

        $path = database_path("data/bairros/{$estado->uf}.csv");
        if (! file_exists($path)) {
            throw new \DomainException("Arquivo CSV não encontrado: {$path}");
        }

        $dtos = $this->parseCsv($path, $cidades);

        DB::transaction(function () use ($dtos) {
            foreach (array_chunk($dtos, 500) as $chunk) {
                Bairro::query()->insert(array_map(fn ($dto) => $dto->toArray(), $chunk));
            }
        });
    }

    private function parseCsv(string $path, \Illuminate\Support\Collection $cidades): array
    {
        $handle = fopen($path, 'r');
        fgetcsv($handle); // skip header row

        $dtos = [];
        while (($row = fgetcsv($handle)) !== false) {
            [$cdMun, , $nmBairro, $lat, $long] = $row;

            $codigoIbge = (int) $cdMun;
            if (! $cidades->has($codigoIbge)) {
                continue;
            }

            $dtos[] = new NovoBairroDTO(
                nome: $nmBairro,
                slug: $this->geraSlug->executa($nmBairro),
                cidadeID: $cidades[$codigoIbge],
                latitude: $lat !== '' ? (float) $lat : null,
                longitude: $long !== '' ? (float) $long : null,
            );
        }
        fclose($handle);

        return $dtos;
    }
}
