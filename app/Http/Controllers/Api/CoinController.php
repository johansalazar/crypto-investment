<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrackedCoin;
use App\Models\PriceSnapshot;
use App\Services\CoinMarketCapService;
use Carbon\Carbon;

class CoinController extends Controller {
    public function index(){
        return TrackedCoin::with(['snapshots' => function($q){ $q->latest()->limit(1); }])->get();
    }

    public function store(Request $r, CoinMarketCapService $cmc){
        $symbol = strtoupper($r->input('symbol'));
        $coin = TrackedCoin::firstOrCreate(['symbol' => $symbol], ['name' => null]);

        try {
            $map = $cmc->mapBySymbol($symbol);
            if (!empty($map['data'][0])) {
                $coin->update(['cmc_id' => $map['data'][0]['id'], 'name' => $map['data'][0]['name']]);
            }
        } catch (\Exception $e) {
            // log
        }

        return response()->json($coin, 201);
    }

    public function prices(Request $r, $id){
        $from = $r->query('from') ? Carbon::parse($r->query('from')) : Carbon::now()->subDays(7);
        $to = $r->query('to') ? Carbon::parse($r->query('to')) : Carbon::now();
        $list = PriceSnapshot::where('tracked_coin_id', $id)
                  ->whereBetween('timestamp_utc', [$from, $to])
                  ->orderBy('timestamp_utc','asc')->get();
        return response()->json($list);
    }

    public function latest($id){
        $latest = PriceSnapshot::where('tracked_coin_id',$id)->latest('timestamp_utc')->first();
        return response()->json($latest);
    }

    public function destroy($id) {
        TrackedCoin::findOrFail($id)->delete();
        return response()->json(['deleted' => true]);
    }
}
