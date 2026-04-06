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
        $filters = $this->extractFiltersFromRequest($request);
        $gestionActiva = cache()->remember('gestion_activa', 60, function () {
            return Gestion::activa();
        });

        // Construir y ejecutar query
        $query = $this->buildClientQuery($gestionActiva);
        $query = $this->applyFiltersToQuery($query, $filters, $gestionActiva);

        // Paginar clientes
        $clientes = $query->paginate($filters['per_page'])
            ->appends($filters);

        return view('gestor.index', compact('clientes', 'filters'));
    }

    /**
     * Extrae y valida los filtros desde la request
     */
    private function extractFiltersFromRequest(Request $request): array
    {
        $perPage = (int) $request->get('per_page', 25);
        $perPage = in_array($perPage, [25, 50, 100]) ? $perPage : 25;

        return [
            'per_page' => $perPage,
            'filtro_campo' => $request->get('filtro_campo', 'id'),
            'filtro_busqueda' => trim($request->get('filtro_busqueda', '')),
            'filtro_dias' => $request->get('filtro_dias', 'todos'),
            'deudores' => (bool) $request->get('deudores', false),
        ];
    }

    /**
     * Construye la query base de clientes con columnas necesarias
     */
    private function buildClientQuery($gestion)
    {
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

        if ($gestion) {
            $query->where('gestion_id', $gestion->id);
        }

        return $query;
    }

    /**
     * Aplica filtros a la query
     */
    private function applyFiltersToQuery($query, array $filters, $gestion)
    {
        // Filtro de deudores (más de 5 días vencidos)
        if ($filters['deudores']) {
            $query = $this->applyDebtorFilter($query);
        }

        // Filtro de días restantes
        if (!$filters['deudores'] && $filters['filtro_dias'] !== 'todos' && is_numeric($filters['filtro_dias'])) {
            $query = $this->applyDaysFilter($query, (int) $filters['filtro_dias']);
        }

        // Filtro de búsqueda
        if ($filters['filtro_busqueda'] !== '') {
            $query = $this->applySearchFilter($query, $filters['filtro_campo'], $filters['filtro_busqueda']);
        }

        // Ordenar por urgencia
        $query->orderBy('Fecha_Fin', 'asc');

        return $query;
    }

    /**
     * Aplica filtro de deudores (vencimiento > 5 días)
     */
    private function applyDebtorFilter($query)
    {
        $dateLimit = now()->subDays(5)->toDateString();
        return $query->where('Fecha_Fin', '<', $dateLimit);
    }

    /**
     * Aplica filtro de días restantes
     */
    private function applyDaysFilter($query, int $daysFromNow)
    {
        $today = now()->toDateString();
        $dateLimit = now()->addDays($daysFromNow)->toDateString();
        return $query->whereBetween('Fecha_Fin', [$today, $dateLimit]);
    }

    /**
     * Aplica filtro de búsqueda con FULLTEXT o LIKE
     */
    private function applySearchFilter($query, string $field, string $searchTerm)
    {
        $searchTerm = trim($searchTerm);
        $fieldMap = [
            'id' => 'id_cliente',
            'nombre' => 'nombre',
            'correo' => 'Correo_Electronico',
        ];

        $dbField = $fieldMap[$field] ?? 'id_cliente';

        // Usar FULLTEXT para búsquedas largas (> 2 caracteres), LIKE para cortas
        if (strlen($searchTerm) > 2) {
            return $query->whereRaw("MATCH($dbField) AGAINST(? IN BOOLEAN MODE)", [$searchTerm . '*']);
        }

        return $query->where($dbField, 'like', '%' . $searchTerm . '%');
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
