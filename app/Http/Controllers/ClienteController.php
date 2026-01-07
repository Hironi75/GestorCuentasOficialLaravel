<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

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
            'Correo_Electronico' => 'required|string',
            'Password' => 'required|string',
            'nombre' => 'required|string',
            'celular' => 'nullable|string',
            'Fecha_Inicio' => 'required|date',
            'Fecha_Fin' => 'nullable|date',
            'Concepto' => 'required|string',
            'SaldoPagar' => 'required|numeric',
            'AbonoDeuda' => 'nullable|numeric',
            'TotalPagar' => 'required|numeric',
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

    // Obtener todos los clientes en formato JSON
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Cliente::all();
        }
        return view('gestor.index');
    }

    // Guardar un nuevo cliente desde AJAX
    public function store(Request $request)
    {
        $validated = $request->validate($this->getValidationRules());
        
        $cliente = new Cliente($validated);
        $this->asignarMeses($cliente, $request);
        $cliente->save();

        return response()->json($cliente, 201);
    }

    // Mostrar un cliente específico
    public function show($id)
    {
        return response()->json(Cliente::findOrFail($id));
    }

    // Actualizar un cliente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $validated = $request->validate($this->getValidationRules(true));

        $cliente->fill($validated);
        $this->asignarMeses($cliente, $request);
        $cliente->save();

        return response()->json($cliente);
    }

    // Eliminar un cliente
    public function destroy($id)
    {
        Cliente::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
