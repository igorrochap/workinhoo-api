<?php

use App\Support\GeraSlug;

beforeEach(function () {
    $this->geraSlug = new GeraSlug;
});

test('gera slug correta', function (string $nome, string $slugEsperada) {
    $slug = $this->geraSlug->executa($nome);
    expect($slug)->toBe($slugEsperada);
})->with([
    ['Água Branca', 'agua-branca'],
    ['Mogi das Cruzes', 'mogi-das-cruzes'],
    ['Espirito Santo', 'espirito-santo'],
    ["Olho D'Água das Flores", 'olho-dagua-das-flores'],
    ['BRASÍLIA', 'brasilia'],
    ['Viçosa', 'vicosa'],
    ['Grão-Pará', 'grao-para'],
]);
