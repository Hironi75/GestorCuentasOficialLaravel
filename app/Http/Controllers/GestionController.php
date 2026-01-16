<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;

class GestionController extends Controller
{
    // Obtener todas las gestiones
    public function index(Request $request)
    {
        // Si es una petición API (AJAX), devolver JSON con todas las gestiones
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(Gestion::orderBy('anio', 'desc')->get());
        }

        // Si es una petición web normal, devolver vista con clientes paginados
        // Obtener parámetros de paginación y filtros
        $perPage = $request->get('per_page', 25);
        $filtroCampo = $request->get('filtro_campo', 'id');
        $filtroBusqueda = $request->get('filtro_busqueda', '');
        $filtroDias = $request->get('filtro_dias', 'todos');
        $deudores = $request->get('deudores', false);

        // Validar per_page
        if (!in_array($perPage, [25, 50, 100])) {
            $perPage = 25;
        }

        // Obtener gestión activa
        $gestionActiva = Gestion::activa();

        // Construir query base
        $query = Cliente::query();

        if ($gestionActiva) {
            $query->where('gestion_id', $gestionActiva->id);
        }

        // Aplicar filtro de búsqueda
        if (!empty($filtroBusqueda)) {
            switch ($filtroCampo) {
                case 'id':
                    $query->where('id_cliente', 'like', '%' . $filtroBusqueda . '%');
                    break;
                case 'nombre':
                    $query->where('nombre', 'like', '%' . $filtroBusqueda . '%');
                    break;
                case 'correo':
                    $query->where('Correo_Electronico', 'like', '%' . $filtroBusqueda . '%');
                    break;
            }
        }

        // Aplicar filtro de días restantes
        if ($filtroDias !== 'todos' && is_numeric($filtroDias)) {
            $hoy = now()->startOfDay();
            $diasLimite = (int)$filtroDias;

            $query->whereNotNull('Fecha_Fin')
                  ->whereRaw('DATEDIFF(Fecha_Fin, ?) BETWEEN 0 AND ?', [$hoy->toDateString(), $diasLimite]);
        }

        // Aplicar filtro de deudores (más de 5 días vencidos)
        if ($deudores) {
            $hoy = now()->startOfDay();
            $query->whereNotNull('Fecha_Fin')
                  ->whereRaw('DATEDIFF(Fecha_Fin, ?) < -5', [$hoy->toDateString()]);
        }

        // Paginar clientes
        $clientes = $query->paginate($perPage)
            ->appends([
                'per_page' => $perPage,
                'filtro_campo' => $filtroCampo,
                'filtro_busqueda' => $filtroBusqueda,
                'filtro_dias' => $filtroDias,
                'deudores' => $deudores
            ]);

        return view('gestor.index', compact('clientes', 'perPage', 'filtroCampo', 'filtroBusqueda', 'filtroDias', 'deudores'));
    }

    // Crear nueva gestión
    public function store(Request $request)
    {
        try {
            $anio = $request->input('anio');

            // Verificar si ya existe una gestión con ese año
            if (Gestion::where('anio', $anio)->exists()) {
                return response()->json(['error' => 'Ya existe una gestión para el año ' . $anio], 422);
            }

            \DB::beginTransaction();

            $gestion = Gestion::create([
                'anio' => $anio,
                'nombre' => $request->input('nombre') ?? 'Gestión ' . $anio,
                'activa' => false
            ]);

            \DB::commit();

            return response()->json($gestion, 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Error al crear gestión'], 500);
        }
    }

    // Obtener gestión activa
    public function activa()
    {
        $gestion = Gestion::activa();
        return response()->json($gestion);
    }

    // Cambiar gestión activa
    public function setActiva($id)
    {
        try {
            \DB::beginTransaction();

            // Desactivar todas
            Gestion::query()->update(['activa' => false]);

            // Activar la seleccionada
            $gestion = Gestion::findOrFail($id);
            $gestion->activa = true;
            $gestion->save();

            \DB::commit();

            return response()->json($gestion);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Error al cambiar gestión activa'], 500);
        }
    }

    // Eliminar gestión
    public function destroy($id)
    {
        try {
            $gestion = Gestion::findOrFail($id);

            // No permitir eliminar si tiene clientes
            if ($gestion->clientes()->count() > 0) {
                return response()->json([
                    'message' => 'No se puede eliminar una gestión con clientes asociados'
                ], 400);
            }

            \DB::beginTransaction();

            $gestion->delete();

            \DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Error al eliminar gestión'], 500);
        }
    }

    // Editar gestión
    public function update(Request $request, $id)
    {
        try {
            $gestion = Gestion::findOrFail($id);
            $anio = $request->input('anio');

            // Verificar si ya existe otra gestión con ese año
            if (Gestion::where('anio', $anio)->where('id', '!=', $id)->exists()) {
                return response()->json(['error' => 'Ya existe una gestión para el año ' . $anio], 422);
            }

            \DB::beginTransaction();

            $gestion->anio = $anio;
            $gestion->nombre = $request->input('nombre') ?? ('Gestión ' . $anio);
            $gestion->save();

            \DB::commit();

            return response()->json($gestion);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Error al actualizar gestión'], 500);
        }
    }
}
