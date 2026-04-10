<?php

namespace App\DTO;

use App\DTO\Usuario\NovoUsuarioDTO;
use App\Http\Requests\SignupRequest;

class SignupDTO
{
    public function __construct(
        public NovoUsuarioDTO $usuario
    ) {
    }

    public static function porRequest(SignupRequest $request): SignupDTO
    {
        $usuario = $request->input("usuario");
        return new self(
            new NovoUsuarioDTO($usuario['nome'], $usuario['email'], $usuario['senha'], $usuario['contato'], $request->file('usuario.foto'), $usuario['is_prestador'])
        );
    }
}
