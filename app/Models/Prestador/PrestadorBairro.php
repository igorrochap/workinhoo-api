<?php

namespace App\Models\Prestador;

use Illuminate\Database\Eloquent\Model;

class PrestadorBairro extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'prestador_id',
        'bairro_id',
    ];
}
