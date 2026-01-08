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
