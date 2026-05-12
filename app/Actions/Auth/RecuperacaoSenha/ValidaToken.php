<?php

namespace App\Actions\Auth\RecuperacaoSenha;

use App\Models\Usuario\PasswordResetTokens;
use Carbon\Carbon;

class ValidaToken
{
    public function __construct() {}

    public function porToken(string $token): bool
    {
        $tokenRecuperacao = PasswordResetTokens::porToken($token);

        if (! $tokenRecuperacao) {
            return false;
        }

        $expiracaoEmMinutos = config('auth.passwords.users.expire');

        return Carbon::parse($tokenRecuperacao->created_at)
            ->addMinutes($expiracaoEmMinutos)
            ->isFuture();
    }
}
