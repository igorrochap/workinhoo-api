<?php

namespace App\Actions;

use App\Actions\Usuario\CriaUsuario;
use App\DTO\SignupDTO;
use App\Models\Usuario\Usuario;
use Illuminate\Support\Facades\DB;

final readonly class Signup
{
    public function __construct(
        private CriaUsuario $criaUsuario,
    ) {}

    public function executa(SignupDTO $dto): void
    {
        if (! is_null(Usuario::porEmail($dto->usuario->email))) {
            return;
        }
        DB::transaction(function () use ($dto) {
            $usuario = $this->criaUsuario->executa($dto->usuario);
            // TODO: fluxo quando usuário é um prestador de serviços
        });
    }
}
