<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderTestController;

Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/random', [OrderTestController::class, 'random']);
