<?php

namespace App\Models\Localizacao;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cidade extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'slug',
        'codigo_ibge',
        'estado_id',
    ];

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    public static function porUF(string $uf): Collection
    {
        return self::query()
            ->select(['cidades.*'])
            ->join('estados', 'estados.id', '=', 'cidades.estado_id')
            ->where('estados.uf', $uf)
            ->get();
    }
}
