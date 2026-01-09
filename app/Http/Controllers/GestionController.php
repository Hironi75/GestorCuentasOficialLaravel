<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GestionController extends Controller
{
    // Obtener todas las gestiones
    public function index()
    {
        return response()->json(Gestion::orderBy('anio', 'desc')->get());
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
