<?php

namespace App\Models\Usuario;

use Illuminate\Database\Eloquent\Model;

class PasswordResetTokens extends Model
{
    protected $table = 'password_reset_tokens';

    protected $primaryKey = 'email';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['email', 'token', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public static function porToken(string $token): ?PasswordResetTokens
    {
        return PasswordResetTokens::where('token', $token)->first();
    }
}
