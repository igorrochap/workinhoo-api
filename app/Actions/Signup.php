<?php

namespace App\Actions;

use App\Actions\Usuario\CriaUsuario;
use App\DTO\Usuario\NovoUsuarioDTO;
use App\Models\Usuario\Usuario;
use Illuminate\Support\Facades\DB;

final readonly class Signup
{
    public function __construct(
        private CriaUsuario $criaUsuario,
    ) {}

    public function executa(NovoUsuarioDTO $dto): void
    {
        if (! is_null(Usuario::porEmail($dto->email))) {
            return;
        }
        DB::transaction(function () use ($dto) {
            $usuario = $this->criaUsuario->executa($dto);
            // TODO: fluxo quando usuário é um prestador de serviços
        });
    }
}
