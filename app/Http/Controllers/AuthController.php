<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLogin()
    {
        // Si ya está logueado, redirigir al gestor
        if (Session::has('usuario_id')) {
            return redirect('/gestor');
        }
        
        return view('login.index');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $request->validate([
                'usuario' => 'required|string',
                'password' => 'required|string',
            ]);

            $usuario = Usuario::where('usuario', $request->usuario)->first();

            if (!$usuario) {
                return back()->with('error', 'Usuario no encontrado');
            }

            // Verificar contraseña (simple comparación o usar Hash)
            if ($usuario->contrasenia !== $request->password) {
                return back()->with('error', 'Contraseña incorrecta');
            }

            // Crear sesión
            Session::put('usuario_id', $usuario->id);
            Session::put('usuario_nombre', $usuario->usuario);
            
            DB::commit();

            return redirect('/gestor')->with('success', '¡Bienvenido!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al iniciar sesión: ' . $e->getMessage());
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout()
    {
        Session::flush();
        return redirect('/')->with('success', 'Sesión cerrada correctamente');
    }

    /**
     * Verificar si está autenticado (para AJAX)
     */
    public function check()
    {
        return response()->json([
            'authenticated' => Session::has('usuario_id'),
            'usuario' => Session::get('usuario_nombre')
        ]);
    }
}
