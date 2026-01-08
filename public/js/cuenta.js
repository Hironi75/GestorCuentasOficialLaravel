document.addEventListener('DOMContentLoaded', function() {
    cargarGestiones();
    document.getElementById('form-gestion').addEventListener('submit', crearGestion);
});

function cargarGestiones() {
    fetch('/gestiones')
        .then(r => r.json())
        .then(gestiones => {
            const tbody = document.getElementById('gestiones-list');
            tbody.innerHTML = '';
            gestiones.forEach(g => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${g.id}</td>
                    <td>${g.anio}</td>
                    <td>${g.nombre ?? ''}</td>
                    <td>
                        <button onclick="editarGestion(${g.id}, ${g.anio}, '${g.nombre ? g.nombre.replace(/'/g, "\\'") : ''}')" style="background:#2980b9;color:#fff;border:none;padding:4px 10px;border-radius:3px;cursor:pointer;">Editar</button>
                        <button onclick="eliminarGestion(${g.id})" style="background:#e74c3c;color:#fff;border:none;padding:4px 10px;border-radius:3px;cursor:pointer;">Eliminar</button>
                        <button onclick="activarGestion(${g.id})" style="background:${g.activa ? '#27ae60' : '#b2bec3'};color:#fff;border:none;padding:4px 10px;border-radius:3px;cursor:pointer;">${g.activa ? 'Activa' : 'Activar'}</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        });
}

// Formulario de edición
let editandoId = null;
function editarGestion(id, anio, nombre) {
    editandoId = id;
    document.getElementById('gestion-anio').value = anio;
    document.getElementById('gestion-nombre').value = nombre;
    document.getElementById('form-gestion').scrollIntoView({behavior:'smooth'});
    document.getElementById('btn-crear-editar').textContent = 'Guardar Cambios';
    document.getElementById('btn-cancelar-edicion').style.display = 'inline-block';
}

