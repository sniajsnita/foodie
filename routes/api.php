<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\APi\CategoryController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\CoinTransactionController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('categories',        CategoryController::class);
    Route::apiResource('recipes',           RecipeController::class);
    Route::apiResource('coin-transactions', CoinTransactionController::class)->except('store');
    Route::post('coin-transactions/topup', [CoinTransactionController::class, 'topup']);
    Route::post('coin-transactions/tarik', [CoinTransactionController::class, 'tarik']);
});
