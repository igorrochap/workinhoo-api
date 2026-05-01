<?php

namespace App\DTO\Prestador;

use Illuminate\Http\UploadedFile;

final readonly class NovoPortfolioDTO
{
    public function __construct(
        public int $prestadorId,
        public string $descricao,
        public UploadedFile $midia, // midia_path será gerado pelo action
    ) {}

    public function toArray(string $midiaPath): array
    {
        return [
            'prestador_id' => $this->prestadorId,
            'descricao' => $this->descricao,
            'midia_path' => $midiaPath, // action passa a midia_path para encontrar o arquivo
        ];
    }
}
