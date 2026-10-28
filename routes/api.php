<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPriceController;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
 
    Route::get('products', [ProductController::class,
    'index']);
    Route::get('products/{id}', [ProductController::class,
    'show']);
    Route::get('products/{id}/prices',
    [ProductPriceController::class, 'index']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('products', [ProductController::class,
        'store']);
        Route::match(['put', 'patch'], 'products/{id}',
        [ProductController::class, 'update']);
        Route::delete('products/{id}',
        [ProductController::class, 'destroy']);
        Route::post('products/{id}/prices',
        [ProductPriceController::class, 'store']);
    });