<?php

namespace Database\Factories\Localizacao;

use App\Models\Localizacao\Cidade;
use App\Models\Localizacao\Estado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cidade>
 */
class CidadeFactory extends Factory
{
    protected $model = Cidade::class;

    public function definition(): array
    {
        $nome = fake()->unique()->city();

        return [
            'nome' => $nome,
            'slug' => str($nome)->slug()->value(),
            'codigo_ibge' => fake()->unique()->numerify('#######'),
            'estado_id' => Estado::factory(),
        ];
    }
}
