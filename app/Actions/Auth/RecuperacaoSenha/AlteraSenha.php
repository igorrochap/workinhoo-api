<?php

namespace App\Actions\Auth\RecuperacaoSenha;

use App\Models\Usuario\Usuario;
use Illuminate\Support\Facades\Hash;

class AlteraSenha
{
    public function alterarSenha(Usuario $usuario, string $novaSenha): ?Usuario
    {
        if (Hash::check($novaSenha, $usuario->password)) {
            return null;
        }

        $usuario->update([
            'password' => $novaSenha,
        ]);

        return $usuario;
    }
}
