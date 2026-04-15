<?php

use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\Route;

Route::post('/signup', SignupController::class);
