<?php

use App\Models\Usuario\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withHeaders(['Accept' => 'application/json']);
});

test('me retorna dados do usuário autenticado', function () {
    $usuario = Usuario::factory()->create();

    $response = $this->actingAs($usuario)->get('/api/auth/me');

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('uuid', $usuario->uuid)
            ->where('nome', $usuario->nome)
            ->where('email', $usuario->email)
            ->where('is_prestador', false)
        );
});

test('me retorna 401 quando não autenticado', function () {
    $response = $this->get('/api/auth/me');

    $response->assertUnauthorized();
});
