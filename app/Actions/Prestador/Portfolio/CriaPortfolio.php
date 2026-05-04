<?php

namespace App\Actions\Prestador\Portfolio;

use App\DTO\Prestador\NovoPortfolioDTO;
use App\Models\Prestador\Portfolio;
use App\Support\Storage\Arquivo;
use App\Support\ValueObjects\UUID;

final readonly class CriaPortfolio
{
    public function __construct(
        private Arquivo $arquivo
    ) {}

    public function executa(NovoPortfolioDTO $dto): Portfolio
    {
        $midiaID = UUID::cria();
        $midiaPath = $this->arquivo->persiste($dto->midia, '', "{$midiaID->recupera()}.webp", ['disk' => 'portfolios']);

        return Portfolio::query()->create([...$dto->toArray(), 'midia_path' => $midiaPath]);
    }
}
