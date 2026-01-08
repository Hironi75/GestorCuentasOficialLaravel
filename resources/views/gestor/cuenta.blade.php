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
        </header>
        <main class="gestor-main">
            <section class="gestor-section">
                <h2>Configuración de Cuenta</h2>
                <p>Aquí podrás realizar las configuraciones correspondientes de tu cuenta.</p>
                <!-- Agrega aquí los formularios y opciones de configuración que necesites -->


                <div style="display:flex; gap:12px; align-items:center; margin-bottom:8px;">
                    <button id="toggle-gestiones" class="gestor-toolbar" style="margin-top:24px; font-size:1.1rem; background:#27ae60; color:#fff; border:none; padding:10px 22px; border-radius:4px; cursor:pointer;">Gestiones ▼</button>
                    <button id="toggle-exportar" class="gestor-toolbar" style="margin-top:24px; font-size:1.1rem; background:#f39c12; color:#fff; border:none; padding:10px 22px; border-radius:4px; cursor:pointer;">Exportar ▼</button>
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
                    <h3 style="margin-top:18px;">CRUD de EXPORTAR</h3>
                    
  
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

    <script src="{{ asset('js/cuenta.js') }}"></script>
</body>
</html>
