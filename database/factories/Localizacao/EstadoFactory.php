<?php

namespace Database\Factories\Localizacao;

use App\Models\Localizacao\Estado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Estado>
 */
class EstadoFactory extends Factory
{
    protected $model = Estado::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->unique()->word(),
            'uf' => strtoupper(fake()->unique()->lexify('??')),
            'carregado' => false,
        ];
    }
}
