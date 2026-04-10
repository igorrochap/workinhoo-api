<?php

namespace App\Http\Controllers;

use App\Actions\Signup;
use App\DTO\SignupDTO;
use App\Http\Requests\SignupRequest;
use Illuminate\Http\JsonResponse;

class SignupController extends Controller
{
    public function __invoke(SignupRequest $request, Signup $signup): JsonResponse
    {
        $signup->executa(SignupDTO::porRequest($request));
        return $this->criado(['message' => 'Caso seu cadastro seja válido, você receberá um email de confirmação']);
    }
}
