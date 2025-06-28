<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CoinTransactionController;


Route::apiResource('recipes', RecipeController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('CoinTransactions', CoinTransactionController::class);

Route::post('CoinTransactions/topup', [CoinTransactionController::class, 'topup']);
Route::post('CoinTransactions/tarik', [CoinTransactionController::class, 'tarik']);
