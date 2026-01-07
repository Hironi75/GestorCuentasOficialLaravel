
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;

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
