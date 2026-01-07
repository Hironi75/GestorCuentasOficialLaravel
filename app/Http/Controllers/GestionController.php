<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use Illuminate\Http\Request;

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
        $anio = $request->input('anio');
        
        // Verificar si ya existe una gestión con ese año
        if (Gestion::where('anio', $anio)->exists()) {
            return response()->json(['error' => 'Ya existe una gestión para el año ' . $anio], 422);
        }

        $gestion = Gestion::create([
            'anio' => $anio,
            'nombre' => $request->input('nombre') ?? 'Gestión ' . $anio,
            'activa' => false
        ]);

        return response()->json($gestion, 201);
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
        // Desactivar todas
        Gestion::query()->update(['activa' => false]);
        
        // Activar la seleccionada
        $gestion = Gestion::findOrFail($id);
        $gestion->activa = true;
        $gestion->save();

        return response()->json($gestion);
    }

    // Eliminar gestión
    public function destroy($id)
    {
        $gestion = Gestion::findOrFail($id);
        
        // No permitir eliminar si tiene clientes
        if ($gestion->clientes()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar una gestión con clientes asociados'
            ], 400);
        }
        
        $gestion->delete();
        return response()->json(['success' => true]);
    }
}
