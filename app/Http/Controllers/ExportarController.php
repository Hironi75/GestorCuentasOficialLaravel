<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ExportarController extends Controller
{
    // Mapeo de campos a nombres legibles
    private $camposNombres = [
        'id_cliente' => 'ID Cliente',
        'Correo_Electronico' => 'Correo Electrónico',
        'Password' => 'Password',
        'nombre' => 'Nombre',
        'celular' => 'Celular',
        'Fecha_Inicio' => 'Fecha Inicio',
        'Fecha_Fin' => 'Fecha Fin',
        'Concepto' => 'Concepto',
        'SaldoPagar' => 'Saldo Pagar',
        'AbonoDeuda' => 'Abono Deuda',
        'TotalPagar' => 'Total Pagar',
        'ENERO' => 'Enero',
        'ENERO_CONCEPTO' => 'Enero Concepto',
        'FEBRERO' => 'Febrero',
        'FEBRERO_CONCEPTO' => 'Febrero Concepto',
        'MARZO' => 'Marzo',
        'MARZO_CONCEPTO' => 'Marzo Concepto',
        'ABRIL' => 'Abril',
        'ABRIL_CONCEPTO' => 'Abril Concepto',
        'MAYO' => 'Mayo',
        'MAYO_CONCEPTO' => 'Mayo Concepto',
        'JUNIO' => 'Junio',
        'JUNIO_CONCEPTO' => 'Junio Concepto',
        'JULIO' => 'Julio',
        'JULIO_CONCEPTO' => 'Julio Concepto',
        'AGOSTO' => 'Agosto',
        'AGOSTO_CONCEPTO' => 'Agosto Concepto',
        'SEPTIEMBRE' => 'Septiembre',
        'SEPTIEMBRE_CONCEPTO' => 'Septiembre Concepto',
        'OCTUBRE' => 'Octubre',
        'OCTUBRE_CONCEPTO' => 'Octubre Concepto',
        'NOVIEMBRE' => 'Noviembre',
        'NOVIEMBRE_CONCEPTO' => 'Noviembre Concepto',
        'DICIEMBRE' => 'Diciembre',
        'DICIEMBRE_CONCEPTO' => 'Diciembre Concepto',
    ];

    // Orden correcto: cada mes con su concepto
    private $ordenCampos = [
        'id_cliente', 'Correo_Electronico', 'Password', 'nombre', 'celular',
        'Fecha_Inicio', 'Fecha_Fin', 'Concepto', 'SaldoPagar', 'AbonoDeuda', 'TotalPagar',
        'ENERO', 'ENERO_CONCEPTO',
        'FEBRERO', 'FEBRERO_CONCEPTO',
        'MARZO', 'MARZO_CONCEPTO',
        'ABRIL', 'ABRIL_CONCEPTO',
        'MAYO', 'MAYO_CONCEPTO',
        'JUNIO', 'JUNIO_CONCEPTO',
        'JULIO', 'JULIO_CONCEPTO',
        'AGOSTO', 'AGOSTO_CONCEPTO',
        'SEPTIEMBRE', 'SEPTIEMBRE_CONCEPTO',
        'OCTUBRE', 'OCTUBRE_CONCEPTO',
        'NOVIEMBRE', 'NOVIEMBRE_CONCEPTO',
        'DICIEMBRE', 'DICIEMBRE_CONCEPTO',
    ];

    private function ordenarCampos($campos)
    {
        // Ordenar los campos según el orden definido
        $camposOrdenados = [];
        foreach ($this->ordenCampos as $campo) {
            if (in_array($campo, $campos)) {
                $camposOrdenados[] = $campo;
            }
        }
        return $camposOrdenados;
    }

    public function excel(Request $request)
    {
        $gestionId = $request->query('gestion_id');
        $campos = explode(',', $request->query('campos', ''));
        
        // Filtrar campos válidos y ordenarlos
        $camposValidos = array_intersect($campos, array_keys($this->camposNombres));
        $camposOrdenados = $this->ordenarCampos($camposValidos);
        
        if (empty($camposOrdenados)) {
            return response()->json(['error' => 'No se seleccionaron campos válidos'], 400);
        }
        
        $clientes = Cliente::where('gestion_id', $gestionId)->get();
        
        // Generar CSV (compatible con Excel)
        $filename = 'clientes_gestion_' . $gestionId . '_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($clientes, $camposOrdenados) {
            $file = fopen('php://output', 'w');
            
            if ($file === false) {
                return;
            }
            
            // BOM para UTF-8 en Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados
            $encabezados = array_map(fn($campo) => $this->camposNombres[$campo], $camposOrdenados);
            fputcsv($file, $encabezados);
            
            // Datos
            foreach ($clientes as $cliente) {
                $fila = [];
                foreach ($camposOrdenados as $campo) {
                    $fila[] = $cliente->{$campo} ?? '';
                }
                fputcsv($file, $fila);
            }
            
            // NO cerrar php://output - Laravel lo maneja
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function pdf(Request $request)
    {
        $gestionId = $request->query('gestion_id');
        $campos = explode(',', $request->query('campos', ''));
        
        // Filtrar campos válidos y ordenarlos
        $camposValidos = array_intersect($campos, array_keys($this->camposNombres));
        $camposOrdenados = $this->ordenarCampos($camposValidos);
        
        if (empty($camposOrdenados)) {
            return response()->json(['error' => 'No se seleccionaron campos válidos'], 400);
        }
        
        $clientes = Cliente::where('gestion_id', $gestionId)->get();
        
        // Generar HTML para imprimir como PDF desde el navegador
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Exportar Clientes</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #2c3e50; text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 11px; }
                th { background-color: #34495e; color: white; }
                tr:nth-child(even) { background-color: #f2f2f2; }
                .print-btn { 
                    background: #e74c3c; color: white; padding: 10px 20px; 
                    border: none; border-radius: 4px; cursor: pointer; 
                    font-size: 16px; margin-bottom: 20px;
                }
                @media print { .print-btn { display: none; } }
            </style>
        </head>
        <body>
            <button class="print-btn" onclick="window.print()">🖨️ Imprimir / Guardar como PDF</button>
            <h1>Listado de Clientes - Gestión ' . $gestionId . '</h1>
            <p>Fecha de exportación: ' . date('d/m/Y H:i:s') . '</p>
            <p>Total de registros: ' . count($clientes) . '</p>
            <table>
                <thead>
                    <tr>';
        
        // Encabezados
        foreach ($camposOrdenados as $campo) {
            $html .= '<th>' . htmlspecialchars($this->camposNombres[$campo]) . '</th>';
        }
        
        $html .= '
                    </tr>
                </thead>
                <tbody>';
        
        // Datos
        foreach ($clientes as $cliente) {
            $html .= '<tr>';
            foreach ($camposOrdenados as $campo) {
                $valor = $cliente->{$campo} ?? '-';
                $html .= '<td>' . htmlspecialchars($valor) . '</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '
                </tbody>
            </table>
        </body>
        </html>';
        
        return response($html);
    }
}
