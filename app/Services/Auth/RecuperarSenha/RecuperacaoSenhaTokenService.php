<?php

namespace App\Services\Auth\RecuperarSenha;

use App\Actions\Auth\RecuperacaoSenha\SalvaToken;
use App\Actions\Auth\RecuperacaoSenha\ValidaToken;
use App\Exceptions\TokenResetSenhaInvalidoException;
use App\Models\Usuario\Usuario;

class RecuperacaoSenhaTokenService
{
    public function __construct(
        private readonly SalvaToken $tokenRecuperacaoSenha,
        private readonly ValidaToken $validaToken) {}

    public function enviaCodigo(string $email): ?array
    {
        $usuario = Usuario::porEmail($email);
        if ($usuario) {
            $codigo = $this->gerarCodigoConfirmacao();
            $this->tokenRecuperacaoSenha->salvar($usuario->email, $codigo);

            return ['email' => $usuario->email, 'nome' => $usuario->nome, 'codigo' => $codigo];
        }

        return null;
    }

    public function validaTokens(string $token): bool
    {
        if (! $this->validaToken->porToken($token)) {
            throw TokenResetSenhaInvalidoException::exception();
        }

        return true;
    }

    public function validaToken(string $token): bool
    {
        return $this->validaTokens($token);
    }

    private function gerarCodigoConfirmacao(): string
    {
        return bin2hex(random_bytes(4));
    }
}
