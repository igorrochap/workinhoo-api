<?php

namespace Database\Factories\Prestador;

use App\Models\Localizacao\Cidade;
use App\Models\Prestador\Prestador;
use App\Models\Usuario\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prestador>
 */
class PrestadorFactory extends Factory
{
    protected $model = Prestador::class;

    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory()->create(['is_prestador' => true])->id,
            'descricao' => fake()->paragraph(),
            'instagram' => null,
            'cidade_id' => Cidade::factory(),
            'atende_cidade_toda' => false,
            'latitude' => null,
            'longitude' => null,
        ];
    }
}
