<?php

namespace Database\Factories\Localizacao;

use App\Models\Localizacao\Bairro;
use App\Models\Localizacao\Cidade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bairro>
 */
class BairroFactory extends Factory
{
    protected $model = Bairro::class;

    public function definition(): array
    {
        $nome = fake()->unique()->streetName();

        return [
            'nome' => $nome,
            'cidade_id' => Cidade::factory(),
            'slug' => str($nome)->slug()->value(),
            'latitude' => null,
            'longitude' => null,
            'ativo' => true,
        ];
    }
}
