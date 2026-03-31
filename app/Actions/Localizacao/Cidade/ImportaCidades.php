<?php

namespace App\Actions\Localizacao\Cidade;

use App\DTO\Localizacao\Cidade\NovaCidadeDTO;
use App\Models\Localizacao\Cidade;
use App\Models\Localizacao\Estado;
use App\Services\Ibge\IbgeClient;
use App\Support\GeraSlug;
use Illuminate\Support\Facades\DB;

final readonly class ImportaCidades
{
    public function __construct(
        private IbgeClient $ibgeClient,
        private GeraSlug $geraSlug,
    ) {}

    public function executa(string $uf): void
    {
        $estado = Estado::query()->where('uf', strtoupper($uf))->firstOrFail();
        if ($estado->carregado) {
            throw new \DomainException('As cidades do estado informado já foram carregadas à aplicação');
        }
        $cidades = $this->ibgeClient->cidadesPorUF($estado->uf);
        DB::transaction(function () use ($estado, $cidades) {
            $this->criaCidades($cidades, $estado);
            $estado->carregado = true;
            $estado->save();
        });
    }

    private function criaCidades(array $cidades, Estado $estado): void
    {
        $dtos = [];
        foreach ($cidades as $cidade) {
            $dtos[] = new NovaCidadeDTO(
                $cidade['nome'],
                $this->geraSlug->executa($cidade['nome']),
                $cidade['id'],
                $estado->id
            );
        }
        Cidade::query()->insert(array_map(fn ($dto) => $dto->toArray(), $dtos));
    }
}
