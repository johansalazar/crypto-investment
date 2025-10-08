<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\TrackedCoin;
use App\Models\PriceSnapshot;
use App\Services\CoinMarketCapService;
use Carbon\Carbon;

class CollectSnapshots extends Command {
    protected $signature = 'coins:collect';
    protected $description = 'Collect latest quotes from CoinMarketCap and persist snapshots';

    public function handle(CoinMarketCapService $cmc){
        $coins = TrackedCoin::where('is_active', true)->get();
        if ($coins->isEmpty()) return 0;

        $symbols = $coins->pluck('symbol')->toArray();
        // chunk to avoid too many symbols per request
        $chunks = array_chunk($symbols, 20);
        foreach ($chunks as $chunk){
            try {
                $res = $cmc->quotesLatest($chunk);
                $now = Carbon::now()->toDateTimeString();
                foreach ($chunk as $sym){
                    if (!isset($res['data'][$sym])) continue;
                    $d = $res['data'][$sym];
                    $quote = $d['quote']['USD'];
                    $coin = $coins->firstWhere('symbol', $sym);
                    PriceSnapshot::create([
                        'tracked_coin_id' => $coin->id,
                        'timestamp_utc' => $now,
                        'price_usd' => $quote['price'] ?? 0,
                        'percent_change_24h' => $quote['percent_change_24h'] ?? null,
                        'volume_24h' => $quote['volume_24h'] ?? null,
                        'market_cap' => $quote['market_cap'] ?? null,
                        'raw' => $d
                    ]);
                }
            } catch (\Exception $e) {
                // log error, continuar
            }
        }
        return 0;
    }
}
