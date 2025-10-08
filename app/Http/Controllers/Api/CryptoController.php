<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Cryptocurrency;

class CryptoController extends Controller
{
    public function updateData()
    {
        $url = config('coinmarketcap.base_url') . 'cryptocurrency/listings/latest';

        $response = Http::withHeaders([
            'X-CMC_PRO_API_KEY' => config('coinmarketcap.api_key'),
            'Accept' => 'application/json',
        ])->get($url, ['limit' => 10]);

        $data = $response->json()['data'];

        foreach ($data as $coin) {
            Cryptocurrency::updateOrCreate(
                ['symbol' => $coin['symbol']],
                [
                    'name' => $coin['name'],
                    'price' => $coin['quote']['USD']['price'],
                    'percent_change_24h' => $coin['quote']['USD']['percent_change_24h'],
                    'market_cap' => $coin['quote']['USD']['market_cap'],
                    'volume_24h' => $coin['quote']['USD']['volume_24h'],
                ]
            );
        }

        return response()->json(['message' => 'Datos actualizados']);
    }

    public function list()
    {
        return Cryptocurrency::orderBy('market_cap', 'desc')->get();
    }
}
