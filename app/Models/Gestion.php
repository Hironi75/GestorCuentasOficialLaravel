<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    protected $table = 'gestiones';
    
    protected $fillable = [
        'anio',
        'nombre',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'anio' => 'integer'
    ];

    // Relación con clientes
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'gestion_id');
    }

    // Obtener gestión activa
    public static function activa()
    {
        return self::where('activa', true)->first();
    }
}
