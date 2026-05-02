<?php

use App\Actions\Prestador\Portfolio\ExcluiFotoPortfolio;
use App\Models\Prestador\Portfolio;
use App\Support\Storage\Arquivo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('portfolios');

    $this->arquivo = new Arquivo;
    $this->action = new ExcluiFotoPortfolio($this->arquivo);

    $path = UploadedFile::fake()->image('foto.jpg')
        ->storeAs('', 'foto.webp', ['disk' => 'portfolios']);

    $this->portfolio = Portfolio::factory()->create(['midia_path' => $path]);
});

test('remove arquivo do disco', function () {
    $path = $this->portfolio->midia_path;

    $this->action->executa($this->portfolio);

    Storage::disk('portfolios')->assertMissing($path);
});

test('define midia_path como null no banco', function () {
    $portfolio = $this->action->executa($this->portfolio);

    expect($portfolio->midia_path)->toBeNull();
});

test('retorna portfolio atualizado', function () {
    $portfolio = $this->action->executa($this->portfolio);

    expect($portfolio)->toBeInstanceOf(Portfolio::class);
});
