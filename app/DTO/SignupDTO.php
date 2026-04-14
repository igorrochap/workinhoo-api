<?php

namespace App\DTO;

use App\DTO\Prestador\NovoPrestadorDTO;
use App\DTO\Usuario\NovoUsuarioDTO;
use App\Http\Requests\SignupRequest;

class SignupDTO
{
    public function __construct(
        public NovoUsuarioDTO $usuario,
        public ?NovoPrestadorDTO $prestador,
    ) {}

    public static function porRequest(SignupRequest $request): SignupDTO
    {
        $usuario = $request->input('usuario');
        $prestador = $request->input('prestador');

        return new self(
            new NovoUsuarioDTO(
                $usuario['nome'],
                $usuario['email'],
                $usuario['senha'],
                $usuario['contato'],
                $request->file('usuario.foto'),
                $usuario['is_prestador'],
            ),
            $usuario['is_prestador']
                ? new NovoPrestadorDTO(
                    descricao: $prestador['descricao'],
                    instagram: $prestador['instagram'] ?? null,
                    cidadeID: $prestador['cidade_id'],
                    atendeCidadeToda: $prestador['atende_cidade_toda'],
                    latitude: isset($prestador['latitude']) ? (float) $prestador['latitude'] : null,
                    longitude: isset($prestador['longitude']) ? (float) $prestador['longitude'] : null,
                    especialidades: $prestador['especialidades'],
                    bairros: $prestador['bairros'] ?? [],
                )
                : null,
        );
    }
}
