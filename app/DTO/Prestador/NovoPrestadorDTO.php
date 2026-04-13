<?php

namespace App\DTO\Prestador;

final readonly class NovoPrestadorDTO
{
    public function __construct(
        public string $descricao,
        public ?string $instagram,
        public int $cidadeID,
        public bool $atendeCidadeToda,
        public ?float $latitude,
        public ?float $longitude,
        /** @var array<int, array<string>> */
        public array $especialidades,
        /** @var array<int> */
        public array $bairros = []
    ) {}

    public function toArray(): array
    {
        return [
            'descricao' => $this->descricao,
            'instagram' => $this->instagram,
            'cidade_id' => $this->cidadeID,
            'atende_cidade_toda' => $this->atendeCidadeToda,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
