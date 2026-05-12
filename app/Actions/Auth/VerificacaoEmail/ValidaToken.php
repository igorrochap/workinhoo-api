<?php

namespace App\Actions\Auth\VerificacaoEmail;

use App\Models\Usuario\EmailVerificationToken;
use Carbon\Carbon;

class ValidaToken
{
    public function porToken(string $token): ?EmailVerificationToken
    {
        $tokenVerificacao = EmailVerificationToken::porToken($token);

        if (! $tokenVerificacao) {
            return null;
        }

        $expiracaoEmMinutos = config('auth.email_verification.expire');
        $tokenValido = Carbon::parse($tokenVerificacao->created_at)
            ->addMinutes($expiracaoEmMinutos)
            ->isFuture();

        return $tokenValido ? $tokenVerificacao : null;
    }
}
