<?php

namespace App\Http\Controllers\Auth;

use App\Events\VerificarEmailEvent;
use App\Http\Controllers\Controller;
use App\Services\Auth\VerificarEmail\VerificacaoEmailTokenService;
use Symfony\Component\HttpFoundation\Response;

class VerificacaoEmailController extends Controller
{
    public function __construct(private readonly VerificacaoEmailTokenService $service) {}

    public function enviaCodigo(string $email)
    {
        $response = $this->service->enviaCodigo($email);

        if ($response) {
            VerificarEmailEvent::dispatch(
                $response['email'],
                $response['nome'],
                $response['codigo'],
            );
        }

        return $this->sucesso(['message' => 'Caso o cadastro exista e esteja pendente, você receberá um email de confirmação']);
    }

    public function validaCodigo(string $codigoInformado)
    {
        if (! $codigoInformado) {
            return response()->json('Nenhum token foi informado!', Response::HTTP_BAD_REQUEST);
        }

        $this->service->validaToken($codigoInformado);

        return $this->sucesso(['message' => 'E-mail verificado com sucesso']);
    }
}
