<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|string|unique:usuarios,id',
                'usuario' => 'required|string',
                'contrasenia' => 'required|string',
            ]);

            \DB::beginTransaction();
            
            $usuario = new \App\Models\Usuario($validated);
            $usuario->save();
            
            \DB::commit();

            return response()->json(['success' => true, 'usuario' => $usuario], 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Error al crear usuario'], 500);
        }
    }
}
