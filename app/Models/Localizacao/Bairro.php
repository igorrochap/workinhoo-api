<?php

namespace App\Models\Localizacao;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bairro extends Model
{
    use HasFactory;

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

    public static function porCidade(int $idCidade, array $colunas = ['*']): Collection
    {
        return self::query()->select($colunas)->where('cidade_id', $idCidade)->orderBy('nome')->get();
    }
}
