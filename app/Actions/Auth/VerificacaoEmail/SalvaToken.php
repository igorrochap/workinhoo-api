<?php

namespace App\Actions\Auth\VerificacaoEmail;

use App\Models\Usuario\EmailVerificationToken;

class SalvaToken
{
    public function salvar(string $email, string $token): void
    {
        EmailVerificationToken::updateOrCreate(
            ['email' => $email],
            [
                'token' => $token,
                'created_at' => now(),
            ],
        );
    }
}
