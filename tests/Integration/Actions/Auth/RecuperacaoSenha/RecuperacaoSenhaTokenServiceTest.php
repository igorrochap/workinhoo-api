<?php

use App\Models\Usuario\PasswordResetTokens;
use App\Services\Auth\RecuperarSenha\RecuperacaoSenhaTokenService;
use Illuminate\Validation\ValidationException;

test('valida token de recuperacao existente e nao expirado', function () {
    PasswordResetTokens::create([
        'email' => 'usuario@example.com',
        'token' => 'token-valido',
        'created_at' => now(),
    ]);

    $service = app(RecuperacaoSenhaTokenService::class);

    expect($service->validaTokens('token-valido'))->toBeTrue();
});

test('rejeita token de recuperacao inexistente', function () {
    $service = app(RecuperacaoSenhaTokenService::class);

    expect(fn () => $service->validaTokens('token-inexistente'))
        ->toThrow(ValidationException::class);
});

test('rejeita token de recuperacao expirado', function () {
    PasswordResetTokens::create([
        'email' => 'usuario@example.com',
        'token' => 'token-expirado',
        'created_at' => now()->subMinutes(config('auth.passwords.users.expire') + 1),
    ]);

    $service = app(RecuperacaoSenhaTokenService::class);

    expect(fn () => $service->validaTokens('token-expirado'))
        ->toThrow(ValidationException::class);
});
