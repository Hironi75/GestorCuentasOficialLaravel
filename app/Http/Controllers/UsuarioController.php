<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string|unique:usuarios,id',
            'usuario' => 'required|string',
            'contrasenia' => 'required|string',
        ]);

        $usuario = new \App\Models\Usuario($validated);
        $usuario->save();

        return response()->json(['success' => true, 'usuario' => $usuario], 201);
    }
}
