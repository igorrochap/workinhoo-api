<?php

use App\Models\Usuario\PasswordResetTokens;
use App\Models\Usuario\Usuario;
use App\Services\Auth\TokenService;
use Illuminate\Validation\ValidationException;

test('gera token de recuperacao para usuario existente', function () {
    $usuario = Usuario::factory()->create([
        'email' => 'usuario@example.com',
    ]);

    $response = app(TokenService::class)->salvaToken(new PasswordResetTokens, $usuario->email);

    expect($response)
        ->email->toBe($usuario->email)
        ->nome->toBe($usuario->nome)
        ->codigo->toBeString();

    $this->assertDatabaseHas('password_reset_tokens', ['email' => $usuario->email]);
});

test('nao gera token de recuperacao para usuario inexistente', function () {
    $response = app(TokenService::class)->salvaToken(new PasswordResetTokens, 'inexistente@example.com');

    expect($response)->toBeNull();
    $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'inexistente@example.com']);
});

test('rejeita token de recuperacao inexistente', function () {
    expect(fn () => app(TokenService::class)->validaTokens(new PasswordResetTokens, 'token-inexistente'))
        ->toThrow(ValidationException::class);
});
