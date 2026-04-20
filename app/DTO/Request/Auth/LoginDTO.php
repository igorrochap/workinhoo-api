<?php

namespace App\DTO\Request\Auth;

final readonly class LoginDTO
{
    public function __construct(
        public string $credencial,
        public string $senha,
    ) {}
}
