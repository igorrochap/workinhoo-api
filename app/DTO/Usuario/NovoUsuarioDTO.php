<?php

namespace App\DTO\Usuario;

use Illuminate\Http\UploadedFile;

final readonly class NovoUsuarioDTO
{
    public function __construct(
        public string $nome,
        public string $email,
        public string $password,
        public string $contato,
        public UploadedFile $foto,
        public bool $isPrestador
    ) {}

    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
            'email' => $this->email,
            'password' => $this->password,
            'contato' => $this->contato,
            'is_prestador' => $this->isPrestador,
        ];
    }
}
