<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TraspasarController extends Controller
{
    /**
     * Traspasar datos de clientes de una gestión a otra
     */
    public function traspasar(Request $request)
    {
        $request->validate([
            'origen_id' => 'required|exists:gestiones,id',
            'destino_id' => 'required|exists:gestiones,id|different:origen_id',
            'campos' => 'required|array|min:1',
        ]);

        $origenId = $request->input('origen_id');
        $destinoId = $request->input('destino_id');
        $campos = $request->input('campos');

        // Obtener clientes de la gestión origen
        $clientesOrigen = Cliente::where('gestion_id', $origenId)->get();

        if ($clientesOrigen->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'La gestión de origen no tiene clientes.'
            ]);
        }

        $actualizados = 0;
        $creados = 0;
        $errores = 0;

        // Campos que se pueden traspasar (excluyendo id y timestamps)
        $camposPermitidos = [
            'Correo_Electronico', 'Password', 'nombre', 'celular', 'Fecha_Inicio', 'Fecha_Fin', 'Concepto',
            'SaldoPagar', 'AbonoDeuda', 'TotalPagar',
            'ENERO', 'ENERO_CONCEPTO', 'FEBRERO', 'FEBRERO_CONCEPTO', 'MARZO', 'MARZO_CONCEPTO',
            'ABRIL', 'ABRIL_CONCEPTO', 'MAYO', 'MAYO_CONCEPTO', 'JUNIO', 'JUNIO_CONCEPTO',
            'JULIO', 'JULIO_CONCEPTO', 'AGOSTO', 'AGOSTO_CONCEPTO', 'SEPTIEMBRE', 'SEPTIEMBRE_CONCEPTO',
            'OCTUBRE', 'OCTUBRE_CONCEPTO', 'NOVIEMBRE', 'NOVIEMBRE_CONCEPTO', 'DICIEMBRE', 'DICIEMBRE_CONCEPTO'
        ];

        DB::beginTransaction();
        
        try {
            foreach ($clientesOrigen as $clienteOrigen) {
                // Buscar si existe un cliente con el mismo id_cliente en la gestión destino
                $clienteDestino = Cliente::where('gestion_id', $destinoId)
                    ->where('id_cliente', $clienteOrigen->id_cliente)
                    ->first();

                // Preparar los datos a traspasar (SOLO los campos seleccionados)
                $datosATraspasar = [];
                foreach ($campos as $campo) {
                    if (in_array($campo, $camposPermitidos)) {
                        $datosATraspasar[$campo] = $clienteOrigen->$campo ?? null;
                    }
                }

                if ($clienteDestino) {
                    // Actualizar cliente existente con solo los campos seleccionados
                    $clienteDestino->update($datosATraspasar);
                    $actualizados++;
                } else {
                    // Verificar si ya existe un cliente con ese id_cliente (en cualquier gestión)
                    $existeId = Cliente::where('id_cliente', $clienteOrigen->id_cliente)->exists();
                    
                    if ($existeId) {
                        // El id_cliente ya existe en otra gestión, generar uno nuevo
                        $nuevoIdCliente = $clienteOrigen->id_cliente . '_' . $destinoId;
                        
                        // Verificar que el nuevo ID tampoco exista
                        $contador = 1;
                        while (Cliente::where('id_cliente', $nuevoIdCliente)->exists()) {
                            $nuevoIdCliente = $clienteOrigen->id_cliente . '_' . $destinoId . '_' . $contador;
                            $contador++;
                        }
                        
                        $idClienteNuevo = $nuevoIdCliente;
                    } else {
                        $idClienteNuevo = $clienteOrigen->id_cliente;
                    }
                    
                    // Crear nuevo cliente con SOLO los campos seleccionados
                    // Los campos requeridos tendrán valores por defecto si no están seleccionados
                    $datosNuevoCliente = [
                        'gestion_id' => $destinoId,
                        'id_cliente' => $idClienteNuevo,
                        // Valores por defecto para campos requeridos
                        'Correo_Electronico' => '',
                        'Password' => '',
                        'nombre' => '',
                        'Fecha_Inicio' => now()->format('Y-m-d'),
                        'Concepto' => '',
                        'SaldoPagar' => 0,
                        'TotalPagar' => 0,
                    ];
                    
                    // Sobrescribir SOLO con los campos seleccionados
                    foreach ($datosATraspasar as $campo => $valor) {
                        $datosNuevoCliente[$campo] = $valor;
                    }
                    
                    Cliente::create($datosNuevoCliente);
                    $creados++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Traspaso completado exitosamente.',
                'actualizados' => $actualizados,
                'creados' => $creados,
                'total' => $clientesOrigen->count()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar el traspaso: ' . $e->getMessage()
            ]);
        }
    }
}
