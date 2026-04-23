<?php

use App\Models\Usuario\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withHeaders([
        'Accept' => 'application/json',
        'Origin' => 'http://localhost',
    ]);
});

test('logout retorna 204', function () {
    $usuario = Usuario::factory()->create();

    $response = $this->actingAs($usuario)->post('/api/auth/logout');

    $response->assertNoContent();
});

test('logout retorna 401 quando não autenticado', function () {
    $response = $this->post('/api/auth/logout');

    $response->assertUnauthorized();
});
