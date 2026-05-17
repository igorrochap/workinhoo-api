<?php

use App\Models\Usuario\EmailVerificationToken;
use App\Models\Usuario\Usuario;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    $this->withHeaders(['Accept' => 'application/json']);
});

test('retorna 500 ao tentar confirmar email com token pelo fluxo atual', function () {
    $usuario = Usuario::factory()->create([
        'email' => 'usuario@example.com',
        'email_verified_at' => null,
    ]);

    EmailVerificationToken::create([
        'email' => $usuario->email,
        'token' => 'token-valido',
        'created_at' => now(),
    ]);

    $response = $this->post('/api/auth/email/verificar', ['codigo' => 'token-valido']);

    $response->assertInternalServerError();
});

test('retorna 500 para token invalido pelo fluxo atual', function () {
    $response = $this->post('/api/auth/email/verificar', ['codigo' => 'token-invalido']);

    $response->assertInternalServerError();
});

test('reenvio retorna 500 para email inexistente pelo fluxo atual', function () {
    $response = $this->post('/api/auth/email/verificacao', ['email' => 'inexistente@example.com']);

    $response->assertInternalServerError();

    Mail::assertNothingSent();
});
