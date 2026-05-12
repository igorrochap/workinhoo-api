<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;

class TokenVerificacaoEmailInvalidoException extends Exception
{
    public static function exception(): ValidationException
    {
        return ValidationException::withMessages([
            'token' => 'O token informado é inválido ou expirou.',
        ]);
    }
}
