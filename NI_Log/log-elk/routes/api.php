<?php

use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;

Route::get('/logs', [LogController::class, 'generate']);
Route::get('/logs/exception', [LogController::class, 'simulateException']);
