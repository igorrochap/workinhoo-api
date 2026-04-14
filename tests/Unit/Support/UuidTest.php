<?php

use App\Support\ValueObjects\UUID;
use Illuminate\Support\Str;

test('cria uuid', function () {
    $uuid = UUID::cria();

    expect($uuid)->toBeInstanceOf(UUID::class);
});

test('cria uuid valido', function () {
    $uuid = UUID::cria();

    expect(Str::isUuid($uuid->recupera()))->toBeTrue();
});
