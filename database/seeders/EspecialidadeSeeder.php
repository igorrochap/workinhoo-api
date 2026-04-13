<?php

namespace Database\Seeders;

use App\Models\Prestador\Especialidade;
use Illuminate\Database\Seeder;

class EspecialidadeSeeder extends Seeder
{
    public function run(): void
    {
        $especialidades = [
            ['nome' => 'Encanador(a)'],
            ['nome' => 'Pintor(a)'],
            ['nome' => 'Tatuador(a)'],
            ['nome' => 'Eletricista'],
            ['nome' => 'Pedreiro(a)'],
            ['nome' => 'Carpinteiro(a)'],
            ['nome' => 'Marceneiro(a)'],
            ['nome' => 'Serralheiro(a)'],
            ['nome' => 'Soldador(a)'],
            ['nome' => 'Mecânico(a)'],
            ['nome' => 'Chaveiro(a)'],
            ['nome' => 'Vidraceiro(a)'],
            ['nome' => 'Jardineiro(a)'],
            ['nome' => 'Diarista'],
            ['nome' => 'Cozinheiro(a)'],
            ['nome' => 'Babá'],
            ['nome' => 'Cuidador(a) de Idosos'],
            ['nome' => 'Personal Trainer'],
            ['nome' => 'Nutricionista'],
            ['nome' => 'Fisioterapeuta'],
            ['nome' => 'Psicólogo(a)'],
            ['nome' => 'Cabeleireiro(a)'],
            ['nome' => 'Barbeiro(a)'],
            ['nome' => 'Manicure'],
            ['nome' => 'Maquiador(a)'],
            ['nome' => 'Esteticista'],
            ['nome' => 'Massagista'],
            ['nome' => 'Designer Gráfico(a)'],
            ['nome' => 'Fotógrafo(a)'],
            ['nome' => 'Videomaker'],
            ['nome' => 'Tradutor(a)'],
            ['nome' => 'Professor(a) Particular'],
            ['nome' => 'Contador(a)'],
            ['nome' => 'Advogado(a)'],
            ['nome' => 'Arquiteto(a)'],
            ['nome' => 'Decorador(a) de Interiores'],
            ['nome' => 'Azulejista'],
            ['nome' => 'Gesseiro(a)'],
            ['nome' => 'Impermeabilizador(a)'],
            ['nome' => 'Dedetizador(a)'],
            ['nome' => 'Técnico(a) em Informática'],
            ['nome' => 'Instalador(a) de Ar-Condicionado'],
            ['nome' => 'Técnico(a) em Refrigeração'],
            ['nome' => 'Técnico(a) em Elevador'],
            ['nome' => 'Montador(a) de Móveis'],
            ['nome' => 'Mudador(a)'],
            ['nome' => 'Motorista Particular'],
            ['nome' => 'Motoboy'],
            ['nome' => 'Personal Organizer'],
            ['nome' => 'DJ'],
        ];

        if (Especialidade::query()->count() > 0) {
            return;
        }

        Especialidade::query()->insert($especialidades);
    }
}
