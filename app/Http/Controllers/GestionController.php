<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;

class GestionController extends Controller
{
    // Listar gestiones como JSON (para AJAX) - Con caché de 5 minutos
    public function list()
    {
        $gestiones = cache()->remember('gestiones_list', 300, function () {
            return Gestion::orderBy('anio', 'desc')->get();
        });
        return response()->json($gestiones);
    }

    // Obtener todas las gestiones
    public function index(Request $request)
    {
        // Si es una petición API (AJAX), devolver JSON con todas las gestiones (con caché)
        if ($request->wantsJson() || $request->ajax() || $request->is('api/*') || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $gestiones = cache()->remember('gestiones_list', 300, function () {
                return Gestion::orderBy('anio', 'desc')->get();
            });
            return response()->json($gestiones);
        }

        // Si es una petición web normal, devolver vista con clientes paginados
        // Obtener parámetros de paginación y filtros
        $perPage = (int) $request->get('per_page', 25);
        $filtroCampo = $request->get('filtro_campo', 'id');
        $filtroBusqueda = trim($request->get('filtro_busqueda', ''));
        $filtroDias = $request->get('filtro_dias', 'todos');
        $deudores = (bool) $request->get('deudores', false);

        // Validar per_page
        $perPage = in_array($perPage, [25, 50, 100]) ? $perPage : 25;

        // Obtener gestión activa (con caché de 1 minuto)
        $gestionActiva = cache()->remember('gestion_activa', 60, function () {
            return Gestion::activa();
        });

        // Construir query base - SOLO columnas necesarias para la tabla
        $query = Cliente::select([
            'id_cliente',
            'Correo_Electronico',
            'Password',
            'nombre',
            'Fecha_Inicio',
            'Fecha_Fin',
            'Concepto',
            'SaldoPagar',
            'AbonoDeuda',
            'TotalPagar',
            'gestion_id'
        ]);

        // Filtrar por gestión activa primero (usa índice)
        if ($gestionActiva) {
            $query->where('gestion_id', $gestionActiva->id);
        }

        // Aplicar filtro de deudores (más de 5 días vencidos) - antes de búsqueda
        if ($deudores) {
            $fechaLimite = now()->subDays(5)->toDateString();
            $query->where('Fecha_Fin', '<', $fechaLimite);
        }

        // Aplicar filtro de días restantes
        if (!$deudores && $filtroDias !== 'todos' && is_numeric($filtroDias)) {
            $hoy = now()->toDateString();
            $fechaLimite = now()->addDays((int)$filtroDias)->toDateString();
            $query->whereBetween('Fecha_Fin', [$hoy, $fechaLimite]);
        }

        // Aplicar filtro de búsqueda - Usar FULLTEXT para búsquedas más rápidas
        if ($filtroBusqueda !== '') {
            $busquedaLimpia = trim($filtroBusqueda);

            // Si la búsqueda tiene más de 2 caracteres, usar FULLTEXT (más rápido)
            // Si es muy corta, usar LIKE tradicional
            if (strlen($busquedaLimpia) > 2) {
                switch ($filtroCampo) {
                    case 'id':
                        $query->whereRaw('MATCH(id_cliente) AGAINST(? IN BOOLEAN MODE)', [$busquedaLimpia . '*']);
                        break;
                    case 'nombre':
                        $query->whereRaw('MATCH(nombre) AGAINST(? IN BOOLEAN MODE)', [$busquedaLimpia . '*']);
                        break;
                    case 'correo':
                        $query->whereRaw('MATCH(Correo_Electronico) AGAINST(? IN BOOLEAN MODE)', [$busquedaLimpia . '*']);
                        break;
                }
            } else {
                // Para búsquedas cortas, usar LIKE (FULLTEXT requiere mínimo 3-4 caracteres)
                $busqueda = '%' . $busquedaLimpia . '%';
                switch ($filtroCampo) {
                    case 'id':
                        $query->where('id_cliente', 'like', $busqueda);
                        break;
                    case 'nombre':
                        $query->where('nombre', 'like', $busqueda);
                        break;
                    case 'correo':
                        $query->where('Correo_Electronico', 'like', $busqueda);
                        break;
                }
            }
        }

        // Ordenar por Fecha_Fin para mostrar los más urgentes primero
        $query->orderBy('Fecha_Fin', 'asc');

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

            // Limpiar caché de gestiones
            cache()->forget('gestiones_list');
            cache()->forget('gestion_activa');

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

            // Limpiar caché de gestiones
            cache()->forget('gestiones_list');
            cache()->forget('gestion_activa');

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

            // Limpiar caché de gestiones
            cache()->forget('gestiones_list');
            cache()->forget('gestion_activa');

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

            // Limpiar caché de gestiones
            cache()->forget('gestiones_list');
            cache()->forget('gestion_activa');

            return response()->json($gestion);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Error al actualizar gestión'], 500);
        }
    }
}
