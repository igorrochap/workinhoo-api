<?php

namespace App\Actions\Auth;

use App\Models\Usuario\Usuario;

class RecuperaSenha
{
    public function buscaPorEmail(string $email)
    {
        $usuario = Usuario::porEmail($email);
        if($usuario) {
            $codigo = $this->gerarCodigoConfirmacao();

        }
    }

    private function gerarCodigoConfirmacao() {

        return md5(uniqid(rand(), true));
    }
}
