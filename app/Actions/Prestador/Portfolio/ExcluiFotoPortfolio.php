<?php

namespace App\Actions\Prestador\Portfolio;

use App\Models\Prestador\Portfolio;
use App\Support\Storage\Arquivo;
use App\Support\ValueObjects\UUID;
use Illuminate\Http\UploadedFile;

final readonly class ExcluiFotoPortfolio
{
    public function __construct(
        private Arquivo $arquivo
    ) {}

    public function executa(Portfolio $portfolio, UploadedFile $midia): Portfolio
    {
        // Remove o arquivo antigo
        $this->arquivo->remove('', $portfolio->midia_path);

        $portfolio->update(['midia_path' => null]);

        return $portfolio->refresh();
    }
}
