<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VerificarEmailEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $email,
        public readonly string $nome,
        public readonly string $codigo,
    ) {}
}
