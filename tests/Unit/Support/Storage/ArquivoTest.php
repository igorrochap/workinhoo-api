<?php

use App\Support\Storage\Arquivo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->arquivo = new Arquivo;
    Storage::fake('local');
});

test('persiste arquivo no disco', function () {
    $file = UploadedFile::fake()->image('foto.jpg');

    $this->arquivo->persiste($file, 'avatars', 'foto.jpg');

    Storage::assertExists('avatars/foto.jpg');
});

test('persiste retorna caminho relativo ao disco contendo o nome do arquivo', function () {
    $file = UploadedFile::fake()->image('foto.jpg');

    $resultado = $this->arquivo->persiste($file, 'avatars', 'foto.jpg');

    expect($resultado)->toContain('foto.jpg');
});

test('persiste arquivo em subdiretorio', function () {
    $file = UploadedFile::fake()->create('documento.pdf', 100);

    $this->arquivo->persiste($file, 'documentos/contratos', 'contrato.pdf');

    Storage::assertExists('documentos/contratos/contrato.pdf');
});

test('persiste arquivo com opcoes de disco alternativo', function () {
    Storage::fake('s3');
    $file = UploadedFile::fake()->image('avatar.png');

    $this->arquivo->persiste($file, 'fotos', 'avatar.png', ['disk' => 's3']);

    Storage::disk('s3')->assertExists('fotos/avatar.png');
});

test('remove arquivo existente retorna verdadeiro', function () {
    Storage::put('avatars/foto.jpg', 'conteudo');

    $resultado = $this->arquivo->remove('avatars', 'foto.jpg');

    expect($resultado)->toBeTrue();
    Storage::assertMissing('avatars/foto.jpg');
});

test('remove arquivo inexistente e idempotente', function () {
    $resultado = $this->arquivo->remove('avatars', 'nao-existe.jpg');

    expect($resultado)->toBeTrue();
    Storage::assertMissing('avatars/nao-existe.jpg');
});

test('remove nao afeta outros arquivos no mesmo diretorio', function () {
    Storage::put('avatars/manter.jpg', 'conteudo');
    Storage::put('avatars/remover.jpg', 'conteudo');

    $this->arquivo->remove('avatars', 'remover.jpg');

    Storage::assertExists('avatars/manter.jpg');
    Storage::assertMissing('avatars/remover.jpg');
});
