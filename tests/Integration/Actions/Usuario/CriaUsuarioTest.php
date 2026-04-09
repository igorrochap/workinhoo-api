<?php

use App\Actions\Usuario\CriaUsuario;
use App\DTO\Usuario\NovoUsuarioDTO;
use App\Models\Usuario\Usuario;
use App\Support\Storage\Arquivo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake("avatars");
    $arquivo = New Arquivo();

    $foto = UploadedFile::fake()->image('avatar.jpg');
    $this->dto = new NovoUsuarioDTO('John Doe', 'johndoe@test.com', '123456', '(00) 00000-0000', $foto, false);

    $this->action = new CriaUsuario($arquivo);
});

test('cria usuario', function () {
    $usuario = $this->action->executa($this->dto);

    expect($usuario)->toBeInstanceOf(Usuario::class);
});

test('cria usuario com dados válidos', function () {
    $usuario = $this->action->executa($this->dto);

    expect($usuario->nome)->toBe($this->dto->nome)
        ->and($usuario->email)->toBe($this->dto->email)
        ->and($usuario->contato)->toBe($this->dto->contato)
        ->and($usuario->is_prestador)->toBeFalse();
});

test('cria hash da senha', function () {
    $usuario = $this->action->executa($this->dto);

    expect(Hash::check($this->dto->password, $usuario->password))->toBeTrue();
});

test('cria foto de perfil do usuário', function () {
    $usuario = $this->action->executa($this->dto);

    Storage::disk('avatars')->assertExists($usuario->path_foto);
});

