<?php

namespace Database\Factories\Usuario;

use App\Models\Usuario\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Support\ValueObjects\UUID;

/**
 * @extends Factory<Usuario>
 */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'nome'         => fake()->name(),
            'email'        => fake()->unique()->safeEmail(),
            'password'     => 'password',
            'contato'      => fake()->numerify('###########'),
            'path_foto'    => 'avatars/' . UUID::cria()->recupera() . '.webp',
            'is_prestador' => false,
        ];
    }
}
