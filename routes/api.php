<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CoinController;
use App\Http\Controllers\Api\CryptoController;

/*
|--------------------------------------------------------------------------
| Rutas API para manejo de criptomonedas
|--------------------------------------------------------------------------
| Todas estas rutas estarán disponibles bajo el prefijo automático /api/
| Ejemplo: GET /api/coins, POST /api/coins, etc.
*/

// === RUTAS DE COINS === //
Route::prefix('coins')->group(function () {
    Route::get('/', [CoinController::class, 'index']);               // Listar todas las monedas seguidas
    Route::post('/', [CoinController::class, 'store']);              // Agregar una nueva moneda por símbolo
    Route::get('/{id}/prices', [CoinController::class, 'prices']);   // Consultar histórico de precios
    Route::get('/{id}/latest', [CoinController::class, 'latest']);   // Último precio registrado
    Route::delete('/{id}', [CoinController::class, 'destroy']);      // Eliminar una moneda
});

// === RUTAS DE CRYPTOS (sin duplicados) === //
Route::prefix('cryptos')->group(function () {
    Route::get('/', [CryptoController::class, 'list']);              // Listar criptos disponibles
    Route::get('/update', [CryptoController::class, 'updateData']);  // Actualizar datos desde CoinMarketCap
});
