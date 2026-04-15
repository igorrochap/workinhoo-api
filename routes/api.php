<?php

use App\Http\Controllers\Localizacao\BairroController;
use App\Http\Controllers\Localizacao\CidadeController;
use App\Http\Controllers\Prestador\EspecialidadeController;
use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\Route;

Route::post('/signup', SignupController::class);

Route::get('cidades', [CidadeController::class, 'index']);
Route::get('bairros', [BairroController::class, 'index']);
Route::get('especialidades', [EspecialidadeController::class, 'index']);
