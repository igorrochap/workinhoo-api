<?php

namespace App\DTO\Localizacao\Cidade;

final readonly class NovaCidadeDTO
{
    public function __construct(
        public string $nome,
        public string $slug,
        public int $codigoIbge,
        public int $estadoID
    ) {}

    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
            'slug' => $this->slug,
            'codigo_ibge' => $this->codigoIbge,
            'estado_id' => $this->estadoID,
        ];
    }
}
