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
        <main class="gestor-main p-4 sm:p-6 md:p-8">
            <section class="max-w-4xl mx-auto">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6">Dashboard</h2>

                <!-- Tarjetas de estadísticas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Tarjeta de clientes -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Clientes Registrados</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalClientes }}</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje de bienvenida -->
                <div class="mt-8 bg-gradient-to-r from-slate-700 to-slate-800 rounded-xl p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">¡Bienvenido, {{ session('usuario_nombre') }}!</h3>
                    <p class="text-slate-300 text-sm">Este es tu panel de control. Desde aquí puedes gestionar todas tus cuentas.</p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
