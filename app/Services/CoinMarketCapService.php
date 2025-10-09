<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CoinMarketCapService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.cmc.key', env('COINMARKETCAP_API_KEY'));
        $this->baseUrl = env('COINMARKETCAP_BASE', 'https://pro-api.coinmarketcap.com/v1');
    }

    /**
     * Obtener cotizaciones por símbolo (BTC, ETH, etc.)
     */
    public function quotesLatest(array $symbols): array
    {
        $symbolsCsv = implode(',', $symbols);

        $response = Http::withHeaders([
            'X-CMC_PRO_API_KEY' => $this->apiKey,
            'Accept' => 'application/json',
        ])->timeout(10)->get($this->baseUrl . 'cryptocurrency/quotes/latest', [
            'symbol' => $symbolsCsv,
            'convert' => 'USD',
        ]);

        if ($response->failed()) {
            throw new \Exception('Error al consultar CoinMarketCap: ' . $response->body());
        }

        return $response->json()['data'] ?? [];
    }

    /**
     * Obtener información del mapa de monedas
     */
    public function mapBySymbol(string $symbol): array
    {
        $response = Http::withHeaders([
            'X-CMC_PRO_API_KEY' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get($this->baseUrl . 'cryptocurrency/map', [
            'symbol' => $symbol,
        ]);

        if ($response->failed()) {
            throw new \Exception('Error al consultar CoinMarketCap: ' . $response->body());
        }

        return $response->json()['data'] ?? [];
    }

    /**
     * Obtener listado de criptomonedas
     */
    public function getLatestListings(int $limit = 10): array
    {
        $response = Http::withHeaders([
            'X-CMC_PRO_API_KEY' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get($this->baseUrl . 'cryptocurrency/listings/latest', [
            'limit' => $limit,
            'convert' => 'USD',
        ]);

        if ($response->failed()) {
            throw new \Exception('Error al consultar CoinMarketCap: ' . $response->body());
        }

        return $response->json()['data'] ?? [];
    }

    /**
     * Obtener cotización de una moneda específica
     */
    public function getQuote(string $symbol): array
    {
        $response = Http::withHeaders([
            'X-CMC_PRO_API_KEY' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get($this->baseUrl . 'cryptocurrency/quotes/latest', [
            'symbol' => $symbol,
            'convert' => 'USD',
        ]);

        if ($response->failed()) {
            throw new \Exception('Error al consultar CoinMarketCap: ' . $response->body());
        }

        return $response->json()['data'][$symbol] ?? [];
    }
}
