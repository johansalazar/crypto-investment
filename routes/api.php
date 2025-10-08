<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CoinController;
use App\Http\Controllers\Api\CryptoController;

Route::get('coins', [CoinController::class,'index']);
Route::post('coins', [CoinController::class,'store']);
Route::delete('coins/{id}', [CoinController::class,'destroy']);
Route::get('coins/{id}/prices', [CoinController::class,'prices']);
Route::get('coins/{id}/latest', [CoinController::class,'latest']);
Route::get('/cryptos', [CryptoController::class, 'list']);
Route::get('/cryptos/update', [CryptoController::class, 'updateData']);
