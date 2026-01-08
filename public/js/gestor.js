// Gestor de Cuentas - JavaScript Optimizado
window.addEventListener('DOMContentLoaded', function() {
    // ==================== CONSTANTES ====================
    const MESES = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 
                   'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    
    const ICONOS = {
        ver: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
              </svg>`,
        editar: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                   <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                 </svg>`,
        eliminar: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                     <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                     <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                   </svg>`
    };

    // ==================== ELEMENTOS DOM ====================
    const elementos = {
        btnAgregar: document.getElementById('btn-agregar'),
        btnDeudores: document.getElementById('btn-deudores'),
        modalCrud: document.getElementById('modal-crud'),
        modalVer: document.getElementById('modal-ver'),
        modalAdvertencia: document.getElementById('modal-advertencia'),
        modalConfirmar: document.getElementById('modal-confirmar'),
        btnCancelar: document.getElementById('btn-cancelar'),
        btnCerrarVer: document.getElementById('btn-cerrar-ver'),
        btnCerrarAdvertencia: document.getElementById('btn-cerrar-advertencia'),
        btnConfirmarEliminar: document.getElementById('btn-confirmar-eliminar'),
        btnCancelarEliminar: document.getElementById('btn-cancelar-eliminar'),
        formCrud: document.getElementById('form-crud'),
        tablaCuentas: document.getElementById('tabla-cuentas'),
        modalTitle: document.getElementById('modal-title'),
        abonoInput: document.getElementById('crud-AbonoDeuda'),
        // Elementos de filtro
        filtroCampo: document.getElementById('filtro-campo'),
        filtroCuentas: document.getElementById('filtro-cuentas'),
        filtroDias: document.getElementById('filtro-dias'),
        btnGuardar: document.querySelector('#form-crud button[type="submit"]'),
        // Elementos de gestión
        selectGestion: document.getElementById('select-gestion'),
        btnNuevaGestion: document.getElementById('btn-nueva-gestion'),
        modalGestion: document.getElementById('modal-gestion'),
        formGestion: document.getElementById('form-gestion'),
        btnCancelarGestion: document.getElementById('btn-cancelar-gestion'),
        gestionActualLabel: document.getElementById('gestion-actual-label'),
        // Elementos de modal de cuenta y gestiones
        linkCuenta: document.getElementById('link-cuenta'),
        modalCuenta: document.getElementById('modal-cuenta'),
        btnCerrarCuenta: document.getElementById('btn-cerrar-cuenta'),
        gestionesBody: document.getElementById('gestiones-body'),
        formEditarGestion: document.getElementById('form-editar-gestion'),
        editarGestionAnio: document.getElementById('editar-gestion-anio'),
        modalEditarGestion: document.getElementById('modal-editar-gestion'),
        btnCancelarEditarGestion: document.getElementById('btn-cancelar-editar-gestion')
    };

    let modoEdicion = false;
    let idClienteEdicion = null;
    let clientesData = []; // Almacenar clientes para filtrado
    let mostrandoDeudores = false; // Estado del filtro deudores
    let idClienteEliminar = null; // ID del cliente a eliminar
    let datosOriginales = {}; // Datos originales para comparar cambios
    let gestionActual = null; // Gestión actualmente seleccionada
    let gestionEditId = null; // ID de la gestión en edición

    // ==================== UTILIDADES ====================
    const getCSRFToken = () => document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const formatearNumero = (num) => num % 1 === 0 ? num : num.toFixed(2);
    
    const capitalizarMes = (mes) => mes.charAt(0) + mes.slice(1).toLowerCase();
    
    const getElementValue = (id) => document.getElementById(id)?.value || '';
    
    const setElementValue = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.value = value !== undefined && value !== null ? value : '';
    };

    // ==================== FUNCIONES PRINCIPALES ====================
    function mostrarAdvertencia(mensaje) {
        document.getElementById('advertencia-message').textContent = mensaje;
        elementos.modalAdvertencia.style.display = 'flex';
    }

    function mostrarConfirmacionEliminar(id, nombreCliente) {
        idClienteEliminar = id;
        document.getElementById('confirmar-message').textContent = 
            `¿Está seguro que desea eliminar al cliente "${nombreCliente}"?`;
        elementos.modalConfirmar.style.display = 'flex';
    }

    function cerrarModalConfirmar() {
        elementos.modalConfirmar.style.display = 'none';
        idClienteEliminar = null;
    }

    function resetearFormulario() {
        elementos.formCrud.reset();
        modoEdicion = false;
        idClienteEdicion = null;
        datosOriginales = {};
        if (elementos.btnGuardar) {
            elementos.btnGuardar.disabled = false;
        }
    }

    // Verificar si hay cambios en el formulario (solo en modo edición)
    function verificarCambios() {
        if (!modoEdicion) return;
        
        const camposComparar = [
            'crud-Correo_Electronico', 'crud-Password', 'crud-nombre', 
            'crud-celular', 'crud-Fecha_Inicio', 'crud-Fecha_Fin', 
            'crud-Concepto', 'crud-AbonoDeuda'
        ];
        
        let hayCambios = false;
        
        // Comparar campos principales
        for (const campo of camposComparar) {
            const valorActual = getElementValue(campo);
            const valorOriginal = datosOriginales[campo] || '';
            if (valorActual !== valorOriginal) {
                hayCambios = true;
                break;
            }
        }
        
        // Comparar meses si no hay cambios aún
        if (!hayCambios) {
            for (const mes of MESES) {
                const montoActual = getElementValue('mes-' + mes.toLowerCase() + '-monto');
                const montoOriginal = datosOriginales['mes-' + mes.toLowerCase() + '-monto'] || '';
                const conceptoActual = getElementValue('mes-' + mes.toLowerCase() + '-concepto');
                const conceptoOriginal = datosOriginales['mes-' + mes.toLowerCase() + '-concepto'] || '';
                
                if (montoActual !== montoOriginal || conceptoActual !== conceptoOriginal) {
                    hayCambios = true;
                    break;
                }
            }
        }
        
        if (elementos.btnGuardar) {
            elementos.btnGuardar.disabled = !hayCambios;
        }
    }

    // Mostrar modal para agregar
    if (elementos.btnAgregar && elementos.modalCrud) {
        elementos.btnAgregar.addEventListener('click', function() {
            modoEdicion = false;
            idClienteEdicion = null;
            datosOriginales = {};
            elementos.modalTitle.textContent = 'Agregar Cuenta';
            elementos.formCrud.reset();
            document.getElementById('crud-id_cliente').disabled = false;
            if (elementos.btnGuardar) {
                elementos.btnGuardar.disabled = false; // Habilitado para agregar
            }
            elementos.modalCrud.style.display = 'block';
        });
    }

    // Cerrar modal CRUD
    if (elementos.btnCancelar && elementos.modalCrud) {
        elementos.btnCancelar.addEventListener('click', function() {
            elementos.modalCrud.style.display = 'none';
            resetearFormulario();
        });
    }
    
    // Cerrar modal Ver
    if (elementos.btnCerrarVer && elementos.modalVer) {
        elementos.btnCerrarVer.addEventListener('click', function() {
            elementos.modalVer.style.display = 'none';
        });
    }
    
    // Cerrar modal Advertencia
    if (elementos.btnCerrarAdvertencia && elementos.modalAdvertencia) {
        elementos.btnCerrarAdvertencia.addEventListener('click', function() {
            elementos.modalAdvertencia.style.display = 'none';
        });
    }
    
    window.onclick = function(event) {
        if (event.target === elementos.modalVer) {
            elementos.modalVer.style.display = 'none';
        }
    };

    // ==================== API HELPERS ====================
    const apiRequest = (url, options = {}) => {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        };
        return fetch(url, { ...defaultOptions, ...options });
    };

    // ==================== RENDERIZAR CLIENTES ====================
    function renderizarClientes(clientes) {
        elementos.tablaCuentas.innerHTML = '';
        
        if (clientes.length === 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td colspan="11" style="text-align:center; padding:20px; color:#7f8c8d; font-style:italic;">No se encontraron coincidencias</td>`;
            elementos.tablaCuentas.appendChild(tr);
            return;
        }
        
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        
        clientes.forEach(cliente => {
            const tr = document.createElement('tr');
            let simboloDeuda = '';
            
            // Calcular días restantes y aplicar color
            if (cliente.Fecha_Fin) {
                const fechaFin = new Date(cliente.Fecha_Fin);
                const diffTime = fechaFin - hoy;
                const diasRestantes = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                // Si pasaron más de 30 días de vencido
                if (diasRestantes < -30) {
                    tr.style.backgroundColor = '#f5c6cb'; // Rosa más intenso
                    tr.style.color = '#721c24';
                    simboloDeuda = ' 🚨';
                } else if (diasRestantes < 0) {
                    tr.style.backgroundColor = '#f8d7da'; // Rosa pastel - Vencido
                    tr.style.color = '#721c24';
                } else if (diasRestantes <= 3) {
                    tr.style.backgroundColor = '#f8d7da'; // Rosa pastel - Urgente
                    tr.style.color = '#721c24';
                } else if (diasRestantes <= 5) {
                    tr.style.backgroundColor = '#ffe5cc'; // Naranja pastel - Próximo
                    tr.style.color = '#856404';
                } else if (diasRestantes <= 8) {
                    tr.style.backgroundColor = '#fff9db'; // Amarillo pastel - Atención
                    tr.style.color = '#856404';
                }
            }
            tr.innerHTML = `
                <td>${cliente.id_cliente || cliente.id}${simboloDeuda}</td>
                <td>${cliente.Correo_Electronico || ''}</td>
                <td>${cliente.Password || ''}</td>
                <td>${cliente.nombre || ''}</td>
                <td>${cliente.Fecha_Inicio || ''}</td>
                <td>${cliente.Fecha_Fin || ''}</td>
                <td>${cliente.Concepto || ''}</td>
                <td>${cliente.SaldoPagar || ''}</td>
                <td>${cliente.AbonoDeuda || ''}</td>
                <td>${cliente.TotalPagar || ''}</td>
                <td>
                    <button class="btn-ver" data-id="${cliente.id_cliente}" title="Ver">${ICONOS.ver}</button>
                    <button class="btn-editar" data-id="${cliente.id_cliente}" title="Editar">${ICONOS.editar}</button>
                    <button class="btn-eliminar" data-id="${cliente.id_cliente}" title="Eliminar">${ICONOS.eliminar}</button>
                </td>
            `;
            elementos.tablaCuentas.appendChild(tr);
        });
    }

    // ==================== FILTRAR CLIENTES ====================
    function filtrarClientes() {
        const campo = elementos.filtroCampo.value;
        const busqueda = elementos.filtroCuentas.value.toLowerCase().trim();
        const dias = elementos.filtroDias.value;
        
        let clientesFiltrados = clientesData;
        
        // Filtrar por campo de búsqueda
        if (busqueda) {
            clientesFiltrados = clientesFiltrados.filter(cliente => {
                switch (campo) {
                    case 'id':
                        return (cliente.id_cliente || '').toString().toLowerCase().includes(busqueda);
                    case 'nombre':
                        return (cliente.nombre || '').toLowerCase().includes(busqueda);
                    case 'correo':
                        return (cliente.Correo_Electronico || '').toLowerCase().includes(busqueda);
                    default:
                        return true;
                }
            });
        }
        
        // Filtrar por días restantes hasta Fecha_Fin
        if (dias !== 'todos') {
            const diasLimite = parseInt(dias);
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);
            
            clientesFiltrados = clientesFiltrados.filter(cliente => {
                if (!cliente.Fecha_Fin) return false;
                const fechaFin = new Date(cliente.Fecha_Fin);
                const diffTime = fechaFin - hoy;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                return diffDays >= 0 && diffDays <= diasLimite;
            });
        }
        
        renderizarClientes(clientesFiltrados);
    }

    // ==================== FILTRAR DEUDORES ====================
    function filtrarDeudores() {
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        
        const deudores = clientesData.filter(cliente => {
            if (!cliente.Fecha_Fin) return false;
            const fechaFin = new Date(cliente.Fecha_Fin);
            const diffTime = fechaFin - hoy;
            const diasVencidos = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            // Mostrar solo los que tienen más de 5 días vencidos (diasVencidos < -5)
            return diasVencidos < -5;
        });
        
        renderizarClientes(deudores);
    }

    // ==================== GESTIONES ====================
    function mostrarGestionActivaRapido() {
        fetch('/gestiones')
            .then(res => res.json())
            .then(gestiones => {
                const activa = gestiones.find(g => g.activa);
                const label = document.getElementById('gestion-activa-label');
                if (label && activa) {
                    label.textContent = activa.nombre || `Gestión ${activa.anio}`;
                }
            });
    }
    // Mostrar gestión activa lo antes posible
    mostrarGestionActivaRapido();
    
    function cargarGestiones() {
        fetch('/gestiones')
            .then(res => res.json())
            .then(gestiones => {
                const activa = gestiones.find(g => g.activa);
                const label = document.getElementById('gestion-activa-label');
                if (label && activa) {
                    label.textContent = activa.nombre || `Gestión ${activa.anio}`;
                    gestionActual = activa.id;
                }
                // Cargar clientes después de obtener la gestión activa
                cargarClientes();
            });
    }
    cargarGestiones();
    
    // ==================== CARGAR CLIENTES ====================
    function cargarClientes() {
        if (!gestionActual) {
            // Si aún no hay gestión activa, esperar
            return;
        }
        const url = `/api/clientes?gestion_id=${gestionActual}`;
        
        apiRequest(url)
            .then(res => res.json())
            .then(clientes => {
                clientesData = clientes; // Guardar para filtrado
                if (mostrandoDeudores) {
                    filtrarDeudores();
                } else {
                    filtrarClientes();
                }
            })
            .catch(err => {
                console.error('Error al cargar clientes:', err);
            });
    }

        // Refuerzo: los filtros siempre operan sobre clientesData, que solo contiene los clientes de la gestión seleccionada
        // No se mezclan datos de gestiones diferentes

    // Event listener para nueva gestión
    if (elementos.btnNuevaGestion) {
        elementos.btnNuevaGestion.addEventListener('click', () => {
            document.getElementById('gestion-anio').value = new Date().getFullYear();
            document.getElementById('gestion-nombre').value = '';
            elementos.modalGestion.style.display = 'flex';
        });
    }

    if (elementos.btnCancelarGestion) {
        elementos.btnCancelarGestion.addEventListener('click', () => {
            elementos.modalGestion.style.display = 'none';
        });
    }

    if (elementos.formGestion) {
        elementos.formGestion.addEventListener('submit', function(e) {
            e.preventDefault();
            const anio = document.getElementById('gestion-anio').value;
            const nombre = document.getElementById('gestion-nombre').value || `Gestión ${anio}`;
            
            apiRequest('/api/gestiones', {
                method: 'POST',
                body: JSON.stringify({ anio, nombre })
            })
            .then(res => {
                return res.json().then(data => ({ ok: res.ok, data }));
            })
            .then(({ ok, data }) => {
                if (!ok || data.error) {
                    mostrarAdvertencia(data.error || 'Error al crear la gestión');
                } else {
                    elementos.modalGestion.style.display = 'none';
                    cargarGestiones();
                }
            })
            .catch(err => {
                mostrarAdvertencia('Error al crear la gestión');
            });
        });
    }
    
    // Event listeners para filtros
    if (elementos.filtroCampo) {
        elementos.filtroCampo.addEventListener('change', () => {
            mostrandoDeudores = false;
            elementos.btnDeudores.textContent = 'Deudores';
            filtrarClientes();
        });
    }
    if (elementos.filtroCuentas) {
        elementos.filtroCuentas.addEventListener('input', () => {
            mostrandoDeudores = false;
            elementos.btnDeudores.textContent = 'Deudores';
            filtrarClientes();
        });
    }
    if (elementos.filtroDias) {
        elementos.filtroDias.addEventListener('change', () => {
            mostrandoDeudores = false;
            elementos.btnDeudores.textContent = 'Deudores';
            filtrarClientes();
        });
    }
    
    // Event listener para botón Deudores
    if (elementos.btnDeudores) {
        elementos.btnDeudores.addEventListener('click', () => {
            mostrandoDeudores = !mostrandoDeudores;
            if (mostrandoDeudores) {
                elementos.btnDeudores.textContent = 'Ver Todos';
                // Limpiar filtros
                elementos.filtroCuentas.value = '';
                elementos.filtroDias.value = 'todos';
                filtrarDeudores();
            } else {
                elementos.btnDeudores.textContent = 'Deudores';
                filtrarClientes();
            }
        });
    }

    // ==================== CALCULAR SALDOS ====================
    function calcularSaldos() {
        let suma = 0;
        MESES.forEach(mes => {
            const val = parseFloat(document.getElementById('mes-' + mes.toLowerCase() + '-monto')?.value || 0);
            suma += isNaN(val) ? 0 : val;
        });
        setElementValue('crud-SaldoPagar', formatearNumero(suma));
        const abono = parseFloat(getElementValue('crud-AbonoDeuda') || 0);
        const total = suma - (isNaN(abono) ? 0 : abono);
        setElementValue('crud-TotalPagar', formatearNumero(total));
        
        // Verificar cambios después de calcular saldos
        verificarCambios();
    }

    // Escuchar cambios en los campos de meses y abono
    MESES.forEach(mes => {
        const input = document.getElementById('mes-' + mes.toLowerCase() + '-monto');
        if (input) input.addEventListener('input', calcularSaldos);
        const inputConcepto = document.getElementById('mes-' + mes.toLowerCase() + '-concepto');
        if (inputConcepto) inputConcepto.addEventListener('input', verificarCambios);
    });
    if (elementos.abonoInput) elementos.abonoInput.addEventListener('input', calcularSaldos);
    
    // Escuchar cambios en todos los campos del formulario para verificar cambios
    const camposFormulario = [
        'crud-Correo_Electronico', 'crud-Password', 'crud-nombre', 
        'crud-celular', 'crud-Fecha_Inicio', 'crud-Fecha_Fin', 'crud-Concepto'
    ];
    camposFormulario.forEach(campoId => {
        const campo = document.getElementById(campoId);
        if (campo) campo.addEventListener('input', verificarCambios);
    });

    // ==================== GUARDAR CLIENTE ====================
    if (elementos.formCrud) {
        elementos.formCrud.addEventListener('submit', function(e) {
            e.preventDefault();
            calcularSaldos();
            
            const data = {
                id_cliente: getElementValue('crud-id_cliente'),
                Correo_Electronico: getElementValue('crud-Correo_Electronico'),
                Password: getElementValue('crud-Password'),
                nombre: getElementValue('crud-nombre'),
                celular: getElementValue('crud-celular'),
                Fecha_Inicio: getElementValue('crud-Fecha_Inicio'),
                Fecha_Fin: getElementValue('crud-Fecha_Fin'),
                Concepto: getElementValue('crud-Concepto'),
                SaldoPagar: getElementValue('crud-SaldoPagar'),
                AbonoDeuda: getElementValue('crud-AbonoDeuda') || 0,
                TotalPagar: getElementValue('crud-TotalPagar')
            };

                // Asignar gestion_id seleccionado
                if (elementos.selectGestion && elementos.selectGestion.value) {
                    data.gestion_id = elementos.selectGestion.value;
                }
            
            // Meses y conceptos
            MESES.forEach(mes => {
                data[mes] = getElementValue('mes-' + mes.toLowerCase() + '-monto');
                data[mes + '_CONCEPTO'] = getElementValue('mes-' + mes.toLowerCase() + '-concepto');
            });
            
            const url = modoEdicion ? `/api/clientes/${idClienteEdicion}` : '/api/clientes';
            const method = modoEdicion ? 'PUT' : 'POST';
            
            apiRequest(url, {
                method: method,
                body: JSON.stringify(data)
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => Promise.reject(err));
                }
                return res.json();
            })
            .then(() => {
                elementos.modalCrud.style.display = 'none';
                resetearFormulario();
                cargarClientes();
            })
            .catch(error => {
                if (error.errors && error.errors.id_cliente) {
                    mostrarAdvertencia('El ID Cliente ya existe. Por favor, ingrese un ID diferente.');
                } else if (error.message) {
                    mostrarAdvertencia('Error: ' + error.message);
                } else {
                    mostrarAdvertencia('Error al guardar el cliente. Por favor, intente nuevamente.');
                }
            });
        });
    }

    // ==================== EVENT DELEGATION ACCIONES ====================
    if (elementos.tablaCuentas) {
        elementos.tablaCuentas.addEventListener('click', function(e) {
            const target = e.target.closest('button');
            if (!target) return;
            
            const id = target.dataset.id;

            // Ver detalles del cliente
            if (target.classList.contains('btn-ver')) {
                apiRequest(`/api/clientes/${id}`)
                    .then(res => res.json())
                    .then(cliente => {
                        if (!cliente || !cliente.id_cliente) {
                            mostrarAdvertencia('Error al cargar los datos del cliente.');
                            return;
                        }
                        
                        let mesesHTML = '';
                        MESES.forEach(mes => {
                            const monto = cliente[mes] || '';
                            const concepto = cliente[mes + '_CONCEPTO'] || '';
                            if (monto || concepto) {
                                mesesHTML += `
                                    <tr>
                                        <td>${capitalizarMes(mes)}</td>
                                        <td>${monto}</td>
                                        <td>${concepto}</td>
                                    </tr>
                                `;
                            }
                        });
                        
                        document.getElementById('modal-ver-body').innerHTML = `
                            <h3 style="text-align: center; margin: 0 0 20px 0; color: #2c3e50;">Detalle de Cuenta</h3>
                            <div class="info-row">
                                <strong>ID Cliente</strong>
                                <span>${cliente.id_cliente || ''}</span>
                            </div>
                            <div class="info-row">
                                <strong>Correo Electrónico</strong>
                                <span>${cliente.Correo_Electronico || ''}</span>
                            </div>
                            <div class="info-row">
                                <strong>Password</strong>
                                <span>${cliente.Password || ''}</span>
                            </div>
                            <div class="info-row">
                                <strong>Nombre</strong>
                                <span>${cliente.nombre || ''}</span>
                            </div>
                            <div class="info-row">
                                <strong>Fecha Inicio</strong>
                                <span>${cliente.Fecha_Inicio || ''}</span>
                            </div>
                            <div class="info-row">
                                <strong>Fecha Fin</strong>
                                <span>${cliente.Fecha_Fin || 'N/A'}</span>
                            </div>
                            <div class="info-row">
                                <strong>Concepto</strong>
                                <span>${cliente.Concepto || ''}</span>
                            </div>
                            <div class="info-row">
                                <strong>Saldo Pagar</strong>
                                <span>${cliente.SaldoPagar || 0}</span>
                            </div>
                            <div class="info-row">
                                <strong>Abono Deuda</strong>
                                <span>${cliente.AbonoDeuda || 0}</span>
                            </div>
                            <div class="info-row">
                                <strong>Total Pagar</strong>
                                <span>${cliente.TotalPagar || 0}</span>
                            </div>
                            <h4 style="margin: 20px 0 10px 0; color: #2c3e50;">Detalle Mensual</h4>
                            <table class="tabla-detalle-ver">
                                <thead>
                                    <tr>
                                        <th>Mes</th>
                                        <th>Monto</th>
                                        <th>Concepto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${mesesHTML}
                                </tbody>
                            </table>
                        `;
                        elementos.modalVer.style.display = 'block';
                    })
                    .catch(() => {
                        mostrarAdvertencia('Error al cargar los datos del cliente.');
                    });
            }

            // Editar cliente
            if (target.classList.contains('btn-editar')) {
                apiRequest(`/api/clientes/${id}`)
                    .then(res => res.json())
                    .then(cliente => {
                        if (!cliente || !cliente.id_cliente) {
                            mostrarAdvertencia('Error al cargar los datos del cliente.');
                            return;
                        }
                        
                        modoEdicion = true;
                        idClienteEdicion = cliente.id_cliente;
                        elementos.modalTitle.textContent = 'Editar Cuenta';
                        
                        // Cargar datos en el formulario
                        setElementValue('crud-id_cliente', cliente.id_cliente);
                        document.getElementById('crud-id_cliente').disabled = true;
                        setElementValue('crud-Correo_Electronico', cliente.Correo_Electronico);
                        setElementValue('crud-Password', cliente.Password);
                        setElementValue('crud-nombre', cliente.nombre);
                        setElementValue('crud-celular', cliente.celular);
                        setElementValue('crud-Fecha_Inicio', cliente.Fecha_Inicio);
                        setElementValue('crud-Fecha_Fin', cliente.Fecha_Fin);
                        setElementValue('crud-Concepto', cliente.Concepto);
                        setElementValue('crud-SaldoPagar', cliente.SaldoPagar);
                        setElementValue('crud-AbonoDeuda', cliente.AbonoDeuda || 0);
                        setElementValue('crud-TotalPagar', cliente.TotalPagar);
                        
                        // Cargar meses
                        MESES.forEach(mes => {
                            setElementValue('mes-' + mes.toLowerCase() + '-monto', cliente[mes]);
                            setElementValue('mes-' + mes.toLowerCase() + '-concepto', cliente[mes + '_CONCEPTO']);
                        });
                        
                        // Guardar datos originales para comparar cambios
                        datosOriginales = {
                            'crud-Correo_Electronico': cliente.Correo_Electronico || '',
                            'crud-Password': cliente.Password || '',
                            'crud-nombre': cliente.nombre || '',
                            'crud-celular': cliente.celular || '',
                            'crud-Fecha_Inicio': cliente.Fecha_Inicio || '',
                            'crud-Fecha_Fin': cliente.Fecha_Fin || '',
                            'crud-Concepto': cliente.Concepto || '',
                            'crud-AbonoDeuda': String(cliente.AbonoDeuda || 0)
                        };
                        
                        // Guardar meses originales
                        MESES.forEach(mes => {
                            datosOriginales['mes-' + mes.toLowerCase() + '-monto'] = cliente[mes] || '';
                            datosOriginales['mes-' + mes.toLowerCase() + '-concepto'] = cliente[mes + '_CONCEPTO'] || '';
                        });
                        
                        // Deshabilitar botón guardar inicialmente
                        if (elementos.btnGuardar) {
                            elementos.btnGuardar.disabled = true;
                        }
                        
                        elementos.modalCrud.style.display = 'block';
                    })
                    .catch(() => {
                        mostrarAdvertencia('Error al cargar los datos del cliente.');
                    });
            }

            // Eliminar cliente - mostrar modal de confirmación
            if (target.classList.contains('btn-eliminar')) {
                // Buscar el nombre del cliente en los datos cargados
                const cliente = clientesData.find(c => c.id_cliente === id);
                const nombreCliente = cliente ? cliente.nombre : id;
                mostrarConfirmacionEliminar(id, nombreCliente);
            }
        });
    }

    // Event listeners para modal de confirmación de eliminación
    if (elementos.btnConfirmarEliminar) {
        elementos.btnConfirmarEliminar.addEventListener('click', function() {
            if (idClienteEliminar) {
                apiRequest(`/api/clientes/${idClienteEliminar}`, { method: 'DELETE' })
                    .then(res => res.json())
                    .then(() => {
                        cerrarModalConfirmar();
                        cargarClientes();
                    })
                    .catch(() => {
                        cerrarModalConfirmar();
                        mostrarAdvertencia('Error al eliminar el cliente.');
                    });
            }
        });
    }

    if (elementos.btnCancelarEliminar) {
        elementos.btnCancelarEliminar.addEventListener('click', cerrarModalConfirmar);
    }

    // ==================== MODAL CUENTA (GESTIONES) ====================
    const linkCuenta = document.getElementById('link-cuenta');
    const modalCuenta = document.getElementById('modal-cuenta');
    const btnCerrarCuenta = document.getElementById('btn-cerrar-cuenta');
    const gestionesBody = document.getElementById('gestiones-body');

    function renderizarGestiones(gestiones) {
        gestionesBody.innerHTML = '';
        gestiones.forEach(g => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${g.anio}</td>
                <td>${g.activa ? '✅' : ''}</td>
                <td>
                    <button class="btn-editar-gestion" data-id="${g.id}" style="background:#f39c12;color:#fff;border:none;padding:4px 10px;border-radius:4px;">Editar</button>
                    <button class="btn-eliminar-gestion" data-id="${g.id}" style="background:#e74c3c;color:#fff;border:none;padding:4px 10px;border-radius:4px;">Eliminar</button>
                </td>
            `;
            gestionesBody.appendChild(tr);
        });
    }

    function cargarGestionesTabla() {
        fetch('/api/gestiones')
            .then(res => res.json())
            .then(gestiones => {
                renderizarGestiones(gestiones);
            });
    }

    if (linkCuenta) {
        linkCuenta.addEventListener('click', function(e) {
            e.preventDefault();
            modalCuenta.style.display = 'flex';
            cargarGestionesTabla();
        });
    }
    if (btnCerrarCuenta) {
        btnCerrarCuenta.addEventListener('click', function() {
            modalCuenta.style.display = 'none';
        });
    }

    // Editar y eliminar gestiones
    gestionesBody.addEventListener('click', function(e) {
        const btn = e.target.closest('button');
        if (!btn) return;
        const id = btn.dataset.id;
        if (btn.classList.contains('btn-editar-gestion')) {
            gestionEditId = id;
            fetch('/api/gestiones')
                .then(res => res.json())
                .then(gestiones => {
                    const gestion = gestiones.find(g => g.id == id);
                    if (gestion) {
                        document.getElementById('editar-gestion-anio').value = gestion.anio;
                        document.getElementById('modal-editar-gestion').style.display = 'flex';
                    }
                });
        }
        if (btn.classList.contains('btn-eliminar-gestion')) {
            if (confirm('¿Seguro que deseas eliminar esta gestión?')) {
                fetch(`/api/gestiones/${id}`, { method: 'DELETE' })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            cargarGestionesTabla();
                        } else {
                            alert(data.message || 'No se pudo eliminar la gestión');
                        }
                    });
            }
        }
    });
    document.getElementById('form-editar-gestion').addEventListener('submit', function(e) {
        e.preventDefault();
        const anio = document.getElementById('editar-gestion-anio').value;
        fetch(`/api/gestiones/${gestionEditId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ anio })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('modal-editar-gestion').style.display = 'none';
            cargarGestionesTabla();
        });
    });
    document.getElementById('btn-cancelar-editar-gestion').addEventListener('click', function() {
        document.getElementById('modal-editar-gestion').style.display = 'none';
    });
});
