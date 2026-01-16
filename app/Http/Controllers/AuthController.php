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
            $request->validate([
                'usuario' => 'required|string',
                'password' => 'required|string',
            ]);

            $usuario = Usuario::where('usuario', $request->usuario)->first();

            if (!$usuario) {
                return back()->with('error', 'Usuario no encontrado');
            }

            // Verificar contraseña con Hash::check
            if (!Hash::check($request->password, $usuario->contrasenia)) {
                return back()->with('error', 'Contraseña incorrecta');
            }

            // Crear sesión
            Session::put('usuario_id', $usuario->id);
            Session::put('usuario_nombre', $usuario->usuario);

            return redirect('/gestor')->with('success', '¡Bienvenido!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al iniciar sesión: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegister()
    {
        // Si ya está logueado, redirigir al gestor
        if (Session::has('usuario_id')) {
            return redirect('/gestor');
        }

        return view('login.register');
    }

    /**
     * Procesar registro de nuevo usuario
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'usuario' => 'required|string|min:3|max:50|unique:usuarios,usuario',
                'password' => 'required|string|min:6|confirmed',
            ], [
                'usuario.required' => 'El usuario es requerido',
                'usuario.min' => 'El usuario debe tener al menos 3 caracteres',
                'usuario.unique' => 'Este usuario ya existe',
                'password.required' => 'La contraseña es requerida',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres',
                'password.confirmed' => 'Las contraseñas no coinciden',
            ]);

            DB::beginTransaction();

            // Generar ID único
            $id = (string) \Illuminate\Support\Str::uuid();

            // Crear usuario con contraseña hasheada
            Usuario::create([
                'id' => $id,
                'usuario' => $request->usuario,
                'contrasenia' => Hash::make($request->password), // Hash de la contraseña
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', '¡Usuario creado exitosamente! Ahora puedes iniciar sesión.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear usuario: ' . $e->getMessage())->withInput();
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
