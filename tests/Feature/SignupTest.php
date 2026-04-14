<?php

use App\Models\Localizacao\Bairro;
use App\Models\Localizacao\Cidade;
use App\Models\Prestador\Especialidade;
use App\Models\Usuario\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('avatars');
    $this->withHeaders(['Accept' => 'application/json']);
});

// ---------------------------------------------------------------------------
// Happy paths
// ---------------------------------------------------------------------------

test('cadastro válido cria usuário e retorna 201 com mensagem', function () {
    $response = $this->post('/api/signup', [
        'usuario' => [
            'nome' => 'Igor Rocha',
            'email' => 'igor@example.com',
            'senha' => 'senha123',
            'contato' => '82999999999',
            'foto' => UploadedFile::fake()->image('foto.jpg'),
            'is_prestador' => false,
        ],
    ]);

    $response->assertCreated()
        ->assertJson(fn (AssertableJson $json) => $json->where('message', 'Caso seu cadastro seja válido, você receberá um email de confirmação')
        );

    $this->assertDatabaseHas('usuarios', ['email' => 'igor@example.com']);
});

test('cadastro com email já existente retorna 201 sem criar duplicata', function () {
    Usuario::factory()->create(['email' => 'igor@example.com']);

    $response = $this->post('/api/signup', [
        'usuario' => [
            'nome' => 'Outro Nome',
            'email' => 'igor@example.com',
            'senha' => 'senha123',
            'contato' => '82999999999',
            'foto' => UploadedFile::fake()->image('foto.jpg'),
            'is_prestador' => false,
        ],
    ]);

    $response->assertCreated();
    $this->assertDatabaseCount('usuarios', 1);
});

// ---------------------------------------------------------------------------
// Validações de campos obrigatórios
// ---------------------------------------------------------------------------

dataset('campos obrigatórios', [
    'nome ausente' => [['email' => 'x@x.com', 'senha' => 'abc', 'contato' => '123'], 'usuario.nome'],
    'email ausente' => [['nome' => 'João',     'senha' => 'abc', 'contato' => '123'], 'usuario.email'],
    'senha ausente' => [['nome' => 'João', 'email' => 'x@x.com', 'contato' => '123'], 'usuario.senha'],
    'contato ausente' => [['nome' => 'João', 'email' => 'x@x.com', 'senha' => 'abc'],   'usuario.contato'],
    'is_prestador ausente' => [['nome' => 'João', 'email' => 'x@x.com', 'senha' => 'abc', 'contato' => '123'], 'usuario.is_prestador'],
]);

