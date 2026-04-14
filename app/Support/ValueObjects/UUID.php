<?php

namespace App\Support\ValueObjects;

use Illuminate\Support\Str;

final readonly class UUID
{
    public function __construct(
        private string $uuid,
    ) {}

    public static function cria(): UUID
    {
        $uuid = Str::uuid7();

        return new self($uuid->toString());
    }

    public function recupera(): string
    {
        return $this->uuid;
    }
}
