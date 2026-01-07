<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'id_cliente', 'gestion_id', 'Correo_Electronico', 'Password', 'nombre', 'celular', 'Fecha_Inicio', 'Fecha_Fin', 'Concepto',
        'SaldoPagar', 'AbonoDeuda', 'TotalPagar',
        'ENERO', 'ENERO_CONCEPTO', 'FEBRERO', 'FEBRERO_CONCEPTO', 'MARZO', 'MARZO_CONCEPTO',
        'ABRIL', 'ABRIL_CONCEPTO', 'MAYO', 'MAYO_CONCEPTO', 'JUNIO', 'JUNIO_CONCEPTO',
        'JULIO', 'JULIO_CONCEPTO', 'AGOSTO', 'AGOSTO_CONCEPTO', 'SEPTIEMBRE', 'SEPTIEMBRE_CONCEPTO',
        'OCTUBRE', 'OCTUBRE_CONCEPTO', 'NOVIEMBRE', 'NOVIEMBRE_CONCEPTO', 'DICIEMBRE', 'DICIEMBRE_CONCEPTO'
    ];

    // Relación con gestión
    public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'gestion_id');
    }
}
