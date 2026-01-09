<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!Session::has('usuario_id')) {
            // Si es una petición AJAX, devolver JSON
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'No autenticado',
                    'redirect' => '/'
                ], 401);
            }
            
            // Si es una petición normal, redirigir al login
            return redirect('/')->with('error', 'Debes iniciar sesión primero');
        }

        return $next($request);
    }
}
