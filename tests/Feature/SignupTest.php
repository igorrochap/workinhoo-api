<?php

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
