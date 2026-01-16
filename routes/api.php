<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\ExportarController;
use App\Http\Controllers\TraspasarController;

// Todas las rutas API requieren autenticación
Route::middleware('auth.check')->group(function () {
    Route::apiResource('clientes', ClienteController::class)->names([
        'index' => 'api.clientes.index',
        'store' => 'api.clientes.store',
        'show' => 'api.clientes.show',
        'update' => 'api.clientes.update',
        'destroy' => 'api.clientes.destroy',
    ]);

    // Rutas de exportación
    Route::get('exportar/excel', [ExportarController::class, 'excel']);
    Route::get('exportar/pdf', [ExportarController::class, 'pdf']);

    // Ruta de traspaso
    Route::post('traspasar', [TraspasarController::class, 'traspasar']);

    // Rutas de gestiones
    Route::get('gestiones', [GestionController::class, 'index']);
    Route::post('gestiones', [GestionController::class, 'store']);
    Route::get('gestiones/activa', [GestionController::class, 'activa']);
    Route::put('gestiones/{id}/activar', [GestionController::class, 'setActiva']);
    Route::delete('gestiones/{id}', [GestionController::class, 'destroy']);
});
