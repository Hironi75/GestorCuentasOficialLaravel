<?php
</html>
</body>
    </div>

        ?>
        echo '</div>';
        echo '<p><strong>Current Path:</strong> ' . __DIR__ . '</p>';
        echo '<p><strong>Document Root:</strong> ' . $_SERVER['DOCUMENT_ROOT'] ?? 'N/A' . '</p>';
        echo '<p><strong>Server:</strong> ' . $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' . '</p>';
        echo '<p><strong>Laravel Version:</strong> ' . app()->version() . '</p>';
        echo '<p><strong>PHP Version:</strong> ' . PHP_VERSION . '</p>';
        echo '<h3>ℹ️ Información del Sistema</h3>';
        echo '<div class="step">';
        // INFORMACIÓN DEL SISTEMA

        echo '</div>';
        echo '</ol>';
        echo '<li>Configura el Document Root de tu dominio a la carpeta /public</li>';
        echo '<li>Verifica que APP_DEBUG=false en .env</li>';
        echo '<li>Si necesitas ejecutar migraciones, usa el archivo migrate.php</li>';
        echo '<li><strong>ELIMINA este archivo (setup.php) INMEDIATAMENTE</strong></li>';
        echo '<ol>';
        echo '<h3>⚠️ IMPORTANTE - SEGURIDAD</h3>';
        echo '<div class="alert alert-warning">';
        // PRÓXIMOS PASOS

        echo '</div>';
        }
            echo '<p>Tu aplicación está lista para funcionar.</p>';
            echo '<h2 style="color: #27ae60;">✅ Setup Completado Exitosamente</h2>';
        } else {
            echo '<p>Revisa los mensajes anteriores y corrige los problemas.</p>';
            echo '<h2 style="color: #e74c3c;">❌ Setup Completado con Errores</h2>';
        if ($hasErrors) {
        echo '<div style="margin-top: 30px; padding: 20px; background: ' . ($hasErrors ? '#fadbd8' : '#d4edda') . '; border-radius: 5px;">';
        // RESUMEN FINAL

        echo '</div>';
        }
            $hasErrors = true;
            echo '</div>';
            echo '- DB_PASSWORD';
            echo '- DB_USERNAME<br>';
            echo '- DB_DATABASE<br>';
            echo '- DB_HOST<br>';
            echo '<strong>ACCIÓN REQUERIDA:</strong> Verifica las credenciales en .env:<br>';
            echo '<div class="alert alert-danger">';
            echo '<p class="error">Error: ' . $e->getMessage() . '</p>';
            echo '<p class="error">✗ No se puede conectar a la base de datos</p>';
        } catch (Exception $e) {
            }
                echo '<p class="warning">⚠ Tabla migrations no existe. Necesitas ejecutar migraciones.</p>';
            } catch (Exception $e) {
                echo '<p class="info">ℹ Para ejecutar migraciones, usa el archivo migrate.php</p>';
                echo '<p><strong>Migraciones ejecutadas:</strong> ' . $migrations . '</p>';
                $migrations = DB::table('migrations')->count();
            try {
            // Ver estado de migraciones

            echo '<p><strong>Base de datos:</strong> ' . DB::connection()->getDatabaseName() . '</p>';
            echo '<p><strong>Driver:</strong> ' . DB::connection()->getDriverName() . '</p>';
            echo '<p class="success">✓ Conexión a base de datos exitosa</p>';
            $pdo = DB::connection()->getPdo();
        try {
        echo '<h3>7. Verificando conexión a base de datos</h3>';
        echo '<div class="step">';
        // PASO 7: Verificar base de datos

        }
            echo '</div>';
            }
                echo '<p class="error">✗ Error en optimización: ' . $e->getMessage() . '</p>';
            } catch (Exception $e) {
                echo '<p class="success">✓ Vistas cacheadas</p>';
                $kernel->call('view:cache');

                echo '<p class="success">✓ Rutas cacheadas</p>';
                $kernel->call('route:cache');

                echo '<p class="success">✓ Configuración cacheada</p>';
                $kernel->call('config:cache');
            try {
            echo '<h3>6. Optimizando para producción</h3>';
            echo '<div class="step">';
        if (!$hasErrors && env('APP_ENV') === 'production') {
        // PASO 6: Optimizar para producción

        echo '</div>';
        }
            }
                echo '<p class="warning">⚠ ' . $e->getMessage() . '</p>';
            } else {
                echo '<p class="info">ℹ Storage link ya existe</p>';
            if (strpos($e->getMessage(), 'already exists') !== false) {
        } catch (Exception $e) {
            echo '<p class="success">✓ Storage link creado</p>';
            $kernel->call('storage:link');
        try {
        echo '<h3>5. Creando storage link</h3>';
        echo '<div class="step">';
        // PASO 5: Crear storage link

        echo '</div>';
        }
            $hasErrors = true;
            echo '<p>Ejecuta: <code>chmod -R 775 bootstrap/cache</code></p>';
            echo '<p class="error">✗ bootstrap/cache/ NO es escribible</p>';
        } else {
            echo '<p class="success">✓ bootstrap/cache/ es escribible</p>';
        if (is_writable($cachePath)) {

        }
            $hasErrors = true;
            echo '<p>Ejecuta: <code>chmod -R 775 storage</code></p>';
            echo '<p class="error">✗ storage/ NO es escribible</p>';
        } else {
            echo '<p class="success">✓ storage/ es escribible</p>';
        if (is_writable($storagePath)) {

        $cachePath = base_path('bootstrap/cache');
        $storagePath = storage_path();
        echo '<h3>4. Verificando permisos de escritura</h3>';
        echo '<div class="step">';
        // PASO 4: Verificar permisos

        echo '</div>';
        }
            $hasErrors = true;
            echo '</div>';
            echo '3. Verifica que contenga manifest.json y la carpeta assets/';
            echo '2. Sube la carpeta <code>public/build/</code> al hosting via FTP<br>';
            echo '1. En tu PC ejecuta: <code>npm run build</code><br>';
            echo '<strong>ACCIÓN REQUERIDA:</strong><br>';
            echo '<div class="alert alert-danger">';
            echo '<p class="error">✗ public/build/manifest.json NO EXISTE</p>';
        } else {
            echo '</ul>';
            }
                echo '<li>' . $key . ' → ' . $value['file'] . '</li>';
            foreach ($manifest as $key => $value) {
            echo '<ul>';
            echo '<p><strong>Assets encontrados:</strong> ' . count($manifest) . '</p>';
            $manifest = json_decode(file_get_contents($manifestPath), true);
            echo '<p class="success">✓ public/build/manifest.json EXISTE</p>';
        if (file_exists($manifestPath)) {
        $manifestPath = public_path('build/manifest.json');
        echo '<h3>3. Verificando assets compilados (Vite)</h3>';
        echo '<div class="step">';
        // PASO 3: Verificar assets de Vite

        echo '</div>';
        }
            $hasErrors = true;
            echo '</div>';
            echo '<strong>ACCIÓN REQUERIDA:</strong> Copia .env.example a .env y configúralo con las credenciales del hosting.';
            echo '<div class="alert alert-danger">';
            echo '<p class="error">✗ Archivo .env NO existe</p>';
        } else {
            }
                $hasErrors = true;
                echo '</div>';
                echo '<strong>❌ ERROR:</strong> APP_KEY no está configurada. Ejecuta: php artisan key:generate';
                echo '<div class="alert alert-danger">';
            if (!env('APP_KEY')) {

            }
                echo '</div>';
                echo '<strong>⚠️ ADVERTENCIA:</strong> APP_DEBUG está en true. Cámbialo a false en producción.';
                echo '<div class="alert alert-warning">';
            if (env('APP_DEBUG')) {

            echo '<p><strong>DB_DATABASE:</strong> ' . env('DB_DATABASE') . '</p>';
            echo '<p><strong>DB_CONNECTION:</strong> ' . env('DB_CONNECTION') . '</p>';
            echo '<p><strong>APP_URL:</strong> ' . env('APP_URL') . '</p>';
            echo '<p><strong>APP_DEBUG:</strong> ' . (env('APP_DEBUG') ? '<span class="warning">true (Cambiar a false)</span>' : '<span class="success">false</span>') . '</p>';
            echo '<p><strong>APP_ENV:</strong> ' . env('APP_ENV') . '</p>';
            echo '<p class="success">✓ Archivo .env existe</p>';
        if (file_exists(__DIR__.'/.env')) {
        echo '<h3>2. Verificando configuración (.env)</h3>';
        echo '<div class="step">';
        // PASO 2: Verificar .env

        echo '</div>';
        }
            $hasErrors = true;
            echo '<p class="error">✗ Error al limpiar cache: ' . $e->getMessage() . '</p>';
        } catch (Exception $e) {
            echo '<p class="success">✓ Cache limpiado exitosamente</p>';
            $kernel->call('route:clear');
            $kernel->call('view:clear');
            $kernel->call('cache:clear');
            $kernel->call('config:clear');
        try {
        echo '<h3>1. Limpiando cache...</h3>';
        echo '<div class="step">';
        // PASO 1: Limpiar cache

        $hasErrors = false;
        <?php

        <h1>⚙️ Setup de Producción - Gestor de Cuentas</h1>
    <div class="container">
<body>
</head>
    </style>
        }
            color: #e67e22;
            border-color: #f39c12;
            background: #fcf8e3;
        .alert-warning {
        }
            color: #1e8449;
            border-color: #27ae60;
            background: #d4edda;
        .alert-success {
        }
            color: #c0392b;
            border-color: #e74c3c;
            background: #fadbd8;
        .alert-danger {
        }
            border-left: 5px solid;
            border-radius: 5px;
            margin: 20px 0;
            padding: 15px;
        .alert {
        }
            overflow-x: auto;
            border-radius: 5px;
            padding: 15px;
            color: #ecf0f1;
            background: #2c3e50;
        pre {
        }
            color: #3498db;
        .info {
        }
            font-weight: bold;
            color: #f39c12;
        .warning {
        }
            font-weight: bold;
            color: #e74c3c;
        .error {
        }
            font-weight: bold;
            color: #27ae60;
        .success {
        }
            color: #2c3e50;
            margin-top: 0;
        .step h3 {
        }
            border-left: 4px solid #3498db;
            border-radius: 5px;
            background: #ecf0f1;
            padding: 15px;
            margin: 20px 0;
        .step {
        }
            padding-bottom: 10px;
            border-bottom: 3px solid #3498db;
            color: #2c3e50;
        h1 {
        }
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            padding: 30px;
            background: white;
        .container {
        }
            background: #f5f5f5;
            padding: 20px;
            margin: 50px auto;
            max-width: 900px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        body {
    <style>
    <title>Setup - Gestor de Cuentas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
<head>
<html lang="es">
<!DOCTYPE html>
?>

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$app = require_once __DIR__.'/bootstrap/app.php';

require __DIR__.'/vendor/autoload.php';

}
    }
        die('<h1>⚠️ ADVERTENCIA</h1><p>Este archivo es solo para producción en cPanel.</p><p>No lo ejecutes en tu ambiente local.</p>');
    if (strpos($envContent, 'APP_ENV=local') !== false) {
    $envContent = file_get_contents(__DIR__.'/.env');
if (file_exists(__DIR__.'/.env')) {
// Verificar que no esté en ambiente local

 */
 * 3. ELIMINA este archivo después de usar (SEGURIDAD)
 * 2. Visita: https://tudominio.com/setup.php
 * 1. Sube este archivo a la raíz de tu proyecto
 * USO:
 *
 * Este archivo ejecuta comandos de Artisan sin necesidad de terminal
 *
 * SETUP.PHP - Configuración inicial para cPanel
/**

