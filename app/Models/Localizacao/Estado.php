<?php

namespace App\Models\Localizacao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estado extends Model
{
    public $timestamps = false;

    protected $casts = [
        'carregado' => 'boolean',
    ];

    public function cidades(): HasMany
    {
        return $this->hasMany(Cidade::class);
    }
}
