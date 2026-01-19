<?php
/**
 * MIGRATE.PHP - Ejecutar migraciones sin terminal
 *
 * Este archivo ejecuta las migraciones de la base de datos
 *
 * USO:
 * 1. Asegúrate de haber ejecutado setup.php primero
 * 2. Sube este archivo a la raíz de tu proyecto
 * 3. Visita: https://tudominio.com/migrate.php
 * 4. ELIMINA este archivo después de usar (SEGURIDAD)
 */

// Verificar que no esté en ambiente local
if (file_exists(__DIR__.'/.env')) {
    $envContent = file_get_contents(__DIR__.'/.env');
    if (strpos($envContent, 'APP_ENV=local') !== false) {
        die('<h1>⚠️ ADVERTENCIA</h1><p>Este archivo es solo para producción en cPanel.</p><p>No lo ejecutes en tu ambiente local.</p>');
    }
}

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migraciones - Gestor de Cuentas</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        .step {
            margin: 20px 0;
            padding: 15px;
            background: #ecf0f1;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
        .success {
            color: #27ae60;
            font-weight: bold;
        }
        .error {
            color: #e74c3c;
            font-weight: bold;
        }
        .warning {
            color: #f39c12;
            font-weight: bold;
        }
        pre {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            white-space: pre-wrap;
        }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 5px solid;
        }
        .alert-danger {
            background: #fadbd8;
            border-color: #e74c3c;
            color: #c0392b;
        }
        .alert-success {
            background: #d4edda;
            border-color: #27ae60;
            color: #1e8449;
        }
        .alert-warning {
            background: #fcf8e3;
            border-color: #f39c12;
            color: #e67e22;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #3498db;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Migraciones de Base de Datos</h1>

        <?php
        $hasErrors = false;

        // Verificar conexión
        echo '<div class="step">';
        echo '<h3>1. Verificando conexión a base de datos</h3>';
        try {
            $pdo = DB::connection()->getPdo();
            echo '<p class="success">✓ Conexión exitosa</p>';
            echo '<p><strong>Base de datos:</strong> ' . DB::connection()->getDatabaseName() . '</p>';
        } catch (Exception $e) {
            echo '<p class="error">✗ Error de conexión: ' . $e->getMessage() . '</p>';
            echo '<div class="alert alert-danger">';
            echo '<strong>No se puede continuar sin conexión a la base de datos.</strong><br>';
            echo 'Verifica las credenciales en .env';
            echo '</div>';
            $hasErrors = true;
        }
        echo '</div>';

        if (!$hasErrors) {
            // Mostrar estado ANTES de migrar
            echo '<div class="step">';
            echo '<h3>2. Estado actual de migraciones</h3>';
            try {
                ob_start();
                $kernel->call('migrate:status');
                $output = ob_get_clean();

                echo '<pre>' . htmlspecialchars($output) . '</pre>';
            } catch (Exception $e) {
                echo '<p class="warning">⚠ No se puede obtener estado: ' . $e->getMessage() . '</p>';
                echo '<p>Probablemente no hay migraciones ejecutadas aún.</p>';
            }
            echo '</div>';

            // Ejecutar migraciones
            echo '<div class="step">';
            echo '<h3>3. Ejecutando migraciones</h3>';
            try {
                ob_start();
                $exitCode = $kernel->call('migrate', ['--force' => true]);
                $output = ob_get_clean();

                if ($exitCode === 0) {
                    echo '<p class="success">✓ Migraciones ejecutadas exitosamente</p>';
                    echo '<pre>' . htmlspecialchars($output) . '</pre>';
                } else {
                    echo '<p class="error">✗ Error al ejecutar migraciones (Exit code: ' . $exitCode . ')</p>';
                    echo '<pre>' . htmlspecialchars($output) . '</pre>';
                    $hasErrors = true;
                }
            } catch (Exception $e) {
                echo '<p class="error">✗ Error: ' . $e->getMessage() . '</p>';
                echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
                $hasErrors = true;
            }
            echo '</div>';

            // Mostrar estado DESPUÉS de migrar
            echo '<div class="step">';
            echo '<h3>4. Estado final de migraciones</h3>';
            try {
                ob_start();
                $kernel->call('migrate:status');
                $output = ob_get_clean();

                echo '<pre>' . htmlspecialchars($output) . '</pre>';

                // Contar migraciones
                $migrations = DB::table('migrations')->count();
                echo '<p><strong>Total de migraciones ejecutadas:</strong> ' . $migrations . '</p>';
            } catch (Exception $e) {
                echo '<p class="error">✗ Error al obtener estado: ' . $e->getMessage() . '</p>';
            }
            echo '</div>';

            // Verificar tablas creadas
            echo '<div class="step">';
            echo '<h3>5. Tablas creadas en la base de datos</h3>';
            try {
                $tables = DB::select('SHOW TABLES');
                echo '<table>';
                echo '<thead><tr><th>#</th><th>Nombre de Tabla</th></tr></thead>';
                echo '<tbody>';
                $i = 1;
                foreach ($tables as $table) {
                    $tableName = array_values((array)$table)[0];
                    echo '<tr><td>' . $i++ . '</td><td>' . $tableName . '</td></tr>';
                }
                echo '</tbody>';
                echo '</table>';
                echo '<p class="success">✓ Total de tablas: ' . count($tables) . '</p>';
            } catch (Exception $e) {
                echo '<p class="error">✗ No se pueden listar las tablas: ' . $e->getMessage() . '</p>';
            }
            echo '</div>';
        }

        // Opción de seeders
        if (!$hasErrors) {
            echo '<div class="step">';
            echo '<h3>6. Seeders (Opcional)</h3>';
            echo '<p>Si deseas ejecutar los seeders (datos de prueba), crea un archivo <code>seed.php</code> similar a este.</p>';
            echo '<p class="warning">⚠️ Ten cuidado con seeders en producción, pueden sobrescribir datos.</p>';
            echo '</div>';
        }

        // RESUMEN FINAL
        echo '<div style="margin-top: 30px; padding: 20px; background: ' . ($hasErrors ? '#fadbd8' : '#d4edda') . '; border-radius: 5px;">';
        if ($hasErrors) {
            echo '<h2 style="color: #e74c3c;">❌ Migraciones Completadas con Errores</h2>';
            echo '<p>Revisa los mensajes anteriores para identificar el problema.</p>';
        } else {
            echo '<h2 style="color: #27ae60;">✅ Migraciones Completadas Exitosamente</h2>';
            echo '<p>Tu base de datos está lista y actualizada.</p>';
        }
        echo '</div>';

        // ADVERTENCIA DE SEGURIDAD
        echo '<div class="alert alert-danger">';
        echo '<h3>⚠️ IMPORTANTE - SEGURIDAD</h3>';
        echo '<ol>';
        echo '<li><strong>ELIMINA este archivo (migrate.php) INMEDIATAMENTE</strong></li>';
        echo '<li>Este archivo permite ejecutar comandos en tu base de datos</li>';
        echo '<li>Dejarlo accesible es un riesgo de seguridad</li>';
        echo '</ol>';
        echo '<p>Para eliminarlo, usa el File Manager de cPanel o FTP.</p>';
        echo '</div>';

        // Información adicional
        echo '<div class="step">';
        echo '<h3>ℹ️ Próximos Pasos</h3>';
        echo '<ol>';
        echo '<li>Elimina este archivo (migrate.php)</li>';
        echo '<li>Elimina también setup.php si no lo has hecho</li>';
        echo '<li>Verifica que tu sitio funciona: <a href="/" target="_blank">Ir al sitio</a></li>';
        echo '<li>Prueba el login y las funcionalidades</li>';
        echo '</ol>';
        echo '</div>';
        ?>

    </div>
</body>
</html>

