<?php

use App\Models\Usuario\EmailVerificationToken;
use App\Models\Usuario\Usuario;
use App\Services\Auth\VerificarEmail\VerificacaoEmailTokenService;
use Illuminate\Validation\ValidationException;

test('gera token de verificacao para usuario nao verificado', function () {
    $usuario = Usuario::factory()->create([
        'email' => 'usuario@example.com',
        'email_verified_at' => null,
    ]);

    $response = app(VerificacaoEmailTokenService::class)->enviaCodigo($usuario->email);

    expect($response)
        ->email->toBe($usuario->email)
        ->nome->toBe($usuario->nome)
        ->codigo->toBeString();

    $this->assertDatabaseHas('email_verification_tokens', ['email' => $usuario->email]);
});

test('nao gera token para usuario ja verificado', function () {
    $usuario = Usuario::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = app(VerificacaoEmailTokenService::class)->enviaCodigo($usuario->email);

    expect($response)->toBeNull();
    $this->assertDatabaseMissing('email_verification_tokens', ['email' => $usuario->email]);
});

test('valida token e marca email como verificado', function () {
    $usuario = Usuario::factory()->create([
        'email' => 'usuario@example.com',
        'email_verified_at' => null,
    ]);

    EmailVerificationToken::create([
        'email' => $usuario->email,
        'token' => 'token-valido',
        'created_at' => now(),
    ]);

    $usuarioVerificado = app(VerificacaoEmailTokenService::class)->validaTokens('token-valido');

    expect($usuarioVerificado->fresh()->email_verified_at)->not->toBeNull();
    $this->assertDatabaseMissing('email_verification_tokens', ['token' => 'token-valido']);
});

test('rejeita token de verificacao inexistente', function () {
    expect(fn () => app(VerificacaoEmailTokenService::class)->validaTokens('token-inexistente'))
        ->toThrow(ValidationException::class);
});

test('rejeita token de verificacao expirado', function () {
    $usuario = Usuario::factory()->create([
        'email' => 'usuario@example.com',
        'email_verified_at' => null,
    ]);

    EmailVerificationToken::create([
        'email' => $usuario->email,
        'token' => 'token-expirado',
        'created_at' => now()->subMinutes(config('auth.email_verification.expire') + 1),
    ]);

    expect(fn () => app(VerificacaoEmailTokenService::class)->validaTokens('token-expirado'))
        ->toThrow(ValidationException::class);

    expect($usuario->fresh()->email_verified_at)->toBeNull();
});
