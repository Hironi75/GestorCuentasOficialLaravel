<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    // Meses disponibles para datos de facturación
    private const MONTHS = [
        'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO',
        'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'
    ];

    // Reglas de validación base para cliente
    private const BASE_VALIDATION_RULES = [
        'gestion_id' => 'nullable|exists:gestiones,id',
        'Correo_Electronico' => 'nullable|email|max:255',
        'Password' => 'nullable|string|min:6|max:255',
        'nombre' => 'nullable|string|max:255',
        'celular' => 'nullable|string|max:50',
        'Fecha_Inicio' => 'nullable|date',
        'Fecha_Fin' => 'nullable|date|after_or_equal:Fecha_Inicio',
        'Concepto' => 'nullable|string|max:500',
        'SaldoPagar' => 'nullable|numeric|min:0',
        'AbonoDeuda' => 'nullable|numeric|min:0',
        'TotalPagar' => 'nullable|numeric|min:0',
    ];

    /**
     * Obtiene las reglas de validación según si es creación o actualización
     */
    private function getValidationRules(bool $isUpdate = false): array
    {
        $rules = self::BASE_VALIDATION_RULES;

        if (!$isUpdate) {
            $rules['id_cliente'] = 'required|string|unique:clientes,id_cliente';
        }

        return $rules;
    }

    /**
     * Asigna datos mensuales (importe y concepto por mes) al cliente
     */
    private function assignMonthlyData(Cliente $cliente, Request $request): void
    {
        foreach (self::MONTHS as $month) {
            $cliente->{$month} = $request->input($month);
            $cliente->{$month . '_CONCEPTO'} = $request->input($month . '_CONCEPTO');
        }
    }

    /**
     * Obtiene la gestión activa o la especificada en la request
     */
    private function getActiveGestion(?int $gestionId = null): ?Gestion
    {
        if ($gestionId) {
            return Gestion::find($gestionId);
        }

        return cache()->remember('gestion_activa', 60, function () {
            return Gestion::activa();
        });
    }

    // Obtener todos los clientes en formato JSON (filtrado por gestión)
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $gestionId = $request->query('gestion_id');
            $gestion = $this->getActiveGestion($gestionId);

            if ($gestion) {
                return Cliente::where('gestion_id', $gestion->id)->get();
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
            if (empty($validated['gestion_id'])) {
                $gestion = $this->getActiveGestion();
                $validated['gestion_id'] = $gestion?->id;
            }

            DB::beginTransaction();

            $cliente = new Cliente($validated);
            $this->assignMonthlyData($cliente, $request);
            $cliente->save();

            DB::commit();

            return response()->json($cliente, 201);
        } catch (\Exception $e) {
            DB::rollBack();
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

            DB::beginTransaction();

            $cliente->fill($validated);
            $this->assignMonthlyData($cliente, $request);
            $cliente->save();

            DB::commit();

            return response()->json($cliente);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar cliente: ' . $e->getMessage()], 500);
        }
    }

    // Eliminar un cliente
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $cliente = Cliente::findOrFail($id);
            $cliente->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar cliente'], 500);
        }
    }
}
