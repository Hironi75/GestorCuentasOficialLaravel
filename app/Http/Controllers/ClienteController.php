<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    // Meses disponibles (constante para evitar repetición)
    private const MESES = [
        'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO',
        'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'
    ];

    // Reglas de validación base
    private function getValidationRules($isUpdate = false)
    {
        $rules = [
            'gestion_id' => 'nullable|exists:gestiones,id',
            'Correo_Electronico' => 'nullable|string',
            'Password' => 'nullable|string',
            'nombre' => 'nullable|string',
            'celular' => 'nullable|string',
            'Fecha_Inicio' => 'nullable|date',
            'Fecha_Fin' => 'nullable|date',
            'Concepto' => 'nullable|string',
            'SaldoPagar' => 'nullable|numeric',
            'AbonoDeuda' => 'nullable|numeric',
            'TotalPagar' => 'nullable|numeric',
        ];

        if (!$isUpdate) {
            $rules['id_cliente'] = 'required|string|unique:clientes,id_cliente';
        }

        return $rules;
    }

    // Asignar datos de meses al cliente
    private function asignarMeses(Cliente $cliente, Request $request)
    {
        foreach (self::MESES as $mes) {
            $cliente->$mes = $request->$mes;
            $cliente->{$mes . '_CONCEPTO'} = $request->{$mes . '_CONCEPTO'};
        }
    }

    // Obtener todos los clientes en formato JSON (filtrado por gestión)
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $gestionId = $request->query('gestion_id');
            
            if ($gestionId) {
                return Cliente::where('gestion_id', $gestionId)->get();
            }
            
            // Si no se especifica gestión, usar la activa
            $gestionActiva = Gestion::activa();
            if ($gestionActiva) {
                return Cliente::where('gestion_id', $gestionActiva->id)->get();
            }
            
            return Cliente::all();
        }
        return view('gestor.index');
    }

    // Guardar un nuevo cliente desde AJAX
    public function store(Request $request)
    {
        try {
            $validated = $request->validate($this->getValidationRules());
            
            // Asignar gestión activa si no se especifica
            if (!isset($validated['gestion_id']) || !$validated['gestion_id']) {
                $gestionActiva = Gestion::activa();
                $validated['gestion_id'] = $gestionActiva ? $gestionActiva->id : null;
            }
            
            \DB::beginTransaction();
            
            $cliente = new Cliente($validated);
            $this->asignarMeses($cliente, $request);
            $cliente->save();
            
            \DB::commit();

            return response()->json($cliente, 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Error al guardar cliente: ' . $e->getMessage()], 500);
        }
    }

    // Mostrar un cliente específico
    public function show($id)
    {
        return response()->json(Cliente::findOrFail($id));
    }

    // Actualizar un cliente
    public function update(Request $request, $id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $validated = $request->validate($this->getValidationRules(true));

            \DB::beginTransaction();
            
            $cliente->fill($validated);
            $this->asignarMeses($cliente, $request);
            $cliente->save();
            
            \DB::commit();

            return response()->json($cliente);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Error al actualizar cliente: ' . $e->getMessage()], 500);
        }
    }

    // Eliminar un cliente
    public function destroy($id)
    {
        try {
            \DB::beginTransaction();
            
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();
            
            \DB::commit();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Error al eliminar cliente'], 500);
        }
    }
}
