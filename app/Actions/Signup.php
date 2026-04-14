<?php

namespace App\Actions;

use App\Actions\Prestador\CriaPrestador;
use App\Actions\Usuario\CriaUsuario;
use App\DTO\SignupDTO;
use App\Models\Usuario\Usuario;
use Illuminate\Support\Facades\DB;

final readonly class Signup
{
    public function __construct(
        private CriaUsuario $criaUsuario,
        private CriaPrestador $criaPrestador,
    ) {}

    public function executa(SignupDTO $dto): void
    {
        if (! is_null(Usuario::porEmail($dto->usuario->email))) {
            return;
        }
        DB::transaction(function () use ($dto) {
            $usuario = $this->criaUsuario->executa($dto->usuario);
            $this->fluxoPrestador($usuario, $dto);
        });
    }

    private function fluxoPrestador(Usuario $usuario, SignupDTO $dto): void
    {
        if ($dto->usuario->isPrestador && $dto->prestador !== null) {
            $this->criaPrestador->executa($dto->prestador, $usuario->id);
        }
    }
}
