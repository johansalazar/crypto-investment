<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrackedCoin;
use App\Models\PriceSnapshot;
use App\Services\CoinMarketCapService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CoinController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/coins",
     *     summary="Listar todas las criptomonedas rastreadas",
     *     tags={"Criptomonedas"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de criptomonedas con sus últimos precios"
     *     )
     * )
     */
    public function index()
    {
        $coins = TrackedCoin::with([
            'snapshots' => function ($q) {
                $q->latest('timestamp_utc')->limit(1);
            }
        ])->get();

        return response()->json($coins);
    }

    /**
     * @OA\Post(
     *     path="/api/coins",
     *     summary="Agregar una nueva criptomoneda a la lista de seguimiento",
     *     tags={"Criptomonedas"},
     *     @OA\Parameter(
     *         name="symbol",
     *         in="query",
     *         description="Símbolo de la criptomoneda (ej: BTC, ETH)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Criptomoneda agregada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Símbolo no proporcionado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al conectarse con CoinMarketCap"
     *     )
     * )
     */
    public function store(Request $request, CoinMarketCapService $cmc)
    {
        $symbol = ($request->input('symbol'));

        if (empty($symbol)) {
            return response()->json(['error' => 'El símbolo de la criptomoneda es obligatorio'], 400);
        }

        $coin = TrackedCoin::firstOrCreate(['symbol' => $symbol]);

        try {
            // Consultar el ID y nombre oficial en CoinMarketCap
            $map = $cmc->mapBySymbol($symbol);

            if (!empty($map['data'][0])) {
                $coin->update([
                    'cmc_id' => $map['data'][0]['id'],
                    'name'   => $map['data'][0]['name']
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Error obteniendo datos de CMC para {$symbol}: " . $e->getMessage());
            return response()->json(['error' => 'No se pudo conectar con CoinMarketCap'], 500);
        }

        return response()->json($coin, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/coins/{id}/prices",
     *     summary="Obtener precios históricos de una criptomoneda rastreada",
     *     tags={"Criptomonedas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la criptomoneda rastreada",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         description="Fecha de inicio (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         description="Fecha de fin (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de precios en el rango solicitado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Criptomoneda no encontrada"
     *     )
     * )
     */
    public function prices(Request $request, $id)
    {
        $from = $request->query('from')
            ? Carbon::parse($request->query('from'))
            : Carbon::now()->subDays(7);

        $to = $request->query('to')
            ? Carbon::parse($request->query('to'))
            : Carbon::now();

        $list = PriceSnapshot::where('tracked_coin_id', $id)
            ->whereBetween('timestamp_utc', [$from, $to])
            ->orderBy('timestamp_utc', 'asc')
            ->get();

        return response()->json([
            'coin_id' => $id,
            'from' => $from->toDateTimeString(),
            'to' => $to->toDateTimeString(),
            'data' => $list
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/coins/{id}/latest",
     *     summary="Obtener el precio más reciente de una criptomoneda rastreada",
     *     tags={"Criptomonedas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la criptomoneda rastreada",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Último precio registrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No hay registros de precios"
     *     )
     * )
     */
    public function latest($id)
    {
        $latest = PriceSnapshot::where('tracked_coin_id', $id)
            ->latest('timestamp_utc')
            ->first();

        if (!$latest) {
            return response()->json(['message' => 'No hay registros de precios para esta moneda'], 404);
        }

        return response()->json($latest);
    }

    /**
     * @OA\Delete(
     *     path="/api/coins/{id}",
     *     summary="Eliminar una criptomoneda rastreada",
     *     tags={"Criptomonedas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la criptomoneda rastreada a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criptomoneda eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Criptomoneda no encontrada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $coin = TrackedCoin::find($id);

        if (!$coin) {
            return response()->json(['error' => 'Moneda no encontrada'], 404);
        }

        $coin->delete();

        return response()->json(['deleted' => true]);
    }
}
