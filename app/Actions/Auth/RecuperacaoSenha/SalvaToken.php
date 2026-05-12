<?php

namespace App\Actions\Auth\RecuperacaoSenha;

use App\Models\Usuario\PasswordResetTokens;

class SalvaToken
{
    public function salvar(string $email, string $token): void
    {
        PasswordResetTokens::updateOrCreate(
            ['email' => $email],
            [
                'token' => $token,
                'created_at' => now(),
            ],
        );
    }
}
