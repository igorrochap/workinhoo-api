<?php

use App\Models\Usuario\EmailVerificationToken;
use App\Models\Usuario\Usuario;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    $this->withHeaders(['Accept' => 'application/json']);
});

test('confirma email com token valido', function () {
    $usuario = Usuario::factory()->create([
        'email' => 'usuario@example.com',
        'email_verified_at' => null,
    ]);

    EmailVerificationToken::create([
        'email' => $usuario->email,
        'token' => 'token-valido',
        'created_at' => now(),
    ]);

    $response = $this->post('/api/auth/email/verificar/token-valido');

    $response->assertOk()
        ->assertJson(['message' => 'E-mail verificado com sucesso']);

    expect($usuario->fresh()->email_verified_at)->not->toBeNull();
});

test('retorna 422 para token invalido', function () {
    $response = $this->post('/api/auth/email/verificar/token-invalido');

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['token']);
});

test('reenvio retorna resposta generica para email inexistente', function () {
    $response = $this->post('/api/auth/email/verificacao/inexistente@example.com');

    $response->assertOk()
        ->assertJson(['message' => 'Caso o cadastro exista e esteja pendente, você receberá um email de confirmação']);

    Mail::assertNothingSent();
});