// Modificar el submit para crear o editar
function crearGestion(e) {
    e.preventDefault();
    const anio = document.getElementById('gestion-anio').value;
    const nombre = document.getElementById('gestion-nombre').value;
    if(editandoId) {
        fetch(`/gestiones/${editandoId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({anio, nombre})
        })
        .then(r => r.json())
        .then(res => {
            if(res.error) {
                alert(res.error);
            } else {
                cargarGestiones();
                document.getElementById('form-gestion').reset();
                editandoId = null;
                document.getElementById('btn-crear-editar').textContent = 'Crear Gestión';
                document.getElementById('btn-cancelar-edicion').style.display = 'none';
            }
        });
    } else {
        fetch('/gestiones', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({anio, nombre})
        })
        .then(r => r.json())
        .then(res => {
            if(res.error) {
                alert(res.error);
            } else {
                cargarGestiones();
                document.getElementById('form-gestion').reset();
            }
        });
    }
}

// Botón cancelar edición
if(!document.getElementById('btn-cancelar-edicion')) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.id = 'btn-cancelar-edicion';
    btn.textContent = 'Cancelar';
    btn.style = 'background:#b2bec3;color:#fff;border:none;padding:8px 18px;border-radius:4px;font-size:1em;margin-left:10px;display:none;';
    btn.onclick = function() {
        editandoId = null;
        document.getElementById('form-gestion').reset();
        document.getElementById('btn-crear-editar').textContent = 'Crear Gestión';
        btn.style.display = 'none';
    };
    document.getElementById('form-gestion').appendChild(btn);
}

// Modal de confirmación
let idGestionEliminar = null;

function mostrarModalConfirmacion(id) {
    idGestionEliminar = id;
    const modal = document.getElementById('modal-confirmacion');
    modal.classList.add('show');
}

function cerrarModalConfirmacion() {
    const modal = document.getElementById('modal-confirmacion');
    modal.classList.remove('show');
    idGestionEliminar = null;
}

function mostrarModalAdvertencia(mensaje) {
    console.log('Mostrando modal advertencia:', mensaje);
    const modal = document.getElementById('modal-advertencia');
    const mensajeEl = document.getElementById('mensaje-advertencia');
    console.log('Modal encontrado:', modal);
    console.log('Mensaje elemento encontrado:', mensajeEl);
    if (modal && mensajeEl) {
        mensajeEl.textContent = mensaje;
        modal.style.display = 'flex';
        modal.classList.add('show');
        console.log('Modal debería estar visible ahora');
    } else {
        console.error('No se encontraron los elementos del modal');
        alert(mensaje);
    }
}

function cerrarModalAdvertencia() {
    const modal = document.getElementById('modal-advertencia');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
    }
}

function confirmarEliminarGestion() {
    console.log('Confirmando eliminación de gestión:', idGestionEliminar);
    if (!idGestionEliminar) return;
    fetch(`/gestiones/${idGestionEliminar}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(r => {
        console.log('Respuesta status:', r.status);
        if (!r.ok) {
            return r.json().then(data => {
                console.log('Error data:', data);
                throw { message: data.message || 'Error al eliminar la gestión' };
            });
        }
        return r.json();
    })
    .then(res => {
        console.log('Respuesta exitosa:', res);
        cerrarModalConfirmacion();
        if(res.success) {
            cargarGestiones();
        } else {
            mostrarModalAdvertencia(res.message || 'No se pudo eliminar');
        }
    })
    .catch(error => {
        console.log('Error capturado:', error);
        cerrarModalConfirmacion();
        setTimeout(() => {
            mostrarModalAdvertencia(error.message || 'No se puede eliminar una gestión con clientes asociados');
        }, 300);
    });
}

function eliminarGestion(id) {
    mostrarModalConfirmacion(id);
}

function activarGestion(id) {
    fetch(`/gestiones/${id}/activa`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(r => r.json())
    .then(() => cargarGestiones());
}

// Cerrar modales al hacer clic fuera
window.addEventListener('click', function(event) {
    const modalConfirmacion = document.getElementById('modal-confirmacion');
    const modalAdvertencia = document.getElementById('modal-advertencia');
    
    if (event.target === modalConfirmacion) {
        cerrarModalConfirmacion();
    }
    if (event.target === modalAdvertencia) {
        cerrarModalAdvertencia();
    }
});

// Deslizante
const toggleBtn = document.getElementById('toggle-gestiones');
const gestionesCrud = document.getElementById('gestiones-crud');
let abierto = false;
toggleBtn.addEventListener('click', function() {
    abierto = !abierto;
    if (abierto) {
        gestionesCrud.style.maxHeight = '800px';
        toggleBtn.innerHTML = 'Gestiones ▲';
    } else {
        gestionesCrud.style.maxHeight = '0';
        toggleBtn.innerHTML = 'Gestiones ▼';
    }
});

// Deslizante Exportar
const exportarBtn = document.getElementById('toggle-exportar');
const exportarMenu = document.getElementById('exportar-menu');
let exportarAbierto = false;
if (exportarBtn && exportarMenu) {
    exportarBtn.addEventListener('click', function() {
        exportarAbierto = !exportarAbierto;
        if (exportarAbierto) {
            exportarMenu.style.maxHeight = '1200px';
            exportarMenu.style.padding = '0 20px 20px 20px';
            exportarBtn.innerHTML = 'Exportar ▲';
            cargarGestionesExportar();
        } else {
            exportarMenu.style.maxHeight = '0';
            exportarMenu.style.padding = '0 20px';
            exportarBtn.innerHTML = 'Exportar ▼';
        }
    });
}

// ===================== FUNCIONALIDAD EXPORTAR =====================

// Cargar gestiones en el select de exportar
async function cargarGestionesExportar() {
    try {
        const response = await fetch('/api/gestiones');
        const gestiones = await response.json();
        const select = document.getElementById('exportar-gestion');
        select.innerHTML = '<option value="">-- Seleccionar Gestión --</option>';
        gestiones.forEach(g => {
            select.innerHTML += `<option value="${g.id}">${g.nombre || 'Gestión ' + g.anio} (${g.anio})</option>`;
        });
    } catch (error) {
        console.error('Error al cargar gestiones:', error);
    }
}

// Seleccionar/Deseleccionar todos los campos
if (document.getElementById('btn-seleccionar-todos-campos')) {
    document.getElementById('btn-seleccionar-todos-campos').addEventListener('click', function() {
        document.querySelectorAll('.campo-exportar').forEach(check => {
            check.checked = true;
        });
    });
}

if (document.getElementById('btn-deseleccionar-todos-campos')) {
    document.getElementById('btn-deseleccionar-todos-campos').addEventListener('click', function() {
        document.querySelectorAll('.campo-exportar').forEach(check => {
            check.checked = false;
        });
    });
}

// Seleccionar/Deseleccionar solo meses
if (document.getElementById('btn-seleccionar-meses')) {
    document.getElementById('btn-seleccionar-meses').addEventListener('click', function() {
        document.querySelectorAll('.campo-mes').forEach(check => {
            check.checked = true;
        });
    });
}

if (document.getElementById('btn-deseleccionar-meses')) {
    document.getElementById('btn-deseleccionar-meses').addEventListener('click', function() {
        document.querySelectorAll('.campo-mes').forEach(check => {
            check.checked = false;
        });
    });
}

// Seleccionar/Deseleccionar solo conceptos
if (document.getElementById('btn-seleccionar-conceptos')) {
    document.getElementById('btn-seleccionar-conceptos').addEventListener('click', function() {
        document.querySelectorAll('.campo-concepto').forEach(check => {
            check.checked = true;
        });
    });
}

if (document.getElementById('btn-deseleccionar-conceptos')) {
    document.getElementById('btn-deseleccionar-conceptos').addEventListener('click', function() {
        document.querySelectorAll('.campo-concepto').forEach(check => {
            check.checked = false;
        });
    });
}

// Obtener campos seleccionados
function obtenerCamposSeleccionados() {
    const campos = [];
    document.querySelectorAll('.campo-exportar:checked').forEach(check => {
        campos.push(check.value);
    });
    return campos;
}

// Exportar a Excel
if (document.getElementById('btn-exportar-excel')) {
    document.getElementById('btn-exportar-excel').addEventListener('click', function() {
        const gestionId = document.getElementById('exportar-gestion').value;
        const campos = obtenerCamposSeleccionados();
        
        if (!gestionId) {
            mostrarModalAdvertencia('Por favor, selecciona una gestión.');
            return;
        }
        
        if (campos.length === 0) {
            mostrarModalAdvertencia('Por favor, selecciona al menos un campo para exportar.');
            return;
        }
        
        // Redirigir a la ruta de exportación
        window.location.href = `/api/exportar/excel?gestion_id=${gestionId}&campos=${campos.join(',')}`;
    });
}

// Exportar a PDF
if (document.getElementById('btn-exportar-pdf')) {
    document.getElementById('btn-exportar-pdf').addEventListener('click', function() {
        const gestionId = document.getElementById('exportar-gestion').value;
        const campos = obtenerCamposSeleccionados();
        
        if (!gestionId) {
            mostrarModalAdvertencia('Por favor, selecciona una gestión.');
            return;
        }
        
        if (campos.length === 0) {
            mostrarModalAdvertencia('Por favor, selecciona al menos un campo para exportar.');
            return;
        }
        
        // Redirigir a la ruta de exportación
        window.location.href = `/api/exportar/pdf?gestion_id=${gestionId}&campos=${campos.join(',')}`;
    });
}

// ===================== FUNCIONALIDAD TRASPASAR =====================

// Deslizante Traspasar
const traspasarBtn = document.getElementById('toggle-traspasar');
const traspasarMenu = document.getElementById('traspasar-menu');
let traspasarAbierto = false;
if (traspasarBtn && traspasarMenu) {
    traspasarBtn.addEventListener('click', function() {
        traspasarAbierto = !traspasarAbierto;
        if (traspasarAbierto) {
            traspasarMenu.style.maxHeight = '1200px';
            traspasarMenu.style.padding = '0 20px 20px 20px';
            traspasarBtn.innerHTML = 'Traspasar ▲';
            cargarGestionesTraspasar();
        } else {
            traspasarMenu.style.maxHeight = '0';
            traspasarMenu.style.padding = '0 20px';
            traspasarBtn.innerHTML = 'Traspasar ▼';
        }
    });
}

// Cargar gestiones en los selects de traspasar
async function cargarGestionesTraspasar() {
    try {
        const response = await fetch('/api/gestiones');
        const gestiones = await response.json();
        
        const selectOrigen = document.getElementById('traspasar-origen');
        const selectDestino = document.getElementById('traspasar-destino');
        
        selectOrigen.innerHTML = '<option value="">-- Seleccionar Origen --</option>';
        selectDestino.innerHTML = '<option value="">-- Seleccionar Destino --</option>';
        
        gestiones.forEach(g => {
            const option = `<option value="${g.id}">${g.nombre || 'Gestión ' + g.anio} (${g.anio})</option>`;
            selectOrigen.innerHTML += option;
            selectDestino.innerHTML += option;
        });
    } catch (error) {
        console.error('Error al cargar gestiones:', error);
    }
}

// Seleccionar/Deseleccionar todos los campos de traspasar
if (document.getElementById('btn-seleccionar-todos-traspasar')) {
    document.getElementById('btn-seleccionar-todos-traspasar').addEventListener('click', function() {
        document.querySelectorAll('.campo-traspasar').forEach(check => {
            check.checked = true;
        });
    });
}

if (document.getElementById('btn-deseleccionar-todos-traspasar')) {
    document.getElementById('btn-deseleccionar-todos-traspasar').addEventListener('click', function() {
        document.querySelectorAll('.campo-traspasar').forEach(check => {
            check.checked = false;
        });
    });
}

// Seleccionar/Deseleccionar solo meses de traspasar
if (document.getElementById('btn-seleccionar-meses-traspasar')) {
    document.getElementById('btn-seleccionar-meses-traspasar').addEventListener('click', function() {
        document.querySelectorAll('.campo-mes-traspasar').forEach(check => {
            check.checked = true;
        });
    });
}

if (document.getElementById('btn-deseleccionar-meses-traspasar')) {
    document.getElementById('btn-deseleccionar-meses-traspasar').addEventListener('click', function() {
        document.querySelectorAll('.campo-mes-traspasar').forEach(check => {
            check.checked = false;
        });
    });
}

// Seleccionar/Deseleccionar solo conceptos de traspasar
if (document.getElementById('btn-seleccionar-conceptos-traspasar')) {
    document.getElementById('btn-seleccionar-conceptos-traspasar').addEventListener('click', function() {
        document.querySelectorAll('.campo-concepto-traspasar').forEach(check => {
            check.checked = true;
        });
    });
}

if (document.getElementById('btn-deseleccionar-conceptos-traspasar')) {
    document.getElementById('btn-deseleccionar-conceptos-traspasar').addEventListener('click', function() {
        document.querySelectorAll('.campo-concepto-traspasar').forEach(check => {
            check.checked = false;
        });
    });
}

// Obtener campos seleccionados para traspasar
function obtenerCamposTraspasar() {
    const campos = [];
    document.querySelectorAll('.campo-traspasar:checked').forEach(check => {
        campos.push(check.value);
    });
    return campos;
}

// Ejecutar traspaso
if (document.getElementById('btn-traspasar')) {
    document.getElementById('btn-traspasar').addEventListener('click', async function() {
        const origenId = document.getElementById('traspasar-origen').value;
        const destinoId = document.getElementById('traspasar-destino').value;
        const campos = obtenerCamposTraspasar();
        
        if (!origenId) {
            mostrarModalAdvertencia('Por favor, selecciona una gestión de origen.');
            return;
        }
        
        if (!destinoId) {
            mostrarModalAdvertencia('Por favor, selecciona una gestión de destino.');
            return;
        }
        
        if (origenId === destinoId) {
            mostrarModalAdvertencia('La gestión de origen y destino no pueden ser la misma.');
            return;
        }
        
        if (campos.length === 0) {
            mostrarModalAdvertencia('Por favor, selecciona al menos un campo para traspasar.');
            return;
        }
        
        // Confirmar traspaso
        if (!confirm('¿Estás seguro de que deseas traspasar los datos? Esta acción actualizará los clientes existentes y creará los nuevos.')) {
            return;
        }
        
        try {
            const response = await fetch('/api/traspasar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    origen_id: origenId,
                    destino_id: destinoId,
                    campos: campos
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(`Traspaso completado exitosamente.\n\n${result.actualizados} clientes actualizados.\n${result.creados} clientes creados.`);
            } else {
                mostrarModalAdvertencia(result.message || 'Error al realizar el traspaso.');
            }
        } catch (error) {
            console.error('Error al traspasar:', error);
            mostrarModalAdvertencia('Error al realizar el traspaso. Por favor, intenta de nuevo.');
        }
    });
}
