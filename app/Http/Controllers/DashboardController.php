<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Gestion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Estadísticas generales
        $totalClientes = Cliente::count();
        $totalGestiones = Gestion::count();

        // Estadísticas por mes (últimos 6 meses)
        $clientesPorMes = Cliente::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
            DB::raw('count(*) as total')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('mes')
        ->orderBy('mes', 'asc')
        ->get();

        // Top 5 clientes con más pagos registrados (contando meses con pagos)
        $topClientes = Cliente::select('id_cliente', 'nombre')
            ->selectRaw('
                (CASE WHEN ENERO IS NOT NULL AND ENERO > 0 THEN 1 ELSE 0 END +
                 CASE WHEN FEBRERO IS NOT NULL AND FEBRERO > 0 THEN 1 ELSE 0 END +
                 CASE WHEN MARZO IS NOT NULL AND MARZO > 0 THEN 1 ELSE 0 END +
                 CASE WHEN ABRIL IS NOT NULL AND ABRIL > 0 THEN 1 ELSE 0 END +
                 CASE WHEN MAYO IS NOT NULL AND MAYO > 0 THEN 1 ELSE 0 END +
                 CASE WHEN JUNIO IS NOT NULL AND JUNIO > 0 THEN 1 ELSE 0 END +
                 CASE WHEN JULIO IS NOT NULL AND JULIO > 0 THEN 1 ELSE 0 END +
                 CASE WHEN AGOSTO IS NOT NULL AND AGOSTO > 0 THEN 1 ELSE 0 END +
                 CASE WHEN SEPTIEMBRE IS NOT NULL AND SEPTIEMBRE > 0 THEN 1 ELSE 0 END +
                 CASE WHEN OCTUBRE IS NOT NULL AND OCTUBRE > 0 THEN 1 ELSE 0 END +
                 CASE WHEN NOVIEMBRE IS NOT NULL AND NOVIEMBRE > 0 THEN 1 ELSE 0 END +
                 CASE WHEN DICIEMBRE IS NOT NULL AND DICIEMBRE > 0 THEN 1 ELSE 0 END) as pagos_count
            ')
            ->orderBy('pagos_count', 'desc')
            ->take(5)
            ->get();

        // Total ganado: suma de todos los meses de todos los clientes
        $totalGanado = Cliente::sum(DB::raw('
            COALESCE(ENERO,0)+COALESCE(FEBRERO,0)+COALESCE(MARZO,0)+COALESCE(ABRIL,0)+COALESCE(MAYO,0)+COALESCE(JUNIO,0)+COALESCE(JULIO,0)+COALESCE(AGOSTO,0)+COALESCE(SEPTIEMBRE,0)+COALESCE(OCTUBRE,0)+COALESCE(NOVIEMBRE,0)+COALESCE(DICIEMBRE,0)
        '));

        // Promedio mensual de los últimos 12 meses
        $fechaInicio = Carbon::now()->subMonths(12)->startOfMonth();
        $clientesUltimoAno = Cliente::where('updated_at', '>=', $fechaInicio)->get();
        $sumaUltimoAno = 0;
        foreach ($clientesUltimoAno as $cliente) {
            $sumaUltimoAno += ($cliente->ENERO ?? 0) + ($cliente->FEBRERO ?? 0) + ($cliente->MARZO ?? 0) + ($cliente->ABRIL ?? 0) + ($cliente->MAYO ?? 0) + ($cliente->JUNIO ?? 0) + ($cliente->JULIO ?? 0) + ($cliente->AGOSTO ?? 0) + ($cliente->SEPTIEMBRE ?? 0) + ($cliente->OCTUBRE ?? 0) + ($cliente->NOVIEMBRE ?? 0) + ($cliente->DICIEMBRE ?? 0);
        }
        $promedioMensual = $sumaUltimoAno / 12;

        // Últimos registros editados (últimos 5 clientes actualizados)
        $ultimosEditados = Cliente::orderBy('updated_at', 'desc')->take(5)->get();

        // Gestiones recientes (últimas 5)
        $gestionesRecientes = Gestion::orderBy('created_at', 'desc')->take(5)->get();

        // Para el menú de filtro de ganancias
        $todasGestiones = Gestion::orderBy('anio', 'desc')->get();
        $ganadoFiltrado = null;
        if ($request->filled('mes') || $request->filled('gestion')) {
            $query = Cliente::query();
            if ($request->filled('gestion')) {
                $query->where('gestion_id', $request->gestion);
            }
            $meses = [
                '01' => 'ENERO', '02' => 'FEBRERO', '03' => 'MARZO', '04' => 'ABRIL', '05' => 'MAYO', '06' => 'JUNIO',
                '07' => 'JULIO', '08' => 'AGOSTO', '09' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE'
            ];
            if ($request->filled('mes') && isset($meses[$request->mes])) {
                $campoMes = $meses[$request->mes];
                $ganadoFiltrado = $query->sum(DB::raw('COALESCE(' . $campoMes . ',0)'));
            } else {
                // Si no se selecciona mes, sumar todos los meses
                $ganadoFiltrado = $query->sum(DB::raw('COALESCE(ENERO,0)+COALESCE(FEBRERO,0)+COALESCE(MARZO,0)+COALESCE(ABRIL,0)+COALESCE(MAYO,0)+COALESCE(JUNIO,0)+COALESCE(JULIO,0)+COALESCE(AGOSTO,0)+COALESCE(SEPTIEMBRE,0)+COALESCE(OCTUBRE,0)+COALESCE(NOVIEMBRE,0)+COALESCE(DICIEMBRE,0)'));
            }
        }

        return view('gestor.dashboard', compact(
            'totalClientes',
            'totalGestiones',
            'clientesPorMes',
            'topClientes',
            'totalGanado',
            'promedioMensual',
            'ultimosEditados',
            'gestionesRecientes',
            'todasGestiones',
            'ganadoFiltrado'
        ));
    }
}
