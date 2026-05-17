<?php

use App\Models\Usuario\EmailVerificationToken;
use App\Models\Usuario\Usuario;
use App\Services\Auth\TokenService;
use Illuminate\Validation\ValidationException;

test('gera token de verificacao para usuario existente', function () {
    $usuario = Usuario::factory()->create([
        'email' => 'usuario@example.com',
        'email_verified_at' => null,
    ]);

    $response = app(TokenService::class)->salvaToken(new EmailVerificationToken, $usuario->email);

    expect($response)
        ->email->toBe($usuario->email)
        ->nome->toBe($usuario->nome)
        ->codigo->toBeString();

    $this->assertDatabaseHas('email_verification_tokens', ['email' => $usuario->email]);
});

test('nao gera token de verificacao para usuario inexistente', function () {
    $response = app(TokenService::class)->salvaToken(new EmailVerificationToken, 'inexistente@example.com');

    expect($response)->toBeNull();
    $this->assertDatabaseMissing('email_verification_tokens', ['email' => 'inexistente@example.com']);
});

test('rejeita token de verificacao inexistente', function () {
    expect(fn () => app(TokenService::class)->validaTokens(new EmailVerificationToken, 'token-inexistente'))
        ->toThrow(ValidationException::class);
});
