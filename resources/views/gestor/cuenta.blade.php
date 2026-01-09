<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Cuenta</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/gestor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cuenta.css') }}">
</head>
<body>
    <div class="gestor-main-container">
        <header class="gestor-header">
            <h1>Gestor de Cuentas</h1>
            <nav class="nav-tabs">
                <a href="/cuenta" class="nav-tab active">Cuenta</a>
                <a href="/gestor" class="nav-tab">Gestor</a>
            </nav>
            <div class="user-info">
                <span class="user-name">👤 {{ session('usuario_nombre') }}</span>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-btn">Cerrar Sesión</button>
                </form>
            </div>
        </header>
        <main class="gestor-main">
            <section class="gestor-section">
                <h2>Configuración de Cuenta</h2>
                <p>Aquí podrás realizar las configuraciones correspondientes de tu cuenta.</p>
                <!-- Agrega aquí los formularios y opciones de configuración que necesites -->


                <div style="display:flex; gap:12px; align-items:center; margin-bottom:8px;">
                    <button id="toggle-gestiones" class="gestor-toolbar" style="margin-top:24px; font-size:1.1rem; background:#27ae60; color:#fff; border:none; padding:10px 22px; border-radius:4px; cursor:pointer;">Gestiones ▼</button>
                    <button id="toggle-exportar" class="gestor-toolbar" style="margin-top:24px; font-size:1.1rem; background:#f39c12; color:#fff; border:none; padding:10px 22px; border-radius:4px; cursor:pointer;">Exportar ▼</button>
                    <button id="toggle-traspasar" class="gestor-toolbar" style="margin-top:24px; font-size:1.1rem; background:#9b59b6; color:#fff; border:none; padding:10px 22px; border-radius:4px; cursor:pointer;">Traspasar ▼</button>
                </div>
                <div id="gestiones-crud" style="max-height:0; overflow:hidden; transition:max-height 0.5s ease; background:#ecf0f1; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.12); margin-top:18px; padding:0 20px;">
                    <!-- Aquí va el CRUD de gestiones -->
                    <h3 style="margin-top:18px;">CRUD de Gestiones</h3>
                    <form id="form-gestion" style="margin-bottom:16px;">
                        <div class="form-group">
                            <label for="gestion-anio">Año</label>
                            <input type="number" id="gestion-anio" required min="2000" max="2099">
                        </div>
                        <div class="form-group">
                            <label for="gestion-nombre">Nombre (opcional)</label>
                            <input type="text" id="gestion-nombre" placeholder="Ej: Gestión 2026">
                        </div>
                        <button type="submit" id="btn-crear-editar" style="background:#27ae60; color:#fff; border:none; padding:8px 18px; border-radius:4px; font-size:1em; margin-top:8px;">Crear Gestión</button>
                        <!-- El botón Cancelar se agrega por JS -->
                    </form>
                    <table style="width:100%; border-collapse:collapse; background:#fff;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Año</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="gestiones-list">
                            <!-- Aquí se mostrarán las gestiones -->
                        </tbody>
                    </table>
                </div>
                <div id="exportar-menu" style="max-height:0; overflow:hidden; transition:max-height 0.5s ease; background:#ecf0f1; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.12); margin-top:18px; padding:0 20px;">
                    <!-- Aquí va el CRUD de exportar -->
                    <h3 style="margin-top:18px;">EXPORTAR CLIENTES</h3>
                    
                    <div style="margin-bottom:16px; display:flex; gap:16px; align-items:center; flex-wrap:wrap;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="exportar-gestion">Gestión</label>
                            <select id="exportar-gestion" style="padding:8px 12px; border-radius:4px; border:1px solid #ccc; min-width:150px;">
                                <option value="">-- Seleccionar Gestión --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Selección de campos a exportar -->
                    <div style="background:#fff; padding:16px; border-radius:8px; margin-bottom:16px; border:1px solid #ddd;">
                        <h4 style="margin:0 0 12px 0; color:#2c3e50;">Seleccionar campos a exportar:</h4>
                        
                        <div style="display:flex; gap:8px; margin-bottom:12px;">
                            <button type="button" id="btn-seleccionar-todos-campos" style="background:#3498db; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer; font-size:0.9em;">Seleccionar Todos</button>
                            <button type="button" id="btn-deseleccionar-todos-campos" style="background:#95a5a6; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer; font-size:0.9em;">Deseleccionar Todos</button>
                        </div>

                        <p style="margin:8px 0; font-weight:600; color:#34495e;">📋 Datos Generales:</p>
                        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:8px; margin-bottom:12px;">
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-exportar" value="id_cliente" checked> ID Cliente
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-exportar" value="Correo_Electronico" checked> Correo Electrónico
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-exportar" value="Password"> Password
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-exportar" value="nombre" checked> Nombre
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-exportar" value="celular" checked> Celular
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-exportar" value="Fecha_Inicio"> Fecha Inicio
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-exportar" value="Fecha_Fin"> Fecha Fin
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-exportar" value="Concepto"> Concepto
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-exportar" value="SaldoPagar" checked> Saldo Pagar
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-exportar" value="AbonoDeuda"> Abono Deuda
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-exportar" value="TotalPagar" checked> Total Pagar
                            </label>
                        </div>

                        <p style="margin:8px 0; font-weight:600; color:#34495e;">📅 Detalle Mensual (Monto y Concepto juntos):</p>
                        <div style="display:flex; gap:8px; margin-bottom:12px; flex-wrap:wrap;">
                            <button type="button" id="btn-seleccionar-meses" style="background:#27ae60; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer; font-size:0.9em;">✓ Todos los Meses</button>
                            <button type="button" id="btn-deseleccionar-meses" style="background:#7f8c8d; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer; font-size:0.9em;">✗ Ningún Mes</button>
                            <button type="button" id="btn-seleccionar-conceptos" style="background:#e67e22; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer; font-size:0.9em;">✓ Todos los Conceptos</button>
                            <button type="button" id="btn-deseleccionar-conceptos" style="background:#95a5a6; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer; font-size:0.9em;">✗ Ningún Concepto</button>
                        </div>
                        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:8px; margin-bottom:12px;">
                            <!-- Enero -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-exportar campo-mes" value="ENERO"> Enero
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-exportar campo-concepto" value="ENERO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Febrero -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-exportar campo-mes" value="FEBRERO"> Febrero
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-exportar campo-concepto" value="FEBRERO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Marzo -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-exportar campo-mes" value="MARZO"> Marzo
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-exportar campo-concepto" value="MARZO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Abril -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-exportar campo-mes" value="ABRIL"> Abril
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-exportar campo-concepto" value="ABRIL_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Mayo -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-exportar campo-mes" value="MAYO"> Mayo
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-exportar campo-concepto" value="MAYO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Junio -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-exportar campo-mes" value="JUNIO"> Junio
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-exportar campo-concepto" value="JUNIO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Julio -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-exportar campo-mes" value="JULIO"> Julio
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-exportar campo-concepto" value="JULIO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Agosto -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-exportar campo-mes" value="AGOSTO"> Agosto
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-exportar campo-concepto" value="AGOSTO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Septiembre -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-exportar campo-mes" value="SEPTIEMBRE"> Septiembre
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-exportar campo-concepto" value="SEPTIEMBRE_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Octubre -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-exportar campo-mes" value="OCTUBRE"> Octubre
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-exportar campo-concepto" value="OCTUBRE_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Noviembre -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-exportar campo-mes" value="NOVIEMBRE"> Noviembre
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-exportar campo-concepto" value="NOVIEMBRE_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Diciembre -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-exportar campo-mes" value="DICIEMBRE"> Diciembre
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-exportar campo-concepto" value="DICIEMBRE_CONCEPTO"> Concepto
                                </label>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:16px; margin-bottom:18px; display:flex; gap:12px;">
                        <button id="btn-exportar-excel" style="background:#27ae60; color:#fff; border:none; padding:10px 22px; border-radius:4px; font-size:1em; cursor:pointer;">
                            📥 Exportar a Excel
                        </button>
                        <button id="btn-exportar-pdf" style="background:#e74c3c; color:#fff; border:none; padding:10px 22px; border-radius:4px; font-size:1em; cursor:pointer;">
                            📄 Exportar a PDF
                        </button>
                    </div>
                </div>

                <!-- SECCIÓN TRASPASAR -->
                <div id="traspasar-menu" style="max-height:0; overflow:hidden; transition:max-height 0.5s ease; background:#ecf0f1; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.12); margin-top:18px; padding:0 20px;">
                    <h3 style="margin-top:18px; color:#9b59b6;">TRASPASAR DATOS ENTRE GESTIONES</h3>
                    
                    <div style="margin-bottom:16px; display:flex; gap:16px; align-items:center; flex-wrap:wrap;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="traspasar-origen" style="font-weight:600; color:#2c3e50;">Gestión Origen</label>
                            <select id="traspasar-origen" style="padding:8px 12px; border-radius:4px; border:1px solid #ccc; min-width:180px;">
                                <option value="">-- Seleccionar Origen --</option>
                            </select>
                        </div>
                        <div style="font-size:1.5em; color:#9b59b6;">➜</div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="traspasar-destino" style="font-weight:600; color:#2c3e50;">Gestión Destino</label>
                            <select id="traspasar-destino" style="padding:8px 12px; border-radius:4px; border:1px solid #ccc; min-width:180px;">
                                <option value="">-- Seleccionar Destino --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Selección de campos a traspasar -->
                    <div style="background:#fff; padding:16px; border-radius:8px; margin-bottom:16px; border:1px solid #ddd;">
                        <h4 style="margin:0 0 12px 0; color:#2c3e50;">Seleccionar campos a traspasar:</h4>
                        
                        <div style="display:flex; gap:8px; margin-bottom:12px;">
                            <button type="button" id="btn-seleccionar-todos-traspasar" style="background:#3498db; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer; font-size:0.9em;">Seleccionar Todos</button>
                            <button type="button" id="btn-deseleccionar-todos-traspasar" style="background:#95a5a6; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer; font-size:0.9em;">Deseleccionar Todos</button>
                        </div>

                        <p style="margin:8px 0; font-weight:600; color:#34495e;">📋 Datos Generales:</p>
                        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:8px; margin-bottom:12px;">
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-traspasar" value="Correo_Electronico" checked> Correo Electrónico
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-traspasar" value="Password"> Password
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-traspasar" value="nombre" checked> Nombre
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-traspasar" value="celular" checked> Celular
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-traspasar" value="Fecha_Inicio"> Fecha Inicio
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-traspasar" value="Fecha_Fin"> Fecha Fin
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-traspasar" value="Concepto"> Concepto
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-traspasar" value="SaldoPagar"> Saldo Pagar
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-traspasar" value="AbonoDeuda"> Abono Deuda
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; padding:4px 8px; background:#f8f9fa; border-radius:4px;">
                                <input type="checkbox" class="campo-traspasar" value="TotalPagar"> Total Pagar
                            </label>
                        </div>

                        <p style="margin:8px 0; font-weight:600; color:#34495e;">📅 Detalle Mensual (Monto y Concepto juntos):</p>
                        <div style="display:flex; gap:8px; margin-bottom:12px; flex-wrap:wrap;">
                            <button type="button" id="btn-seleccionar-meses-traspasar" style="background:#27ae60; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer; font-size:0.9em;">✓ Todos los Meses</button>
                            <button type="button" id="btn-deseleccionar-meses-traspasar" style="background:#7f8c8d; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer; font-size:0.9em;">✗ Ningún Mes</button>
                            <button type="button" id="btn-seleccionar-conceptos-traspasar" style="background:#e67e22; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer; font-size:0.9em;">✓ Todos los Conceptos</button>
                            <button type="button" id="btn-deseleccionar-conceptos-traspasar" style="background:#95a5a6; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer; font-size:0.9em;">✗ Ningún Concepto</button>
                        </div>
                        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:8px; margin-bottom:12px;">
                            <!-- Enero -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-traspasar campo-mes-traspasar" value="ENERO"> Enero
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-traspasar campo-concepto-traspasar" value="ENERO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Febrero -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-traspasar campo-mes-traspasar" value="FEBRERO"> Febrero
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-traspasar campo-concepto-traspasar" value="FEBRERO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Marzo -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-traspasar campo-mes-traspasar" value="MARZO"> Marzo
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-traspasar campo-concepto-traspasar" value="MARZO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Abril -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-traspasar campo-mes-traspasar" value="ABRIL"> Abril
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-traspasar campo-concepto-traspasar" value="ABRIL_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Mayo -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-traspasar campo-mes-traspasar" value="MAYO"> Mayo
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-traspasar campo-concepto-traspasar" value="MAYO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Junio -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-traspasar campo-mes-traspasar" value="JUNIO"> Junio
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-traspasar campo-concepto-traspasar" value="JUNIO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Julio -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-traspasar campo-mes-traspasar" value="JULIO"> Julio
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-traspasar campo-concepto-traspasar" value="JULIO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Agosto -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-traspasar campo-mes-traspasar" value="AGOSTO"> Agosto
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-traspasar campo-concepto-traspasar" value="AGOSTO_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Septiembre -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-traspasar campo-mes-traspasar" value="SEPTIEMBRE"> Septiembre
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-traspasar campo-concepto-traspasar" value="SEPTIEMBRE_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Octubre -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-traspasar campo-mes-traspasar" value="OCTUBRE"> Octubre
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-traspasar campo-concepto-traspasar" value="OCTUBRE_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Noviembre -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-traspasar campo-mes-traspasar" value="NOVIEMBRE"> Noviembre
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-traspasar campo-concepto-traspasar" value="NOVIEMBRE_CONCEPTO"> Concepto
                                </label>
                            </div>
                            <!-- Diciembre -->
                            <div style="display:flex; gap:8px; background:#e8f6e8; padding:6px 10px; border-radius:4px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer;">
                                    <input type="checkbox" class="campo-traspasar campo-mes-traspasar" value="DICIEMBRE"> Diciembre
                                </label>
                                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; color:#b35400;">
                                    <input type="checkbox" class="campo-traspasar campo-concepto-traspasar" value="DICIEMBRE_CONCEPTO"> Concepto
                                </label>
                            </div>
                        </div>
                    </div>

                    <div style="background:#fff3cd; padding:12px; border-radius:8px; margin-bottom:16px; border:1px solid #ffc107;">
                        <p style="margin:0; color:#856404;"><strong>⚠️ Nota:</strong> Los clientes con el mismo ID en la gestión destino serán <strong>actualizados</strong> con los datos de la gestión origen. Los clientes nuevos serán <strong>creados</strong>.</p>
                    </div>

                    <div style="margin-top:16px; margin-bottom:18px; display:flex; gap:12px;">
                        <button id="btn-traspasar" style="background:#9b59b6; color:#fff; border:none; padding:10px 22px; border-radius:4px; font-size:1em; cursor:pointer;">
                            🔄 Traspasar Datos
                        </button>
                    </div>
                </div>

            </section>
        </main>
    </div>

    <!-- Modal de confirmación -->
    <div id="modal-confirmacion" class="modal-overlay">
        <div class="modal-box">
            <h3>Confirmar Eliminación</h3>
            <p>¿Seguro que deseas eliminar esta gestión?</p>
            <div class="modal-buttons">
                <button class="btn-confirmar" onclick="confirmarEliminarGestion()">Aceptar</button>
                <button class="btn-cancelar" onclick="cerrarModalConfirmacion()">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de traspaso -->
    <div id="modal-confirmacion-traspaso" class="modal-overlay">
        <div class="modal-box">
            <div style="font-size: 3rem; margin-bottom: 10px;">🔄</div>
            <h3>Confirmar Traspaso</h3>
            <p>¿Estás seguro de que deseas traspasar los datos?</p>
            <p style="font-size: 0.9rem; color: #7f8c8d; margin-top: 10px;">
                Esta acción actualizará los clientes existentes y creará los nuevos.
            </p>
            <div class="modal-buttons">
                <button class="btn-confirmar" onclick="confirmarTraspaso()">Sí, traspasar</button>
                <button class="btn-cancelar" onclick="cerrarModalConfirmacionTraspaso()">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Modal de advertencia -->
    <div id="modal-advertencia" class="modal-overlay">
        <div class="modal-box modal-advertencia">
            <div class="icono-advertencia">⚠️</div>
            <h3>Advertencia</h3>
            <p id="mensaje-advertencia"></p>
            <div class="modal-buttons">
                <button class="btn-aceptar" onclick="cerrarModalAdvertencia()">Aceptar</button>
            </div>
        </div>
    </div>

    <!-- Modal de éxito -->
    <div id="modal-exito" class="modal-overlay">
        <div class="modal-box modal-exito">
            <div class="icono-exito">✅</div>
            <h3 id="titulo-exito">¡Éxito!</h3>
            <p id="mensaje-exito"></p>
            <div id="detalles-exito" class="detalles-traspaso" style="display:none;">
                <!-- Aquí se mostrarán los detalles del traspaso -->
            </div>
            <div class="modal-buttons">
                <button class="btn-exito" onclick="cerrarModalExito()">¡Entendido!</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/cuenta.js') }}"></script>

</body>
</html>
