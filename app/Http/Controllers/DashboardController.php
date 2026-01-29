<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Gestion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hoyInicio = Carbon::today();
        $hoyFin = Carbon::tomorrow();

        // Obtener gestión activa (cacheada como en GestionController)
        $gestionActiva = cache()->remember('gestion_activa', 60, function () {
            return Gestion::activa();
        });

        // Contadores filtrados por gestión activa cuando exista
        $totalClientesQuery = Cliente::query();
        if ($gestionActiva) {
            $totalClientesQuery->where('gestion_id', $gestionActiva->id);
        }
        $totalClientes = $totalClientesQuery->count();

        $totalGestiones = Gestion::count();

        // clientes agregados hoy (created_at)
        $clientesAgregadosHoyQuery = Cliente::whereBetween('created_at', [$hoyInicio, $hoyFin]);
        $clientesEditadosHoyQuery = Cliente::whereBetween('updated_at', [$hoyInicio, $hoyFin]);
        if ($gestionActiva) {
            $clientesAgregadosHoyQuery->where('gestion_id', $gestionActiva->id);
            $clientesEditadosHoyQuery->where('gestion_id', $gestionActiva->id);
        }
        $clientesAgregadosHoy = $clientesAgregadosHoyQuery->count();
        $clientesEditadosHoy = $clientesEditadosHoyQuery->count();

        // Si tus importes están guardados en columnas mensuales (ENERO, FEBRERO, ...), sumarlas por mes
        $nombresMeses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $mesColumnNames = [
            1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
            5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
            9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
        ];

        $year = Carbon::now()->year;
        $gananciasPorMes = [];
        $sumMeses = 0.0; // acumulador para ganancias totales consistentes

        for ($m = 1; $m <= 12; $m++) {
            $col = $mesColumnNames[$m];

            // usar sum directo sobre la columna filtrando por gestión activa (devuelve 0 si no hay filas)
            $total = (float) Cliente::when($gestionActiva, function ($q) use ($gestionActiva) {
                $q->where('gestion_id', $gestionActiva->id);
            })->sum($col);

            $sumMeses += $total;

            $gananciasPorMes[] = (object)[
                'mes' => $year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT),
                'nombre' => $nombresMeses[$m],
                'total' => $total,
            ];
        }

        // Ganancias totales ahora derivadas de la suma de meses (consistente con la tabla)
        $gananciasTotales = $sumMeses;

        return view('gestor.dashboard', compact(
            'totalClientes',
            'totalGestiones',
            'clientesAgregadosHoy',
            'clientesEditadosHoy',
            'gananciasTotales',
            'gananciasPorMes'
        ));

    }

    // Endpoint AJAX que devuelve los datos del dashboard para una gestión específica (o la activa si no se proporciona)
    public function data(Request $request, $gestionId = null)
    {
        $hoyInicio = Carbon::today();
        $hoyFin = Carbon::tomorrow();

        // Obtener gestión: por id si se envía, sino la activa
        $gestion = null;
        if ($gestionId) {
            $gestion = Gestion::find($gestionId);
        }
        if (!$gestion) {
            $gestion = cache()->remember('gestion_activa', 60, function () {
                return Gestion::activa();
            });
        }

        $totalClientesQuery = Cliente::query();
        if ($gestion) {
            $totalClientesQuery->where('gestion_id', $gestion->id);
        }
        $totalClientes = $totalClientesQuery->count();

        $totalGestiones = Gestion::count();

        $clientesAgregadosHoyQuery = Cliente::whereBetween('created_at', [$hoyInicio, $hoyFin]);
        $clientesEditadosHoyQuery = Cliente::whereBetween('updated_at', [$hoyInicio, $hoyFin]);
        if ($gestion) {
            $clientesAgregadosHoyQuery->where('gestion_id', $gestion->id);
            $clientesEditadosHoyQuery->where('gestion_id', $gestion->id);
        }
        $clientesAgregadosHoy = $clientesAgregadosHoyQuery->count();
        $clientesEditadosHoy = $clientesEditadosHoyQuery->count();

        $nombresMeses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $mesColumnNames = [
            1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
            5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
            9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
        ];

        $year = Carbon::now()->year;
        $gananciasPorMes = [];
        $sumMeses = 0.0;

        for ($m = 1; $m <= 12; $m++) {
            $col = $mesColumnNames[$m];
            $total = (float) Cliente::when($gestion, function ($q) use ($gestion) {
                $q->where('gestion_id', $gestion->id);
            })->sum($col);

            $sumMeses += $total;

            $gananciasPorMes[] = [
                'mes' => $year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT),
                'nombre' => $nombresMeses[$m],
                'total' => $total,
            ];
        }

        $gananciasTotales = $sumMeses;

        return response()->json([
            'totalClientes' => $totalClientes,
            'totalGestiones' => $totalGestiones,
            'clientesAgregadosHoy' => $clientesAgregadosHoy,
            'clientesEditadosHoy' => $clientesEditadosHoy,
            'gananciasTotales' => $gananciasTotales,
            'gananciasPorMes' => $gananciasPorMes,
            'gestion' => $gestion ? ['id' => $gestion->id, 'nombre' => $gestion->nombre] : null
        ]);
    }
}
