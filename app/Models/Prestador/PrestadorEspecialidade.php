<?php

namespace App\Models\Prestador;

use Illuminate\Database\Eloquent\Model;

class PrestadorEspecialidade extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'prestador_id',
        'especialidade_id',
        'subcategorias',
    ];
}
