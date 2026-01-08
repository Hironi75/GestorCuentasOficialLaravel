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
        <header class="gestor-header">
            <h1>Gestor de Cuentas</h1>
            <nav class="nav-tabs">
                <a href="/cuenta" class="nav-tab">Cuenta</a>
                <a href="/gestor" class="nav-tab active">Gestor</a>
            </nav>
            <div class="gestion-selector">
                <span id="gestion-activa-label" style="font-size:1.1em; background:#f5f6fa; color:#34495e; padding:7px 16px; border-radius:7px; margin-left:4px; display:inline-block; min-width:120px;"></span>
            </div>
            <button onclick="window.location.href='/'" class="logout-btn">Cerrar sesión</button>
        </header>
        <main class="gestor-main">
            <section class="gestor-section">
                <div class="gestor-toolbar">
                    <h2 style="margin:0; color:#2d3436; font-size:1.5rem;">Listado de Cuentas <span id="gestion-actual-label" style="font-size:0.9rem; color:#7f8c8d;"></span></h2>
                    <div style="display:flex; gap:8px; align-items:center; min-width:420px; width:100%;">
                        <select id="filtro-campo">
                            <option value="id">ID</option>
                            <option value="nombre">Nombre</option>
                            <option value="correo">Correo electrónico</option>
                        </select>
                        <input id="filtro-cuentas" type="text" placeholder="Buscar...">
                        <select id="filtro-dias">
                            <option value="todos">Todos</option>
                            <option value="3">3 días o menos</option>
                            <option value="5">5 días o menos</option>
                            <option value="6">6 días o menos</option>
                            <option value="8">8 días o menos</option>
                        </select>
                        <div style="margin-left:auto; display:flex; gap:8px;">
                            <button id="btn-agregar">Agregar</button>
                            <button id="btn-deudores" class="deudores-btn">Deudores</button>
                        </div>
                    </div>
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
                                <th>Saldo Pagar</th>
                                <th style="border:1px solid #b2bec3; padding:8px;">Abono Deuda</th>
                                <th style="border:1px solid #b2bec3; padding:8px;">Total Pagar</th>
                                <th style="border:1px solid #b2bec3; padding:8px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-cuentas">
                            <!-- Filas dinámicas -->
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
                            <input type="email" id="crud-Correo_Electronico" required>
                        </label>
                        <label>Password
                            <input type="text" id="crud-Password" required>
                        </label>
                        <label>Nombre
                            <input type="text" id="crud-nombre" required>
                        </label>
                        <label>Concepto
                            <input type="text" id="crud-Concepto" required>
                        </label>
                        <label>Fecha Inicio
                            <input type="date" id="crud-Fecha_Inicio" required>
                        </label>
                        <label>Fecha Fin
                            <input type="date" id="crud-Fecha_Fin" required>
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
    <script src="script.js"></script>
    <script src="{{ asset('js/gestor.js') }}"></script>
    <script src="{{ asset('js/no-back.js') }}"></script>
</body>

</html>