<?php

namespace App\Http\Controllers\Auth;

use App\Events\RecuperarSenhaEvent;
use App\Http\Controllers\Controller;
use App\Services\Auth\RecuperarSenha\RecuperacaoSenhaTokenService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PasswordController extends Controller
{
    public function __construct(private readonly RecuperacaoSenhaTokenService $service) {}

    public function enviaCodigo(string $email)
    {
        if ($email) {
            try{
                $response = $this->service->enviaCodigo($email);
                if (is_null($response)) {
                    return response()->json('Email não cadastrado!', Response::HTTP_NOT_FOUND);
                }

                RecuperarSenhaEvent::dispatch(
                    $response['email'],
                    $response['nome'],
                    $response['codigo']
                );

            } catch(\Exception $e) {
                Log::error($e->getMessage());
                return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

    }

    public function validaCodigo(string $codigoInformado)
    {
        if (! $codigoInformado) {
            return response()->json('Nenhum token foi informado!', Response::HTTP_BAD_REQUEST);
        }

        try{
            $this->service->validaToken($codigoInformado);
            return $this->sucesso();
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
