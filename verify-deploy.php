#!/usr/bin/env php
<?php
/**
 * Script de verificación pre-deploy
 * Verifica que no haya problemas que puedan causar corrupción
 */

echo "🔍 VERIFICACIÓN PRE-DEPLOY - Gestor de Cuentas\n";
echo "================================================\n\n";

$errors = 0;
$warnings = 0;

// 1. Verificar .env
echo "1️⃣  Verificando archivo .env...\n";
if (file_exists('.env')) {
    $env = file_get_contents('.env');
    
    // Verificar SESSION_DRIVER
    if (strpos($env, 'SESSION_DRIVER=file') !== false) {
        echo "   ✅ SESSION_DRIVER=file (correcto)\n";
    } elseif (strpos($env, 'SESSION_DRIVER=database') !== false) {
        echo "   ⚠️  SESSION_DRIVER=database (puede causar corrupción)\n";
        $warnings++;
    }
    
    // Verificar CACHE_STORE
    if (strpos($env, 'CACHE_STORE=file') !== false) {
        echo "   ✅ CACHE_STORE=file (correcto)\n";
    } elseif (strpos($env, 'CACHE_STORE=database') !== false) {
        echo "   ⚠️  CACHE_STORE=database (puede causar corrupción)\n";
        $warnings++;
    }
    
    // Verificar QUEUE_CONNECTION
    if (strpos($env, 'QUEUE_CONNECTION=sync') !== false) {
        echo "   ✅ QUEUE_CONNECTION=sync (correcto)\n";
    } elseif (strpos($env, 'QUEUE_CONNECTION=database') !== false) {
        echo "   ⚠️  QUEUE_CONNECTION=database (no recomendado para XAMPP)\n";
        $warnings++;
    }
    
    // Verificar DB_CONNECTION
    if (strpos($env, 'DB_CONNECTION=mysql') !== false) {
        echo "   ✅ DB_CONNECTION=mysql\n";
    } else {
        echo "   ❌ DB_CONNECTION no es mysql\n";
        $errors++;
    }
} else {
    echo "   ❌ Archivo .env no encontrado\n";
    $errors++;
}

echo "\n2️⃣  Verificando controladores...\n";

// 2. Verificar que los controladores usan DB::beginTransaction
$controllers = [
    'app/Http/Controllers/ClienteController.php',
    'app/Http/Controllers/GestionController.php',
    'app/Http/Controllers/TraspasarController.php',
    'app/Http/Controllers/UsuarioController.php',
];

foreach ($controllers as $controller) {
    if (file_exists($controller)) {
        $content = file_get_contents($controller);
        $hasTransaction = strpos($content, 'DB::beginTransaction') !== false || 
                          strpos($content, '\\DB::beginTransaction') !== false;
        $hasTryCatch = strpos($content, 'try {') !== false;
        
        $controllerName = basename($controller);
        if ($hasTransaction && $hasTryCatch) {
            echo "   ✅ $controllerName usa transacciones y try-catch\n";
        } elseif ($hasTransaction) {
            echo "   ⚠️  $controllerName usa transacciones pero falta try-catch\n";
            $warnings++;
        } else {
            echo "   ❌ $controllerName NO usa transacciones\n";
            $errors++;
        }
    }
}

// 3. Verificar ExportarController
echo "\n3️⃣  Verificando ExportarController...\n";
if (file_exists('app/Http/Controllers/ExportarController.php')) {
    $content = file_get_contents('app/Http/Controllers/ExportarController.php');
    
    // Verificar que NO cierre php://output
    if (strpos($content, "fclose(\$file)") !== false && 
        strpos($content, "php://output") !== false) {
        echo "   ⚠️  ADVERTENCIA: ExportarController cierra php://output (puede causar problemas)\n";
        $warnings++;
    } else {
        echo "   ✅ ExportarController maneja streams correctamente\n";
    }
}

// 4. Verificar permisos de storage
echo "\n4️⃣  Verificando permisos...\n";
if (is_writable('storage/framework/sessions')) {
    echo "   ✅ storage/framework/sessions tiene permisos de escritura\n";
} else {
    echo "   ⚠️  storage/framework/sessions no tiene permisos de escritura\n";
    $warnings++;
}

if (is_writable('storage/logs')) {
    echo "   ✅ storage/logs tiene permisos de escritura\n";
} else {
    echo "   ❌ storage/logs no tiene permisos de escritura\n";
    $errors++;
}

// 5. Verificar composer.lock
echo "\n5️⃣  Verificando dependencias...\n";
if (file_exists('composer.lock')) {
    echo "   ✅ composer.lock existe\n";
} else {
    echo "   ❌ composer.lock no existe - ejecutar 'composer install'\n";
    $errors++;
}

// 6. Verificar APP_KEY
echo "\n6️⃣  Verificando APP_KEY...\n";
if (file_exists('.env')) {
    $env = file_get_contents('.env');
    if (strpos($env, 'APP_KEY=base64:') !== false) {
        echo "   ✅ APP_KEY está configurada\n";
    } else {
        echo "   ❌ APP_KEY no está configurada - ejecutar 'php artisan key:generate'\n";
        $errors++;
    }
}

// Resumen
echo "\n================================================\n";
echo "📊 RESUMEN\n";
echo "================================================\n";
echo "❌ Errores críticos: $errors\n";
echo "⚠️  Advertencias: $warnings\n";

if ($errors === 0 && $warnings === 0) {
    echo "\n✅ TODO CORRECTO - Listo para deploy\n";
    exit(0);
} elseif ($errors === 0) {
    echo "\n⚠️  HAY ADVERTENCIAS - Revisar antes de deploy\n";
    exit(0);
} else {
    echo "\n❌ HAY ERRORES CRÍTICOS - NO deployar hasta corregir\n";
    exit(1);
}
