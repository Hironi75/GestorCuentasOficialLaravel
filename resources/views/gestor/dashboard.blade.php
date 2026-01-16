<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/gestor.css') }}">
</head>
<body>
    <div class="gestor-main-container">
        @include('gestor.header')
        <main class="gestor-main">
            <section class="dashboard-section">
                <h2>Dashboard Sesion</h2>
                    <div>
                        <strong>Clientes registrados:</strong> {{ $totalClientes }}
                    </div>
            </section>
        </main>
    </div>
    <h1 class="block">Este es el Dashboard</h1>
</body>
</html>
