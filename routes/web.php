<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\AuthController;

// Rutas públicas (sin autenticación)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth.check')->group(function () {
    // Rutas resource para clientes y usuarios
    Route::resource('clientes', ClienteController::class);
    Route::resource('usuarios', UsuarioController::class);

    // Ruta para mostrar la vista gestor
    Route::get('/gestor', function () {
        return view('gestor.index');
    })->name('gestor');

    // Ruta para mostrar la vista cuenta
    Route::get('/cuenta', function () {
        return view('gestor.cuenta');
    })->name('cuenta');

    // Rutas para el CRUD de gestiones (AJAX)
    Route::get('/gestiones', [GestionController::class, 'index']);
    Route::post('/gestiones', [GestionController::class, 'store']);
    Route::delete('/gestiones/{id}', [GestionController::class, 'destroy']);
    Route::post('/gestiones/{id}/activa', [GestionController::class, 'setActiva']);
    Route::put('/gestiones/{id}', [GestionController::class, 'update']);
});
