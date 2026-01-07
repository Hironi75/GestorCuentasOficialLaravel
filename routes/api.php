<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\GestionController;

Route::apiResource('clientes', ClienteController::class);

// Rutas de gestiones
Route::get('gestiones', [GestionController::class, 'index']);
Route::post('gestiones', [GestionController::class, 'store']);
Route::get('gestiones/activa', [GestionController::class, 'activa']);
Route::put('gestiones/{id}/activar', [GestionController::class, 'setActiva']);
Route::delete('gestiones/{id}', [GestionController::class, 'destroy']);
