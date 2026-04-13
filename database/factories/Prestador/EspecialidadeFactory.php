<?php

namespace Database\Factories\Prestador;

use App\Models\Prestador\Especialidade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Especialidade>
 */
class EspecialidadeFactory extends Factory
{
    protected $model = Especialidade::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->unique()->word(),
        ];
    }
}
