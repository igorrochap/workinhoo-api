<?php

use App\Http\Controllers\Localizacao\BairroController;
use App\Http\Controllers\Localizacao\CidadeController;
use App\Http\Controllers\Prestador\EspecialidadeController;
use App\Http\Controllers\Prestador\PortfolioController;
use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\Route;

Route::post('/signup', SignupController::class);

Route::get('cidades', [CidadeController::class, 'index']);
Route::get('bairros', [BairroController::class, 'index']);
Route::get('especialidades', [EspecialidadeController::class, 'index']);

Route::prefix('prestadores/{prestador}/portfolios')->group(function () {
    Route::get('/',                    [PortfolioController::class, 'index']);
    Route::get('/{uuid}',              [PortfolioController::class, 'show']);
    Route::post('/',                   [PortfolioController::class, 'store']);
    Route::put('/{portfolio}',         [PortfolioController::class, 'update']);
    Route::patch('/{portfolio}/foto',  [PortfolioController::class, 'updateFoto']);
    Route::delete('/{portfolio}/foto', [PortfolioController::class, 'destroyFoto']);
    Route::delete('/{portfolio}',      [PortfolioController::class, 'destroy']);
});
