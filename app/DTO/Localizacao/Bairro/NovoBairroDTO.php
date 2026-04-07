<?php

namespace App\DTO\Localizacao\Bairro;

final readonly class NovoBairroDTO
{
    public function __construct(
        public string $nome,
        public string $slug,
        public int $cidadeID,
        public ?float $latitude,
        public ?float $longitude,
    ) {}

    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
            'slug' => $this->slug,
            'cidade_id' => $this->cidadeID,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
