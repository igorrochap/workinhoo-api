<?php

namespace App\Support;

final readonly class GeraSlug
{
    public function executa(string $nome): string
    {
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', mb_strtolower($nome, 'UTF-8'));
        $semPontuacao = preg_replace('/[^a-z0-9\s-]/', '', $ascii);

        return preg_replace('/\s+/', '-', trim($semPontuacao));
    }
}
