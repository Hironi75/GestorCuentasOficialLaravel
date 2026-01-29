<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/gestor.css') }}">
</head>
<body>
    <div class="gestor-main-container">
        @include('gestor.header')
        <main class="gestor-main">
            <section class="gestor-section">

                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6">Dashboard</h2>

                <!-- Selector de gestión (control segmentado con prev/next) -->
                <div class="mb-6">
                    <div id="gestionesControl" class="inline-flex items-center space-x-2 bg-slate-100 rounded-md p-1">
                        <button id="gestPrev" type="button" class="p-2 rounded text-slate-600 hover:bg-slate-200" aria-label="Gestión anterior">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>

                        <button id="gestSelected" type="button" class="px-4 py-2 bg-white rounded shadow text-sm font-medium text-slate-800 truncate" style="max-width:320px;">
                            Cargando gestión...
                        </button>

                        <button id="gestNext" type="button" class="p-2 rounded text-slate-600 hover:bg-slate-200" aria-label="Siguiente gestión">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        <!-- Spinner pequeño -->
                        <div id="gestionesSpinner" class="ml-2 hidden" aria-hidden="true">
                            <svg class="animate-spin h-5 w-5 text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Tarjetas de estadísticas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Tarjeta de clientes -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Clientes Registrados</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1"><span id="totalClientesSpan">{{ $totalClientes }}</span></p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <!-- Tarjeta de gestiones -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Gestiones Totales</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1"><span id="totalGestionesSpan">{{ $totalGestiones }}</span></p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <!-- Icono de gestor (maletín) -->
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 11V7m0 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0H6a2 2 0 00-2 2v7a2 2 0 002 2h12a2 2 0 002-2v-7a2 2 0 00-2-2h-6zM8 11h8"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <!-- Tarjeta: Clientes agregados hoy -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-teal-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Clientes agregados hoy</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1"><span id="clientesAgregadosHoySpan">{{ $clientesAgregadosHoy }}</span></p>
                            </div>
                            <div class="bg-teal-100 p-3 rounded-full">
                                <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v4m0 0V4m0 4h4M4 12v8h16v-8M8 8h8"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <!-- Tarjeta: Clientes editados hoy -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-indigo-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Clientes editados hoy</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1"><span id="clientesEditadosHoySpan">{{ $clientesEditadosHoy }}</span></p>
                            </div>
                            <div class="bg-indigo-100 p-3 rounded-full">
                                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <!-- Tarjeta: Ganancias totales -->

                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Ganancias totales</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1"><span id="gananciasTotalesSpan">€ {{ number_format($gananciasTotales ?? 0, 2, ',', '.') }}</span></p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8c-3 0-5 2-5 4s2 4 5 4 5-2 5-4-2-4-5-4zM12 2v4M12 18v4"/>
                                </svg>
                            </div>
                        </div>
                    </div>


                </div>



                <!-- Ganancias por mes - diseño mejorado -->
                <div class="mt-8 bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Ganancias por mes</h3>
                            <p class="text-sm text-gray-500">Resumen por mes de la gestión activa</p>
                        </div>
                        <div class="text-sm text-gray-600">Total año: <span class="ml-2 text-xl font-bold text-gray-800">€ {{ number_format($gananciasTotales ?? 0, 2, ',', '.') }}</span></div>
                    </div>

                    <!-- Layout: en pantallas grandes dividir en 2 columnas iguales (50% / 50%) -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Columna izquierda: tabla simple -->
                        <div class="overflow-hidden rounded-lg border bg-white">
                            <div class="px-6 py-3 border-b">
                                <div class="text-sm font-medium text-gray-600">Detalle mensual</div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Mes</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y">
                                        @foreach($gananciasPorMes as $g)
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">{{ $g->nombre }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-800">€ {{ number_format($g->total, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="px-6 py-4 bg-slate-50 border-t">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-semibold text-gray-700">Total año</div>
                                    <div class="text-sm font-bold text-gray-800">€ {{ number_format($gananciasTotales ?? 0, 2, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna derecha: placeholder para gráfico (pie) -->
                        <div class="overflow-hidden rounded-lg border bg-white p-6">
                            <!-- Control: seleccionar tipo de gráfico -->
                            <div class="flex items-center justify-end mb-4">
                                <div class="inline-flex rounded-md bg-slate-100 p-1">
                                    <label class="relative">
                                        <input type="radio" name="chartType" value="pie" class="sr-only" checked>
                                        <span class="px-3 py-1 text-sm font-medium text-gray-700 cursor-pointer select-none">Gráfico Pastel</span>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" name="chartType" value="bar" class="sr-only">
                                        <span class="px-3 py-1 text-sm font-medium text-gray-700 cursor-pointer select-none">Gráfico Barras</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Área del gráfico -->
                            <div class="w-full flex items-start justify-center pt-6 lg:pt-20">
                                <div class="w-full max-w-2xl px-4" style="height:360px;">
                                    <canvas id="gananciasChart" class="w-full h-full block mx-auto" aria-label="Gráfico de ganancias" role="img"></canvas>
                                    <div id="noDataMsg" class="hidden text-gray-500 text-center mt-4">No hay datos de ganancias</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Mensaje de bienvenida -->
                <div class="mt-8 bg-linear-to-r from-slate-700 to-slate-800 rounded-xl p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">¡Bienvenido, {{ session('usuario_nombre') }}!</h3>
                    <p class="text-slate-300 text-sm">Este es tu panel de control. Desde aquí puedes gestionar todas tus cuentas.</p>
                </div>

            </section>
        </main>
    </div>

    <!-- Chart.js CDN + script para renderizar el pie con datos del servidor -->
    <script>
        // Asegurar que Chart.js esté disponible; si no, cargar fallback local desde /js/chart.umd.min.js
        (function(){
            function initChart(){
                const raw = @json($gananciasPorMes ?? []) || [];

                // Normalizar etiquetas y valores (asegurar número correcto incluso con coma decimal)
                let labels = [];
                let data = [];
                if (Array.isArray(raw) && raw.length) {
                    for (let i = 0; i < raw.length; i++) {
                        const r = raw[i] || {};
                        // etiqueta
                        if (r && (r.nombre !== undefined && r.nombre !== null && String(r.nombre).length)) {
                            labels.push(r.nombre);
                        } else {
                            labels.push('Sin nombre');
                        }

                        // valor numérico normalizado
                        let val = 0;
                        if (r && r.total !== undefined && r.total !== null) {
                            // permitir formatos con coma o punto
                            const s = String(r.total).replace(/\./g,'').replace(',','.');
                            const n = parseFloat(s);
                            if (Number.isFinite(n)) val = n;
                        }
                        data.push(val);
                    }
                }

                let totalSum = data.length ? data.reduce((a,b)=>a+b,0) : 0;
                let hasData = totalSum > 0;

                const colors = ['#34d399','#059669','#60a5fa','#3b82f6','#f59e0b','#f97316','#ef4444','#f43f5e','#a78bfa','#7c3aed','#06b6d4','#0ea5a4'];

                const canvas = document.getElementById('gananciasChart');
                const noDataMsg = document.getElementById('noDataMsg');
                if(!canvas) return;

                let chartInstance = null;
                let ro = null; // ResizeObserver
                let currentType = 'pie';

                function formatCurrency(value){ return '€ ' + Number(value).toFixed(2).replace('.',','); }

                function buildConfig(type){
                    if(type === 'bar'){
                        return {
                            type: 'bar',
                            data:{ labels: labels, datasets:[{ label:'Ganancia', data: data, backgroundColor: colors, borderColor: colors, borderWidth:1 }]},
                            options:{ responsive:true, scales:{ x:{ ticks:{ color:'#374151'} }, y:{ ticks:{ color:'#374151', callback:function(v){ return '€ ' + Number(v).toFixed(2).replace('.',','); } }, beginAtZero:true } }, plugins:{ legend:{ display:true, position:'bottom', align:'center', labels:{ boxWidth:12, padding:8 } }, tooltip:{ callbacks:{ label:function(ctx){ return formatCurrency(ctx.parsed.y); } } } }, maintainAspectRatio:false, aspectRatio:1.6, layout:{ padding:{ top:6, bottom:6 } } }
                        };
                    }

                    return {
                        type:'pie', data:{ labels: labels, datasets:[{ data: data, backgroundColor: colors, borderColor:'#fff', borderWidth:1 }] },
                        options:{ responsive:true, plugins:{ legend:{ position:'bottom', align:'center', labels:{ boxWidth:12, padding:8, usePointStyle:true } }, tooltip:{ callbacks:{ label:function(ctx){ return ctx.label + ': ' + formatCurrency(ctx.raw); } } } }, maintainAspectRatio:false, aspectRatio:1.2, layout:{ padding:{ top:6, bottom:6 } } }
                    };
                }

                // Debounce util
                function debounce(fn, wait){
                    let t = null;
                    return function(){
                        const args = arguments;
                        clearTimeout(t);
                        t = setTimeout(function(){ fn.apply(null, args); }, wait);
                    };
                }

                function destroyChart(){
                    if(chartInstance){
                        try{ chartInstance.destroy(); }catch(e){}
                        chartInstance = null;
                    }
                    if(ro){ try{ ro.disconnect(); }catch(e){} ro = null; }
                }

                // Actualizar datos del chart sin recrearlo
                function updateData(newGanancias){
                    // transformar newGanancias [{nombre,total},...]
                    const newLabels = Array.isArray(newGanancias) ? newGanancias.map(function(g){ return g.nombre || 'Sin nombre'; }) : [];
                    const newData = Array.isArray(newGanancias) ? newGanancias.map(function(g){
                        if(!g || g.total === undefined || g.total === null) return 0;
                        const s = String(g.total).replace(/\./g,'').replace(',','.');
                        const n = parseFloat(s);
                        return Number.isFinite(n) ? n : 0;
                    }) : [];

                    totalSum = newData.length ? newData.reduce((a,b)=>a+b,0) : 0;
                    hasData = totalSum > 0;

                    if(!hasData){
                        canvas.style.display='none';
                        noDataMsg.classList.remove('hidden');
                        if(chartInstance){ try{ chartInstance.destroy(); }catch(e){} chartInstance = null; }
                        return;
                    }

                    // Si existe la instancia, actualizar los datos y refrescar
                    if(chartInstance){
                        chartInstance.data.labels = newLabels;
                        if(chartInstance.data.datasets && chartInstance.data.datasets[0]){
                            chartInstance.data.datasets[0].data = newData;
                        }
                        try{ chartInstance.update(); }catch(e){ console.warn('chart update failed', e); }
                    } else {
                        // crear nuevo con el tipo actual
                        labels = newLabels;
                        data = newData;
                        renderChart(currentType);
                    }
                }

                function renderChart(type){
                    // Guardar tipo actual
                    currentType = type || currentType || 'pie';

                    // Destruir previo si existe
                    if(chartInstance){ chartInstance.destroy(); chartInstance = null; }

                    if(!hasData){ canvas.style.display='none'; noDataMsg.classList.remove('hidden'); return; }
                    canvas.style.display=''; noDataMsg.classList.add('hidden');

                    const cfg = buildConfig(type);
                    // Crear chart con el contexto 2D
                    const ctx = canvas.getContext('2d');
                    chartInstance = new Chart(ctx, cfg);

                    // Asegurar que se ajuste cuando el contenedor cambie
                    attachResizeObserver();
                }

                // Observador de tamaño para el contenedor del canvas
                function attachResizeObserver(){
                    const container = canvas.parentElement || canvas.parentNode;
                    if(!container) return;

                    // desconectar previo
                    if(ro){ try{ ro.disconnect(); }catch(e){} ro = null; }

                    // Preferir ResizeObserver si está disponible
                    if(window.ResizeObserver){
                        ro = new ResizeObserver(debounce(function(){
                            try{
                                if(chartInstance && typeof chartInstance.resize === 'function'){
                                    chartInstance.resize();
                                } else if(chartInstance){
                                    // fallback: re-render
                                    const tmpType = currentType || 'pie';
                                    renderChart(tmpType);
                                }
                            }catch(e){
                                // si algo falla, asegurarse que no rompa la app
                                console.warn('ResizeObserver chart resize failed', e);
                            }
                        }, 120));

                        try{ ro.observe(container); }catch(e){ /* ignore */ }
                    } else {
                        // Fallback: escuchar evento window resize
                        window.addEventListener('resize', debounce(function(){ if(chartInstance && typeof chartInstance.resize === 'function'){ chartInstance.resize(); } }, 150));
                    }
                }

                // Inicializar
                renderChart('pie');

                // Control de selección (radio buttons)
                const radios = document.querySelectorAll('input[name="chartType"]');
                radios.forEach(function(r){ r.addEventListener('change', function(e){
                    // Al cambiar el tipo simplemente re-renderizamos con el nuevo tipo
                    const newType = e.target.value || 'pie';
                    // destruir y crear nuevo
                    if(chartInstance){ chartInstance.destroy(); chartInstance = null; }
                    renderChart(newType);
                }); });

                // Exportar funciones públicas: limpieza y actualización de datos
                return { destroy: destroyChart, update: updateData };
            }

            // Intentar inicializar ya; si Chart no está definido, cargar fallback local y luego inicializar
            if(typeof window.Chart !== 'undefined'){
                // inicializar y guardar referencia por si se necesita destruir luego
                window.__ganancias_chart_controller = initChart();
            } else {
                // Cargar fallback local chart.umd.min.js (ubicado en public/js)
                var s = document.createElement('script');
                s.src = '{{ asset('js/chart.umd.min.js') }}';
                s.onload = function(){ console.log('Chart.js cargado desde /js/chart.umd.min.js (fallback local)'); window.__ganancias_chart_controller = initChart(); };
                s.onerror = function(){ console.warn('No se pudo cargar Chart.js desde CDN ni desde el fallback local.'); };
                document.head.appendChild(s);
            }

            // --- Funciones para cargar gestiones y actualizar dashboard dinámicamente ---
            // estado local de gestiones
            window._gestionesList = window._gestionesList || [];
            window._gestionesIndex = 0; // índice seleccionado

            function updateDashboardUI(resp){
                 try{
                     document.getElementById('totalClientesSpan').textContent = resp.totalClientes ?? '0';
                     document.getElementById('totalGestionesSpan').textContent = resp.totalGestiones ?? '0';
                     document.getElementById('clientesAgregadosHoySpan').textContent = resp.clientesAgregadosHoy ?? '0';
                     document.getElementById('clientesEditadosHoySpan').textContent = resp.clientesEditadosHoy ?? '0';
                     document.getElementById('gananciasTotalesSpan').textContent = '€ ' + (Number(resp.gananciasTotales || 0).toFixed(2).replace('.',','));

                     // Actualizar tabla mensual
                     const tbody = document.querySelector('table tbody');
                     if(tbody){
                         tbody.innerHTML = '';
                         (resp.gananciasPorMes || []).forEach(function(g){
                             const tr = document.createElement('tr');
                             tr.className = 'hover:bg-slate-50';
                             const tdName = document.createElement('td'); tdName.className = 'px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800'; tdName.textContent = g.nombre;
                             const tdTotal = document.createElement('td'); tdTotal.className = 'px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-800'; tdTotal.textContent = '€ ' + Number(g.total || 0).toFixed(2).replace('.',',');
                             tr.appendChild(tdName); tr.appendChild(tdTotal);
                             tbody.appendChild(tr);
                         });
                     }

                     // Actualizar gráfico usando el controlador expuesto
                     if(window.__ganancias_chart_controller && typeof window.__ganancias_chart_controller.update === 'function'){
                         window.__ganancias_chart_controller.update(resp.gananciasPorMes || []);
                     } else if(window.__ganancias_chart_controller && typeof window.__ganancias_chart_controller.destroy === 'function'){
                         // fallback: reiniciar y actualizar
                         window.__ganancias_chart_controller.destroy();
                         window.__ganancias_chart_controller = initChart();
                         if(window.__ganancias_chart_controller && typeof window.__ganancias_chart_controller.update === 'function'){
                             window.__ganancias_chart_controller.update(resp.gananciasPorMes || []);
                         }
                     }

                 }catch(e){ console.warn('updateDashboardUI error', e); }
             }

             // Obtener lista de gestiones y preparar control segmentado
             function fetchGestiones(){
                 return fetch('/gestiones', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                     .then(function(r){ return r.json(); })
                     .then(function(list){
                         window._gestionesList = list || [];
                         initGestControl();
                         return window._gestionesList;
                     }).catch(function(e){ console.warn('Error fetching gestiones', e); return []; });
             }

             function initGestControl(){
                 const list = window._gestionesList || [];
                 const selectedBtn = document.getElementById('gestSelected');
                 const prevBtn = document.getElementById('gestPrev');
                 const nextBtn = document.getElementById('gestNext');

                 // determinar índice inicial: localStorage -> activa -> primera
                 let stored = null;
                 try{ stored = localStorage.getItem('dashboard_selected_gestion'); }catch(e){ stored = null; }
                 let idx = 0;
                 if(stored){ idx = list.findIndex(function(g){ return String(g.id) === String(stored); }); }
                 if(idx < 0) idx = list.findIndex(function(g){ return g.activa; });
                 if(idx < 0) idx = 0;
                 window._gestionesIndex = idx;

                 function renderLabel(){
                     const cur = list[window._gestionesIndex];
                     if(cur){
                         selectedBtn.textContent = cur.nombre || ('Gestión ' + cur.anio);
                         selectedBtn.title = cur.nombre || ('Gestión ' + cur.anio);
                         // estilo activo
                         selectedBtn.classList.add('bg-white','shadow');
                     } else {
                         selectedBtn.textContent = 'Sin gestión';
                     }
                 }

                 prevBtn.onclick = function(){
                     if(!list.length) return;
                     window._gestionesIndex = (window._gestionesIndex - 1 + list.length) % list.length;
                     renderLabel();
                     selectGestionByIndex(window._gestionesIndex);
                 };

                 nextBtn.onclick = function(){
                     if(!list.length) return;
                     window._gestionesIndex = (window._gestionesIndex + 1) % list.length;
                     renderLabel();
                     selectGestionByIndex(window._gestionesIndex);
                 };

                 selectedBtn.onclick = function(){
                     // si hay pocas gestiones, mostrar prompt para elegir
                     if(list.length <= 10){
                         const names = list.map(function(g,i){ return (i+1) + '. ' + (g.nombre || ('Gestión ' + g.anio)); }).join('\n');
                         const choice = prompt('Selecciona gestión por número:\n' + names);
                         const num = parseInt(choice||'',10);
                         if(num && num >= 1 && num <= list.length){
                             window._gestionesIndex = num-1;
                             renderLabel();
                             selectGestionByIndex(window._gestionesIndex);
                         }
                     } else {
                         nextBtn.click();
                     }
                 };

                 renderLabel();
                 selectGestionByIndex(window._gestionesIndex);
             }

             function selectGestionByIndex(idx){
                 const list = window._gestionesList || [];
                 if(!list.length || idx < 0 || idx >= list.length) return;
                 const g = list[idx];
                 if(!g) return;
                 // persistir en localStorage
                 try{ localStorage.setItem('dashboard_selected_gestion', String(g.id)); }catch(e){}

                 // mostrar spinner
                 const spinner = document.getElementById('gestionesSpinner'); if(spinner) spinner.classList.remove('hidden');

                 fetch('/dashboard/data/' + g.id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                     .then(function(r){ return r.json(); })
                     .then(function(resp){
                         updateDashboardUI(resp);
                     })
                     .catch(function(e){ console.warn('Error fetching dashboard data', e); })
                     .finally(function(){ if(spinner) spinner.classList.add('hidden'); });
             }

             // iniciar carga de gestiones
             fetchGestiones();

         })();
     </script>
 </body>
 </html>

