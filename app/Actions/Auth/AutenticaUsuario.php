<?php

namespace App\Actions\Auth;

use App\DTO\Request\Auth\LoginDTO;
use App\Models\Usuario\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final readonly class AutenticaUsuario
{
    public function executa(LoginDTO $dto): Usuario
    {
        if (! Auth::attempt(['email' => $dto->credencial, 'password' => $dto->senha])) {
            throw ValidationException::withMessages([
                'credencial' => ['As credenciais informadas estão incorretas.'],
            ]);
        }

        return Auth::user();
    }
}
