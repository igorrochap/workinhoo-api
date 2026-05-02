<?php

use App\Actions\Prestador\Portfolio\EditaFotoPortfolio;
use App\Models\Prestador\Portfolio;
use App\Models\Prestador\Prestador;
use App\Support\Storage\Arquivo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('portfolios');

    $this->arquivo = new Arquivo;
    $this->action = new EditaFotoPortfolio($this->arquivo);

    $path = UploadedFile::fake()->image('antiga.jpg')
        ->storeAs('', 'antiga.webp', ['disk' => 'portfolios']);

    $this->portfolio = Portfolio::factory()->create(['midia_path' => $path]);
});

test('substitui foto', function () {
    $portfolio = $this->action->executa(
        $this->portfolio,
        UploadedFile::fake()->image('nova.jpg')
    );

    expect($portfolio)->toBeInstanceOf(Portfolio::class);
});

test('persiste novo arquivo no disco', function () {
    $portfolio = $this->action->executa(
        $this->portfolio,
        UploadedFile::fake()->image('nova.jpg')
    );

    Storage::disk('portfolios')->assertExists($portfolio->midia_path);
});

test('remove arquivo antigo do disco', function () {
    $pathAntigo = $this->portfolio->midia_path;

    $this->action->executa(
        $this->portfolio,
        UploadedFile::fake()->image('nova.jpg')
    );

    Storage::disk('portfolios')->assertMissing($pathAntigo);
});

test('atualiza midia_path no banco', function () {
    $pathAntigo = $this->portfolio->midia_path;

    $portfolio = $this->action->executa(
        $this->portfolio,
        UploadedFile::fake()->image('nova.jpg')
    );

    expect($portfolio->midia_path)->not->toBe($pathAntigo);
});
