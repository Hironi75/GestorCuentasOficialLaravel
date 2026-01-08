<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\ExportarController;

Route::apiResource('clientes', ClienteController::class);

// Rutas de exportación
Route::get('exportar/excel', [ExportarController::class, 'excel']);
Route::get('exportar/pdf', [ExportarController::class, 'pdf']);

// Rutas de gestiones
Route::get('gestiones', [GestionController::class, 'index']);
Route::post('gestiones', [GestionController::class, 'store']);
Route::get('gestiones/activa', [GestionController::class, 'activa']);
Route::put('gestiones/{id}/activar', [GestionController::class, 'setActiva']);
Route::delete('gestiones/{id}', [GestionController::class, 'destroy']);
