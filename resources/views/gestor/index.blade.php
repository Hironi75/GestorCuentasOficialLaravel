<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Cuentas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/gestor.css') }}">
</head>

<body>
    <div class="gestor-main-container">
        @include('gestor.header')
        <main class="gestor-main">
            <section class="gestor-section">
                <h2 style="margin:0; color:#2d3436; font-size:1.5rem;">Listado de Cuentas <span id="gestion-actual-label" style="font-size:0.9rem; color:#7f8c8d;"></span></h2>
                <!-- Controles de Paginación -->
                <div class="pagination-container">
                    <!-- Selector de registros por página -->
                    <div style="display:flex; align-items:center; gap:10px;">
                        <label for="per-page-select" class="pagination-select-label">Mostrar:</label>
                        <select id="per-page-select" onchange="window.location.href='{{ route('gestor') }}?per_page=' + this.value" class="pagination-select">
                            <option value="25" {{ (isset($perPage) && $perPage == 25) || !isset($perPage) ? 'selected' : '' }}>25</option>
                            <option value="50" {{ isset($perPage) && $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ isset($perPage) && $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="pagination-info">
                            Mostrando {{ $clientes->firstItem() ?? 0 }} - {{ $clientes->lastItem() ?? 0 }} de {{ $clientes->total() }} registros
                        </span>
                    </div>
                    <!-- Enlaces de paginación -->
                    <div class="pagination-links">
                        {{-- Botón Anterior --}}
                        @if ($clientes->onFirstPage())
                            <span class="pagination-btn disabled">‹ Anterior</span>
                        @else
                            <a href="{{ $clientes->previousPageUrl() }}" class="pagination-btn">‹ Anterior</a>
                        @endif
                        {{-- Input de página actual --}}
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span class="pagination-label">Página</span>
                            <input
                                type="number"
                                id="page-input"
                                value="{{ $clientes->currentPage() }}"
                                min="1"
                                max="{{ $clientes->lastPage() }}"
                                class="pagination-page-input"
                                onkeypress="if(event.key === 'Enter') {
                                    let page = parseInt(this.value);
                                    let maxPage = {{ $clientes->lastPage() }};
                                    if(page >= 1 && page <= maxPage) {
                                        window.location.href = '{{ route('gestor') }}?per_page={{ $perPage ?? 25 }}&page=' + page;
                                    } else {
                                        alert('Página debe estar entre 1 y ' + maxPage);
                                        this.value = {{ $clientes->currentPage() }};
                                    }
                                }"
                            >
                            <span class="pagination-info">de {{ $clientes->lastPage() }}</span>
                        </div>
                        {{-- Botón Siguiente --}}
                        @if ($clientes->hasMorePages())
                            <a href="{{ $clientes->nextPageUrl() }}" class="pagination-btn">Siguiente ›</a>
                        @else
                            <span class="pagination-btn disabled">Siguiente ›</span>
                        @endif
                        {{-- Botón Última Página --}}
                        @if ($clientes->currentPage() < $clientes->lastPage())
                            <a href="{{ $clientes->url($clientes->lastPage()) }}" class="pagination-btn">
                                Última »
                            </a>
                        @else
                            <span class="pagination-btn disabled">Última »</span>
                        @endif
                    </div>
                </div>
                &nbsp;
                <div class="gestor-toolbar">
                    <form method="GET" action="{{ route('gestor') }}" id="form-filtros" style="display:flex; gap:8px; align-items:center; width:100%;">
                        <!-- Mantener per_page en el formulario -->
                        <input type="hidden" name="per_page" value="{{ $perPage ?? 25 }}">

                        <select name="filtro_campo" id="filtro-campo" onchange="this.form.submit()">
                            <option value="id" {{ (isset($filtroCampo) && $filtroCampo == 'id') || !isset($filtroCampo) ? 'selected' : '' }}>ID</option>
                            <option value="nombre" {{ isset($filtroCampo) && $filtroCampo == 'nombre' ? 'selected' : '' }}>Nombre</option>
                            <option value="correo" {{ isset($filtroCampo) && $filtroCampo == 'correo' ? 'selected' : '' }}>Correo electrónico</option>
                        </select>

                        <input name="filtro_busqueda" id="filtro-cuentas" type="text" placeholder="Buscar..." value="{{ $filtroBusqueda ?? '' }}">

                        <select name="filtro_dias" id="filtro-dias" onchange="this.form.submit()">
                            <option value="todos" {{ (isset($filtroDias) && $filtroDias == 'todos') || !isset($filtroDias) ? 'selected' : '' }}>Todos</option>
                            <option value="3" {{ isset($filtroDias) && $filtroDias == '3' ? 'selected' : '' }}>3 días o menos</option>
                            <option value="5" {{ isset($filtroDias) && $filtroDias == '5' ? 'selected' : '' }}>5 días o menos</option>
                            <option value="6" {{ isset($filtroDias) && $filtroDias == '6' ? 'selected' : '' }}>6 días o menos</option>
                            <option value="8" {{ isset($filtroDias) && $filtroDias == '8' ? 'selected' : '' }}>8 días o menos</option>
                        </select>

                        <div style="margin-left:auto; display:flex; gap:8px;">
                            <button type="button" id="btn-agregar">Agregar</button>
                            <a href="{{ route('gestor') }}?per_page={{ $perPage ?? 25 }}&deudores=1"
                               class="deudores-btn"
                               style="background: #e74c3c; color: #fff; padding: 10px 22px; border-radius: 4px; text-decoration: none; display: inline-block; border: none; cursor: pointer; font-size: 1rem;">
                                {{ isset($deudores) && $deudores ? 'Ver Todos' : 'Deudores' }}
                            </a>
                        </div>
                    </form>
                </div>
                <div class="gestor-table-container">
                    <table id="tabla-cuentas-table" class="gestor-table">
                        <thead>
                            <tr>
                                <th>ID Cliente</th>
                                <th>Correo Electrónico</th>
                                <th>Password</th>
                                <th>Nombre</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Concepto</th>
                                <th class="columna-compacta">Saldo Pagar</th>
                                <th class="columna-compacta" style="border:1px solid #b2bec3; padding:8px;" >Abono Deuda</th>
                                <th class="columna-compacta" style="border:1px solid #b2bec3; padding:8px;">Total Pagar</th>
                                <th style="border:1px solid #b2bec3; padding:8px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-cuentas">
                            <!-- Filas dinámicas -->
                            @if($clientes->count() > 0)
                                @foreach($clientes as $cliente)
                                    @php
                                        $estilo = '';
                                        $simboloDeuda = '';

                                        if ($cliente->Fecha_Fin) {
                                            $hoy = \Carbon\Carbon::now()->startOfDay();
                                            $fechaFin = \Carbon\Carbon::parse($cliente->Fecha_Fin);
                                            $diasRestantes = $hoy->diffInDays($fechaFin, false);

                                            // Más de 30 días vencido
                                            if ($diasRestantes < -30) {
                                                $estilo = 'background-color: #f5c6cb; color: #721c24;';
                                                $simboloDeuda = ' 🚨';
                                            }
                                            // Vencido (menos de 0 días)
                                            elseif ($diasRestantes < 0) {
                                                $estilo = 'background-color: #f8d7da; color: #721c24;';
                                            }
                                            // 3 días o menos (urgente)
                                            elseif ($diasRestantes <= 3) {
                                                $estilo = 'background-color: #f8d7da; color: #721c24;';
                                            }
                                            // 5 días o menos (próximo)
                                            elseif ($diasRestantes <= 5) {
                                                $estilo = 'background-color: #ffe5cc; color: #856404;';
                                            }
                                            // 8 días o menos (atención)
                                            elseif ($diasRestantes <= 8) {
                                                $estilo = 'background-color: #fff9db; color: #856404;';
                                            }
                                        }
                                    @endphp
                                    <tr style="{{ $estilo }}">
                                        <td class="text-center">{{ $cliente->id_cliente }}{{ $simboloDeuda }}</td>
                                        <td>{{ $cliente->Correo_Electronico }}</td>
                                        <td>{{ $cliente->Password }}</td>
                                        <td>{{ $cliente->nombre }}</td>
                                        <td class="text-center">{{ $cliente->Fecha_Inicio }}</td>
                                        <td class="text-center">{{ $cliente->Fecha_Fin }}</td>
                                        <td>{{ $cliente->Concepto }}</td>
                                        <td class="text-center">{{ $cliente->SaldoPagar }}</td>
                                        <td class="text-center">{{ $cliente->AbonoDeuda }}</td>
                                        <td class="text-center">{{ $cliente->TotalPagar }}</td>
                                        <td class="acciones-btns">
                                            <!-- Botón Ver -->
                                            <button title="Ver" class="btn-ver" data-id="{{ $cliente->id_cliente }}">
                                                <span style="font-size:1.4em;line-height:1;">&#128065;</span>
                                            </button>
                                            <!-- Botón Editar -->
                                            <button title="Editar" class="btn-editar" data-id="{{ $cliente->id_cliente }}">
                                                <span style="font-size:1.4em;line-height:1;">&#9998;</span>
                                            </button>
                                            <!-- Botón Eliminar -->
                                            <button title="Eliminar" class="btn-eliminar" data-id="{{ $cliente->id_cliente }}">
                                                <span style="font-size:1.4em;line-height:1;">&#128465;</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" style="text-align:center; padding:20px; color:#7f8c8d; font-style:italic;">
                                        No se encontraron coincidencias
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>


            </section>
            <!-- Modal de confirmación eliminar -->
            <div id="modal-confirmar" style="display:none;">
                <div class="modal-content modal-confirmar-content">
                    <div class="confirmar-icon">🗑️</div>
                    <h3 id="confirmar-title">Confirmar Eliminación</h3>
                    <p id="confirmar-message">¿Está seguro que desea eliminar este cliente?</p>
                    <div class="modal-actions" style="justify-content:center;">
                        <button type="button" id="btn-confirmar-eliminar">Sí, eliminar</button>
                        <button type="button" id="btn-cancelar-eliminar">Cancelar</button>
                    </div>
                </div>
            </div>
            <!-- Modal de advertencia -->
            <div id="modal-advertencia" style="display:none;">
                <div class="modal-content modal-advertencia-content">
                    <div class="advertencia-icon">⚠️</div>
                    <h3 id="advertencia-title">Advertencia</h3>
                    <p id="advertencia-message"></p>
                    <div class="modal-actions">
                        <button type="button" id="btn-cerrar-advertencia">Aceptar</button>
                    </div>
                </div>
            </div>
            <!-- Modal para ver cuenta -->
            <div id="modal-ver" style="display:none;">
                <div class="modal-content">
                    <div id="modal-ver-body"></div>
                    <div class="modal-actions">
                        <button type="button" id="btn-cerrar-ver">Cerrar</button>
                    </div>
                </div>
            </div>
            <!-- Modal para agregar/editar -->
            <div id="modal-crud" style="display:none;">
                <div class="modal-content">
                    <form id="form-crud">
                        <h3 id="modal-title">Agregar Cuenta</h3>
                        <label>ID Cliente
                                <input type="text" id="crud-id_cliente" required>
                        </label>
                            <label>Correo Electrónico
                                <input type="email" id="crud-Correo_Electronico">
                            </label>
                            <label>Password
                                <input type="text" id="crud-Password">
                            </label>
                            <label>Nombre
                                <input type="text" id="crud-nombre">
                            </label>
                            <label>Concepto
                                <input type="text" id="crud-Concepto">
                            </label>
                            <label>Fecha Inicio
                                <input type="date" id="crud-Fecha_Inicio">
                            </label>
                            <label>Fecha Fin
                                <input type="date" id="crud-Fecha_Fin">
                            </label>
                        <label>Saldo Pagar (Bs)
                            <input type="number" step="0.01" id="crud-SaldoPagar" required readonly>
                        </label>
                        <label>Abono Deuda (Bs)
                            <input type="number" step="0.01" id="crud-AbonoDeuda">
                        </label>
                        <label>Total Pagar (Bs)
                            <input type="number" step="0.01" id="crud-TotalPagar" required readonly>
                        </label>
                        <div style="grid-column: span 2; margin-top:18px;">
                            <h4 style="margin-bottom:8px; color:#234;">Detalle Mensual</h4>
                            <div class="tabla-meses-crud">
                                <table style="width:100%; border-collapse:collapse;">
                                    <thead>
                                        <tr>
                                            <th>Mes</th>
                                            <th>Monto</th>
                                            <th>Concepto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Enero</td>
                                            <td><input type="number" class="mes-monto" id="mes-enero-monto"></td>
                                            <td><input type="text" class="mes-concepto" id="mes-enero-concepto"></td>
                                        </tr>
                                        <tr>
                                            <td>Febrero</td>
                                            <td><input type="number" class="mes-monto" id="mes-febrero-monto"></td>
                                            <td><input type="text" class="mes-concepto" id="mes-febrero-concepto"></td>
                                        </tr>
                                        <tr>
                                            <td>Marzo</td>
                                            <td><input type="number" class="mes-monto" id="mes-marzo-monto"></td>
                                            <td><input type="text" class="mes-concepto" id="mes-marzo-concepto"></td>
                                        </tr>
                                        <tr>
                                            <td>Abril</td>
                                            <td><input type="number" class="mes-monto" id="mes-abril-monto"></td>
                                            <td><input type="text" class="mes-concepto" id="mes-abril-concepto"></td>
                                        </tr>
                                        <tr>
                                            <td>Mayo</td>
                                            <td><input type="number" class="mes-monto" id="mes-mayo-monto"></td>
                                            <td><input type="text" class="mes-concepto" id="mes-mayo-concepto"></td>
                                        </tr>
                                        <tr>
                                            <td>Junio</td>
                                            <td><input type="number" class="mes-monto" id="mes-junio-monto"></td>
                                            <td><input type="text" class="mes-concepto" id="mes-junio-concepto"></td>
                                        </tr>
                                        <tr>
                                            <td>Julio</td>
                                            <td><input type="number" class="mes-monto" id="mes-julio-monto"></td>
                                            <td><input type="text" class="mes-concepto" id="mes-julio-concepto"></td>
                                        </tr>
                                        <tr>
                                            <td>Agosto</td>
                                            <td><input type="number" class="mes-monto" id="mes-agosto-monto"></td>
                                            <td><input type="text" class="mes-concepto" id="mes-agosto-concepto"></td>
                                        </tr>
                                        <tr>
                                            <td>Septiembre</td>
                                            <td><input type="number" class="mes-monto" id="mes-septiembre-monto"></td>
                                            <td><input type="text" class="mes-concepto" id="mes-septiembre-concepto">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Octubre</td>
                                            <td><input type="number" class="mes-monto" id="mes-octubre-monto"></td>
                                            <td><input type="text" class="mes-concepto" id="mes-octubre-concepto"></td>
                                        </tr>
                                        <tr>
                                            <td>Noviembre</td>
                                            <td><input type="number" class="mes-monto" id="mes-noviembre-monto"></td>
                                            <td><input type="text" class="mes-concepto" id="mes-noviembre-concepto">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Diciembre</td>
                                            <td><input type="number" class="mes-monto" id="mes-diciembre-monto"></td>
                                            <td><input type="text" class="mes-concepto" id="mes-diciembre-concepto">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-actions">
                            <button type="submit">Guardar</button>
                            <button type="button" id="btn-cancelar">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>


    <!-- Modal Nueva Gestión -->
    <div id="modal-gestion" class="modal" style="display:none;">
        <div class="modal-content" style="max-width:400px;">
            <h2>Nueva Gestión</h2>
            <form id="form-gestion">
                <div class="form-group">
                    <label>Año</label>
                    <input type="number" id="gestion-anio" required min="2000" max="2099">
                </div>
                <div class="form-group">
                    <label>Nombre (opcional)</label>
                    <input type="text" id="gestion-nombre" placeholder="Ej: Gestión 2026">
                </div>
                <div class="modal-actions">
                    <button type="submit">Crear Gestión</button>
                    <button type="button" id="btn-cancelar-gestion">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Cuenta (Gestiones) eliminado, ahora la edición se hará en la vista cuenta -->
    </main>
    </div>

    <script>
        // Filtro automático en tiempo real para el input de búsqueda
        document.addEventListener('DOMContentLoaded', function() {
            const inputBusqueda = document.getElementById('filtro-cuentas');
            const formFiltros = document.getElementById('form-filtros');

            if (inputBusqueda && formFiltros) {
                let timeoutId;

                inputBusqueda.addEventListener('input', function() {
                    // Limpiar el timeout anterior
                    clearTimeout(timeoutId);

                    // Esperar 500ms después de que el usuario deje de escribir
                    timeoutId = setTimeout(() => {
                        formFiltros.submit();
                    }, 500);
                });

                // Si el usuario presiona Enter, enviar inmediatamente
                inputBusqueda.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        clearTimeout(timeoutId);
                        formFiltros.submit();
                    }
                });
            }
        });
    </script>

    <script src="{{ asset('js/gestor.js') }}"></script>
    <script src="{{ asset('js/no-back.js') }}"></script>
</body>

</html>
