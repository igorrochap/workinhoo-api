<?php

namespace App\Actions\Usuario;

use App\DTO\Usuario\NovoUsuarioDTO;
use App\Models\Usuario\Usuario;
use App\Support\Storage\Arquivo;
use App\Support\ValueObjects\UUID;

final readonly class CriaUsuario
{
    public function __construct(
        private Arquivo $arquivo
    ) {}

    public function executa(NovoUsuarioDTO $dto): Usuario
    {
        $fotoID = UUID::cria();
        $pathFoto = $this->arquivo->persiste($dto->foto, '', "{$fotoID->recupera()}.webp", ['disk' => 'avatars']);

        dump($pathFoto);

        return Usuario::query()->create([...$dto->toArray(), 'path_foto' => $pathFoto]);
    }
}
