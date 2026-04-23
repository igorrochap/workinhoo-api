<?php

namespace App\Models\Usuario;

use App\Support\ValueObjects\UUID;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Hidden(['password', 'remember_token'])]
class Usuario extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'nome',
        'email',
        'password',
        'contato',
        'path_foto',
        'is_prestador',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Usuario $usuario) {
            $usuario->uuid = UUID::cria()->recupera();
        });
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_prestador' => 'boolean',
        ];
    }

    public static function porEmail(string $email): ?Usuario
    {
        return self::query()->where('email', $email)->first();
    }
}
