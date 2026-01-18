<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Reportes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/gestor.css') }}">
</head>
<body class="bg-white">
    <div class="gestor-main-container">
        @include('gestor.header')
        <main class="gestor-main">
            <!-- Contenedor Principal con Fondo Blanco -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <!-- Título del Dashboard -->
                <div class="mb-8 text-left">
                    <h2 class="text-3xl font-bold text-gray-900">Dashboard de Reportes</h2>
                    <p class="text-gray-500 text-base mt-1">Vista general del sistema y estadísticas empresariales</p>
                </div>

                <!-- Menú de consulta de ganancias por mes y gestión (ahora dentro del dashboard) -->
                <div class="mb-8 bg-gray-50 border border-gray-200 rounded-lg p-4 sm:p-6 flex flex-col gap-4">
                    <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-4 w-full">
                        <div class="flex-1 min-w-0">
                            <label for="mes" class="block text-xs font-semibold text-gray-700 mb-1">Mes</label>
                            <select name="mes" id="mes" class="w-full rounded border-gray-300 focus:ring-blue-400">
                                <option value="">Todos</option>
                                @foreach(['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'] as $num => $nombre)
                                    <option value="{{ $num }}" {{ request('mes') == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 min-w-0">
                            <label for="gestion" class="block text-xs font-semibold text-gray-700 mb-1">Gestión</label>
                            <select name="gestion" id="gestion" class="w-full rounded border-gray-300 focus:ring-blue-400">
                                <option value="">Todas</option>
                                @foreach($todasGestiones as $g)
                                    <option value="{{ $g->id }}" {{ request('gestion') == $g->id ? 'selected' : '' }}>{{ $g->nombre }} ({{ $g->anio }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded shadow">Consultar</button>
                    </form>
                    @if(isset($ganadoFiltrado))
                        <div class="w-full text-center sm:text-left text-lg font-bold text-blue-700">
                            Total ganado: ${{ number_format($ganadoFiltrado, 2) }}
                            @if(request('mes'))
                                <span class="text-xs text-gray-500">(Mes: {{ ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'][request('mes')] }})</span>
                            @endif
                            @if(request('gestion'))
                                <span class="text-xs text-gray-500">(Gestión: {{ optional($todasGestiones->firstWhere('id', request('gestion')))->nombre }})</span>
                            @endif
                        </div>
                    @endif
                </div>

            <!-- Tarjetas de Estadísticas Principales -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
                <!-- Total Clientes -->
                <div class="bg-white border border-gray-200 rounded-xl shadow p-6 text-gray-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total Clientes</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($totalClientes) }}</p>
                            <p class="text-xs mt-2 text-gray-400">Registrados en el sistema</p>
                        </div>
                        <div class="bg-gray-100 p-3 rounded-full">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Gestiones -->
                <div class="bg-white border border-gray-200 rounded-xl shadow p-6 text-gray-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Gestiones</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($totalGestiones) }}</p>
                            <p class="text-xs mt-2 text-gray-400">Registros totales</p>
                        </div>
                        <div class="bg-gray-100 p-3 rounded-full">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nueva sección: Promedio mensual y total de ganancias -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-xl shadow p-6 text-gray-800">
                    <h3 class="text-lg font-bold mb-2 text-gray-900">Promedio mensual de ganancias</h3>
                    <p class="text-2xl font-bold text-green-700">${{ number_format($promedioMensual, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-2">Calculado sobre los últimos 12 meses</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow p-6 text-gray-800">
                    <h3 class="text-lg font-bold mb-2 text-gray-900">Total acumulado</h3>
                    <p class="text-2xl font-bold text-blue-700">${{ number_format($totalGanado, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-2">Sumando todos los registros</p>
                </div>
            </div>

            <!-- Grid de 2 Columnas para Gráficos y Top Clientes -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
                <!-- Gráfico de Clientes por Mes (ocupa 2 columnas en desktop) -->
                <div class="xl:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Clientes Registrados (Últimos 6 Meses)</h3>
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">Tendencia</span>
                    </div>
                    <div class="space-y-4">
                        @forelse($clientesPorMes as $mes)
                            @php
                                $porcentaje = $totalClientes > 0 ? ($mes->total / $totalClientes) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($mes->mes.'-01')->format('F Y') }}</span>
                                    <span class="font-bold text-gray-900">{{ $mes->total }} clientes</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-blue-500 h-6 rounded-full flex items-center justify-end pr-3 transition-all duration-500" style="width: {{ max($porcentaje, 5) }}%">
                                        <span class="text-white text-xs font-semibold">{{ round($porcentaje, 1) }}%</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8">No hay datos de los últimos 6 meses</p>
                        @endforelse
                    </div>
                </div>

                <!-- Top 5 Clientes con más Pagos -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Top Clientes</h3>
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">Top 5</span>
                    </div>
                    <div class="space-y-4">
                        @forelse($topClientes as $index => $cliente)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="shrink-0 w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm truncate">{{ $cliente->nombre }}</p>
                                    <p class="text-xs text-gray-500">{{ $cliente->pagos_count }} pagos registrados</p>
                                </div>
                                <div class="shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center text-sm py-4">No hay datos disponibles</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top de últimos registros editados -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Últimos registros editados</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nombre</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha de edición</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($ultimosEditados as $editado)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3">{{ $editado->id }}</td>
                                    <td class="px-4 py-3">{{ $editado->nombre }}</td>
                                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($editado->updated_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">No hay registros editados recientemente</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top de gestiones recientes -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Gestiones Recientes</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Gestión</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nombre</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Año</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($gestionesRecientes as $gestion)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3">ID: {{ $gestion->id }}</td>
                                    <td class="px-4 py-3">{{ $gestion->nombre ?? 'Sin nombre' }}</td>
                                    <td class="px-4 py-3">{{ $gestion->anio ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">No hay gestiones recientes</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tarjeta de Mensaje de Bienvenida -->
            <div class="mt-6 bg-white rounded-xl shadow p-6 sm:p-8 text-gray-900 border border-gray-200">
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="bg-gray-100 p-4 rounded-full">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="text-xl sm:text-2xl font-bold mb-2">¡Bienvenido, {{ session('usuario_nombre') }}! 👋</h3>
                        <p class="text-gray-700 text-sm sm:text-base">Este es tu panel de control empresarial. Aquí puedes ver todas las métricas importantes de tu sistema en tiempo real.</p>
                    </div>
                    <div class="hidden lg:block">
                        <div class="bg-gray-100 rounded-lg px-6 py-4 text-center">
                            <p class="text-xs uppercase tracking-wide mb-1 text-gray-500">Último acceso</p>
                            <p class="text-lg font-bold text-gray-900">{{ now()->format('d/m/Y') }}</p>
                            <p class="text-sm text-gray-700">{{ now()->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            </div> <!-- Cierre del contenedor principal -->
        </main>
    </div>
</body>
</html>
