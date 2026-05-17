<?php

namespace App\Actions\Auth;

use App\Models\Usuario\Usuario;

class ValidaEmailVerificado
{
    public function executa(string $email)
    {
        $usuario = Usuario::porEmail($email);

        if ($usuario->email_verified_at != null) {
            return true;
        }

        return false;
    }
}
