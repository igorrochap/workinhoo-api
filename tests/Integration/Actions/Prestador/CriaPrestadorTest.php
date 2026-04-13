<?php

use App\Actions\Prestador\CriaPrestador;
use App\DTO\Prestador\NovoPrestadorDTO;
use App\Models\Localizacao\Bairro;
use App\Models\Localizacao\Cidade;
use App\Models\Prestador\Especialidade;
use App\Models\Prestador\Prestador;
use App\Models\Usuario\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $usuario = Usuario::factory()->create(['is_prestador' => true]);
    $cidade = Cidade::factory()->create();
    $this->usuarioID = $usuario->id;
    $this->cidadeID = $cidade->id;
    $this->action = new CriaPrestador;
});

test('cria prestador', function () {
    $dto = new NovoPrestadorDTO(
        descricao: 'Eletricista experiente',
        instagram: null,
        cidadeID: $this->cidadeID,
        atendeCidadeToda: true,
        latitude: null,
        longitude: null,
        especialidades: [],
    );

    $prestador = $this->action->executa($dto, $this->usuarioID);

    expect($prestador)->toBeInstanceOf(Prestador::class);
});

test('cria prestador com dados válidos', function () {
    $bairro = Bairro::factory()->create(['cidade_id' => $this->cidadeID]);

    $dto = new NovoPrestadorDTO(
        descricao: 'Eletricista experiente',
        instagram: '@eletricista',
        cidadeID: $this->cidadeID,
        atendeCidadeToda: false,
        latitude: -9.6658,
        longitude: -35.7350,
        especialidades: [],
        bairros: [$bairro->id],
    );

    $prestador = $this->action->executa($dto, $this->usuarioID);

    expect($prestador->descricao)->toBe($dto->descricao)
        ->and($prestador->instagram)->toBe($dto->instagram)
        ->and($prestador->cidade_id)->toBe($dto->cidadeID)
        ->and($prestador->atende_cidade_toda)->toBeFalse()
        ->and($prestador->usuario_id)->toBe($this->usuarioID);
});

test('gera uuid automaticamente', function () {
    $dto = new NovoPrestadorDTO(
        descricao: 'Descrição',
        instagram: null,
        cidadeID: $this->cidadeID,
        atendeCidadeToda: true,
        latitude: null,
        longitude: null,
        especialidades: [],
    );

    $prestador = $this->action->executa($dto, $this->usuarioID);

    expect($prestador->uuid)->not->toBeNull()->toBeString();
});

test('attacha especialidades com subcategorias ao prestador', function () {
    $especialidade1 = Especialidade::factory()->create();
    $especialidade2 = Especialidade::factory()->create();

    $dto = new NovoPrestadorDTO(
        descricao: 'Descrição',
        instagram: null,
        cidadeID: $this->cidadeID,
        atendeCidadeToda: true,
        latitude: null,
        longitude: null,
        especialidades: [
            $especialidade1->id => ['a', 'b'],
            $especialidade2->id => [],
        ],
    );

    $prestador = $this->action->executa($dto, $this->usuarioID);

    $this->assertDatabaseHas('prestador_especialidades', [
        'prestador_id' => $prestador->id,
        'especialidade_id' => $especialidade1->id,
        'subcategorias' => json_encode(['a', 'b']),
    ]);
    $this->assertDatabaseHas('prestador_especialidades', [
        'prestador_id' => $prestador->id,
        'especialidade_id' => $especialidade2->id,
        'subcategorias' => null,
    ]);
});

test('attacha bairros quando atende_cidade_toda é false', function () {
    $bairro1 = Bairro::factory()->create(['cidade_id' => $this->cidadeID]);
    $bairro2 = Bairro::factory()->create(['cidade_id' => $this->cidadeID]);

    $dto = new NovoPrestadorDTO(
        descricao: 'Descrição',
        instagram: null,
        cidadeID: $this->cidadeID,
        atendeCidadeToda: false,
        latitude: null,
        longitude: null,
        especialidades: [],
        bairros: [$bairro1->id, $bairro2->id],
    );

    $prestador = $this->action->executa($dto, $this->usuarioID);

    $this->assertDatabaseCount('prestador_bairros', 2);
    $this->assertDatabaseHas('prestador_bairros', ['prestador_id' => $prestador->id, 'bairro_id' => $bairro1->id]);
    $this->assertDatabaseHas('prestador_bairros', ['prestador_id' => $prestador->id, 'bairro_id' => $bairro2->id]);
});

test('não attacha bairros quando atende_cidade_toda é true', function () {
    $bairro = Bairro::factory()->create(['cidade_id' => $this->cidadeID]);

    $dto = new NovoPrestadorDTO(
        descricao: 'Descrição',
        instagram: null,
        cidadeID: $this->cidadeID,
        atendeCidadeToda: true,
        latitude: null,
        longitude: null,
        especialidades: [],
        bairros: [$bairro->id],
    );

    $this->action->executa($dto, $this->usuarioID);

    $this->assertDatabaseCount('prestador_bairros', 0);
});
