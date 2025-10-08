<?php

namespace App\Services;

use GuzzleHttp\Client;

class CoinMarketCapService {
    protected Client $http;
    protected string $apiKey;

    public function __construct(){
        $this->apiKey = config('services.cmc.key', env('CMC_API_KEY'));
        $this->http = new Client(['base_uri' => env('CMC_BASE', 'https://pro-api.coinmarketcap.com')]);
    }

    public function quotesLatest(array $symbols): array {
        $symbolsCsv = implode(',', $symbols);
        $res = $this->http->request('GET', '/v1/cryptocurrency/quotes/latest', [
            'headers' => ['X-CMC_PRO_API_KEY' => $this->apiKey],
            'query' => ['symbol' => $symbolsCsv, 'convert' => 'USD'],
            'timeout' => 10
        ]);
        return json_decode($res->getBody()->getContents(), true);
    }

    public function mapBySymbol(string $symbol): array {
        $res = $this->http->request('GET', '/v1/cryptocurrency/map', [
            'headers' => ['X-CMC_PRO_API_KEY' => $this->apiKey],
            'query' => ['symbol' => $symbol]
        ]);
        return json_decode($res->getBody()->getContents(), true);
    }
}
