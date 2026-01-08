<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\GestionController;

Route::get('/', function () {
    return view('login.index');
});

// Rutas resource para clientes y usuarios
Route::resource('clientes', ClienteController::class);
Route::resource('usuarios', UsuarioController::class);

// Ruta para mostrar la vista gestor
Route::get('/gestor', function () {
    return view('gestor.index');
});

// Ruta para mostrar la vista cuenta
Route::get('/cuenta', function () {
    return view('gestor.cuenta');
});

// Rutas para el CRUD de gestiones (AJAX)
Route::get('/gestiones', [GestionController::class, 'index']);
Route::post('/gestiones', [GestionController::class, 'store']);
Route::delete('/gestiones/{id}', [GestionController::class, 'destroy']);
Route::post('/gestiones/{id}/activa', [GestionController::class, 'setActiva']);
Route::put('/gestiones/{id}', [GestionController::class, 'update']);
