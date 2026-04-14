<?php

namespace App\Models\Localizacao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estado extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['nome', 'uf', 'carregado'];

    protected $casts = [
        'carregado' => 'boolean',
    ];

    public function cidades(): HasMany
    {
        return $this->hasMany(Cidade::class);
    }
}
