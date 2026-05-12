<?php

namespace App\Http\Controllers;

use App\Actions\Signup;
use App\DTO\SignupDTO;
use App\Events\VerificarEmailEvent;
use App\Http\Requests\SignupRequest;
use App\Services\Auth\VerificarEmail\VerificacaoEmailTokenService;
use Illuminate\Http\JsonResponse;

class SignupController extends Controller
{
    public function __invoke(
        SignupRequest $request,
        Signup $signup,
        VerificacaoEmailTokenService $verificacaoEmail,
    ): JsonResponse {
        $usuario = $signup->executa(SignupDTO::porRequest($request));

        if ($usuario) {
            $response = $verificacaoEmail->enviaCodigo($usuario->email);
            if ($response) {
                VerificarEmailEvent::dispatch(
                    $response['email'],
                    $response['nome'],
                    $response['codigo'],
                );
            }
        }

        return $this->criado(['message' => 'Caso seu cadastro seja válido, você receberá um email de confirmação']);
    }
}