it('rejeita cadastro quando $1 com 422', function (array $campos, string $erroEsperado) {
    $campos['foto'] = UploadedFile::fake()->image('foto.jpg');

    $response = $this->post('/api/signup', ['usuario' => $campos]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([$erroEsperado]);
})->with('campos obrigatórios');

test('rejeita cadastro com email inválido', function () {
    $response = $this->post('/api/signup', [
        'usuario' => [
            'nome' => 'João',
            'email' => 'nao-e-um-email',
            'senha' => 'senha123',
            'contato' => '82999999999',
            'foto' => UploadedFile::fake()->image('foto.jpg'),
            'is_prestador' => false,
        ],
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['usuario.email']);
});

test('rejeita cadastro sem foto', function () {
    $response = $this->post('/api/signup', [
        'usuario' => [
            'nome' => 'João',
            'email' => 'joao@example.com',
            'senha' => 'senha123',
            'contato' => '82999999999',
            'is_prestador' => false,
        ],
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['usuario.foto']);
});

test('rejeita cadastro com foto que não é imagem', function () {
    $response = $this->post('/api/signup', [
        'usuario' => [
            'nome' => 'João',
            'email' => 'joao@example.com',
            'senha' => 'senha123',
            'contato' => '82999999999',
            'foto' => UploadedFile::fake()->create('documento.pdf', 100, 'application/pdf'),
            'is_prestador' => false,
        ],
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['usuario.foto']);
});

// ---------------------------------------------------------------------------
// Prestador — happy paths
// ---------------------------------------------------------------------------

function payloadPrestador(array $overrides = []): array
{
    return array_replace_recursive([
        'usuario' => [
            'nome' => 'Maria Silva',
            'email' => 'maria@example.com',
            'senha' => 'senha123',
            'contato' => '82988888888',
            'foto' => UploadedFile::fake()->image('foto.jpg'),
            'is_prestador' => true,
        ],
        'prestador' => [
            'descricao' => 'Eletricista com 10 anos de experiência',
            'instagram' => null,
            'cidade_id' => null,
            'atende_cidade_toda' => false,
            'latitude' => null,
            'longitude' => null,
            'especialidades' => [],
        ],
    ], $overrides);
}

test('cadastro de prestador válido (atende_cidade_toda=false) cria prestador, especialidades e bairros', function () {
    $cidade = Cidade::factory()->create();
    $bairro1 = Bairro::factory()->create(['cidade_id' => $cidade->id]);
    $bairro2 = Bairro::factory()->create(['cidade_id' => $cidade->id]);
    $especialidade1 = Especialidade::factory()->create();
    $especialidade2 = Especialidade::factory()->create();

    $payload = payloadPrestador([
        'prestador' => [
            'cidade_id' => $cidade->id,
            'atende_cidade_toda' => false,
            'especialidades' => [
                $especialidade1->id => ['a', 'b'],
                $especialidade2->id => [],
            ],
            'bairros' => [$bairro1->id, $bairro2->id],
        ],
    ]);

    $response = $this->post('/api/signup', $payload);

    $response->assertCreated();
    $this->assertDatabaseHas('prestadores', ['descricao' => $payload['prestador']['descricao']]);
    $this->assertDatabaseHas('prestador_especialidades', [
        'especialidade_id' => $especialidade1->id,
        'subcategorias' => json_encode(['a', 'b']),
    ]);
    $this->assertDatabaseHas('prestador_especialidades', [
        'especialidade_id' => $especialidade2->id,
        'subcategorias' => null,
    ]);
    $this->assertDatabaseCount('prestador_bairros', 2);
});

test('cadastro de prestador válido (atende_cidade_toda=true) não cria bairros', function () {
    $cidade = Cidade::factory()->create();
    $especialidade = Especialidade::factory()->create();

    $payload = payloadPrestador([
        'prestador' => [
            'cidade_id' => $cidade->id,
            'atende_cidade_toda' => true,
            'especialidades' => [$especialidade->id => []],
        ],
    ]);

    $response = $this->post('/api/signup', $payload);

    $response->assertCreated();
    $this->assertDatabaseCount('prestador_bairros', 0);
});

test('não cria prestador quando is_prestador=false mesmo com payload de prestador presente', function () {
    $cidade = Cidade::factory()->create();

    $payload = payloadPrestador([
        'usuario' => ['is_prestador' => false],
        'prestador' => ['cidade_id' => $cidade->id],
    ]);

    $response = $this->post('/api/signup', $payload);

    $response->assertCreated();
    $this->assertDatabaseCount('prestadores', 0);
});

// ---------------------------------------------------------------------------
// Prestador — validações
// ---------------------------------------------------------------------------

dataset('campos obrigatórios do prestador', [
    'descricao ausente' => [['descricao' => null], 'prestador.descricao'],
    'cidade_id ausente' => [['cidade_id' => null], 'prestador.cidade_id'],
    'atende_cidade_toda ausente' => [['atende_cidade_toda' => null], 'prestador.atende_cidade_toda'],
    'especialidades ausentes' => [['especialidades' => null], 'prestador.especialidades'],
]);

it('rejeita cadastro de prestador quando $1 com 422', function (array $campos, string $erroEsperado) {
    $cidade = Cidade::factory()->create();

    $overrides = ['prestador' => array_merge(['cidade_id' => $cidade->id], $campos)];
    $payload = payloadPrestador($overrides);

    $response = $this->post('/api/signup', $payload);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([$erroEsperado]);
})->with('campos obrigatórios do prestador');

test('rejeita prestador sem bairros quando não atende cidade toda', function () {
    $cidade = Cidade::factory()->create();
    $especialidade = Especialidade::factory()->create();

    $payload = payloadPrestador([
        'prestador' => [
            'cidade_id' => $cidade->id,
            'atende_cidade_toda' => false,
            'especialidades' => [$especialidade->id => []],
            'bairros' => [],
        ],
    ]);
    unset($payload['prestador']['bairros']);

    $response = $this->post('/api/signup', $payload);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['prestador.bairros']);
});

test('aceita prestador sem bairros quando atende cidade toda', function () {
    $cidade = Cidade::factory()->create();
    $especialidade = Especialidade::factory()->create();

    $payload = payloadPrestador([
        'prestador' => [
            'cidade_id' => $cidade->id,
            'atende_cidade_toda' => true,
            'especialidades' => [$especialidade->id => []],
            'bairros' => [],
        ],
    ]);
    unset($payload['prestador']['bairros']);

    $response = $this->post('/api/signup', $payload);

    $response->assertCreated();
});

test('rejeita prestador com cidade inexistente', function () {
    $payload = payloadPrestador([
        'prestador' => ['cidade_id' => 99999],
    ]);

    $response = $this->post('/api/signup', $payload);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['prestador.cidade_id']);
});
