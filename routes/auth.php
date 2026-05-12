<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerificacaoEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('recuperar-senha/{email}', [PasswordController::class, 'enviaCodigo']);
    Route::post('recuperar-senha/validar/{codigo}', [PasswordController::class, 'validaCodigo']);
    Route::post('email/verificacao/{email}', [VerificacaoEmailController::class, 'enviaCodigo']);
    Route::post('email/verificar/{codigo}', [VerificacaoEmailController::class, 'validaCodigo']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});
