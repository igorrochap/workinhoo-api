<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AutenticaUsuario;
use App\Actions\Auth\DeslogaUsuario;
use App\DTO\Request\Auth\LoginDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\UsuarioAutenticadoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request, AutenticaUsuario $autenticaUsuario): JsonResponse
    {
        $usuario = $autenticaUsuario->executa(new LoginDTO(
            $request->string('credencial'),
            $request->string('senha'),
        ));

        $request->session()->regenerate();

        return $this->sucesso(new UsuarioAutenticadoResource($usuario));
    }

    public function logout(Request $request, DeslogaUsuario $deslogaUsuario): JsonResponse
    {
        $deslogaUsuario->executa($request);

        return $this->semConteudo();
    }

    public function me(Request $request): JsonResponse
    {
        return $this->sucesso(new UsuarioAutenticadoResource($request->user()));
    }
}
