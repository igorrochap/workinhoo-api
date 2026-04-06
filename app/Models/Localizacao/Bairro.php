<?php

namespace App\Models\Localizacao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bairro extends Model
{
    public $fillable = [
        'nome',
        'cidade_id',
        'slug',
        'latitude',
        'longitude',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }
}
