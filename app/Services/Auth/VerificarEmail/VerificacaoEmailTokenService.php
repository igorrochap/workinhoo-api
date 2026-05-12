<?php

namespace App\Services\Auth\VerificarEmail;

use App\Actions\Auth\VerificacaoEmail\SalvaToken;
use App\Actions\Auth\VerificacaoEmail\ValidaToken;
use App\Exceptions\TokenVerificacaoEmailInvalidoException;
use App\Models\Usuario\Usuario;

class VerificacaoEmailTokenService
{
    public function __construct(
        private readonly SalvaToken $salvaToken,
        private readonly ValidaToken $validaToken,
    ) {}

    public function enviaCodigo(string $email): ?array
    {
        $usuario = Usuario::porEmail($email);

        if (! $usuario || $usuario->email_verified_at !== null) {
            return null;
        }

        $codigo = $this->gerarCodigoConfirmacao();
        $this->salvaToken->salvar($usuario->email, $codigo);

        return ['email' => $usuario->email, 'nome' => $usuario->nome, 'codigo' => $codigo];
    }

    public function validaTokens(string $token): Usuario
    {
        $tokenVerificacao = $this->validaToken->porToken($token);

        if (! $tokenVerificacao) {
            throw TokenVerificacaoEmailInvalidoException::exception();
        }

        $usuario = Usuario::porEmail($tokenVerificacao->email);

        if (! $usuario) {
            throw TokenVerificacaoEmailInvalidoException::exception();
        }

        if ($usuario->email_verified_at === null) {
            $usuario->forceFill(['email_verified_at' => now()])->save();
        }

        $tokenVerificacao->delete();

        return $usuario;
    }

    public function validaToken(string $token): Usuario
    {
        return $this->validaTokens($token);
    }

    private function gerarCodigoConfirmacao(): string
    {
        return bin2hex(random_bytes(16));
    }
}
