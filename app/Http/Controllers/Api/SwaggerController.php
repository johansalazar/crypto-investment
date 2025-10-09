<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     title="CryptoInvestment API",
 *     version="1.0.0",
 *     description="Documentación de la API para el monitoreo e inversión en criptomonedas usando CoinMarketCap.",
 *     @OA\Contact(
 *         email="soporte@cryptoinvestment.local"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor local"
 * )
 *
 * @OA\Tag(
 *     name="Coins",
 *     description="Endpoints para gestionar monedas rastreadas"
 * )
 *
 * @OA\Tag(
 *     name="Cryptocurrencies",
 *     description="Endpoints para consultar y actualizar datos desde CoinMarketCap"
 * )
 */
class SwaggerController {}
