<?php

namespace App\DTO\Prestador;

use Illuminate\Http\UploadedFile;

final readonly class NovoPortfolioDTO
{
    public function __construct(
        public int $prestadorID,
        public string $descricao,
        public ?UploadedFile $midia = null, // midia_path será gerado pelo action
    ) {}

    public function toArray(): array
    {
        return [
            'prestador_id' => $this->prestadorID,
            'descricao' => $this->descricao,
        ];
    }
}
