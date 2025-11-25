<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

Route::get('/products',        [ProductController::class, 'index']);
Route::post('/products',       [ProductController::class, 'store']);
Route::get('/products/{id}',   [ProductController::class, 'show']);
Route::put('/products/{id}',   [ProductController::class, 'update']);
Route::delete('/products/{id}',[ProductController::class, 'destroy']);

Route::post('/orders/calculate', [OrderController::class, 'calculate']);
