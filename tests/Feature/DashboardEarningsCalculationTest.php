<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cliente;
use App\Models\Gestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * SUITE DE TESTS: DashboardEarningsCalculationTest
 *
 * Tests de características (Feature Tests) que validan el cálculo de ganancias
 * mensuales del Dashboard. Estos tests verifican reglas de negocio críticas:
 *
 * - Las sumas de importes mensuales son correctas
 * - Las gestiones no interfieren entre sí
 * - Se manejan correctamente valores NULL
 * - Las sumas son precisas con múltiples clientes
 *
 * ⚠️  IMPORTANTE: ESTOS TESTS USAN MYSQL
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * Base de datos: MySQL
 * Uso: RefreshDatabase (crea/limpia tablas en cada test)
 * Base de datos de test: Se usa la configurada en .env o .env.testing
 * Límpieza: Los datos se limpian automáticamente después de cada test
 * Efecto: NO afecta la BD de producción
 *
 * CONFIGURACIÓN NECESARIA EN .env.testing:
 * ────────────────────────────────────────
 * DB_CONNECTION=mysql
 * DB_HOST=127.0.0.1
 * DB_PORT=3306
 * DB_DATABASE=test_gestor_cuentas  (o tu nombre de BD de test)
 * DB_USERNAME=root
 * DB_PASSWORD=
 *
 * ═══════════════════════════════════════════════════════════════════════════
 * CÓMO EJECUTAR ESTOS TESTS:
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * OPCIÓN 1: Ejecutar todos los tests de características
 * ────────────────────────────────────────────────────
 * Comando:
 *   php artisan test tests/Feature/DashboardEarningsCalculationTest.php
 *
 * Qué hace: Ejecuta los 5 tests de características para validar cálculos con MySQL
 * Tiempo aproximado: 3-5 segundos (más que unitarios porque usan BD MySQL)
 * Salida esperada: ✓ PASSED (5 tests)
 *
 * Notas:
 *   - Conecta a la base de datos MySQL de testing
 *   - Crea/limpia tablas automáticamente (RefreshDatabase)
 *   - NO modifica la BD de producción
 *
 *
 * OPCIÓN 2: Ejecutar un test específico
 * ────────────────────────────────────────────────────
 * Ejemplo: TEST 1 (sumas mensuales correctas)
 *   php artisan test tests/Feature/DashboardEarningsCalculationTest.php --filter=it_calculates_monthly_earnings_correctly
 *
 * Ejemplo: TEST 3 (gestiones no se mezclan)
 *   php artisan test tests/Feature/DashboardEarningsCalculationTest.php --filter=it_does_not_mix_earnings_from_different_gestions
 *
 *
 * OPCIÓN 3: Ejecutar con información detallada
 * ────────────────────────────────────────────────────
 * Comando:
 *   php artisan test tests/Feature/DashboardEarningsCalculationTest.php --verbose
 *
 * Qué hace: Muestra cada test y operación detalladamente con MySQL
 * Útil para: Depuración y entender exactamente qué está pasando
 *
 *
 * OPCIÓN 4: Ejecutar TODOS los tests del proyecto
 * ────────────────────────────────────────────────────
 * Comando:
 *   php artisan test
 *
 * Qué hace: Ejecuta TODOS los tests:
 *   - 5 tests unitarios (DashboardValidationTest)
 *   - 5 tests de características (este archivo)
 *   = 10 tests TOTAL
 *
 * Tiempo: ~5-8 segundos
 *
 *
 * OPCIÓN 5: Ejecutar solo tests de características
 * ────────────────────────────────────────────────────
 * Comando:
 *   php artisan test tests/Feature
 *
 * Qué hace: Ejecuta TODOS los tests en la carpeta Feature/ (en este caso, 5)
 * Nota: Si hay más tests de características en el futuro, se ejecutarán todos
 *
 *
 * OPCIÓN 6: Ejecutar con reporte de cobertura
 * ────────────────────────────────────────────────────
 * Comando:
 *   php artisan test tests/Feature/DashboardEarningsCalculationTest.php --coverage
 *
 * Qué hace: Genera un reporte de cobertura de código
 * Muestra: Qué porcentaje de código está cubierto por estos tests
 *
 *
 * ═══════════════════════════════════════════════════════════════════════════
 * IMPORTANTE: DIFERENCIA ENTRE TESTS UNITARIOS Y DE CARACTERÍSTICAS
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * TESTS UNITARIOS (tests/Unit/):
 *   - Prueban una función o clase en AISLAMIENTO
 *   - No usan base de datos real
 *   - Rápidos de ejecutar (< 1 segundo)
 *   - Ejemplo: DashboardValidationTest
 *
 * TESTS DE CARACTERÍSTICAS (tests/Feature/):
 *   - Prueban TODO EL FLUJO del sistema
 *   - USAN base de datos (con RefreshDatabase)
 *   - Más lentos (2-5 segundos cada uno)
 *   - Validan reglas de negocio completas
 *   - Ejemplo: DashboardEarningsCalculationTest (este archivo)
 *
 * IMPORTANTE: Este archivo usa RefreshDatabase, lo que significa:
 *   - Antes de cada test, la BD se limpia/resetea
 *   - Después de cada test, los datos se limpian
 *   - No afecta la BD real de producción
 *
 *
 * ═══════════════════════════════════════════════════════════════════════════
 * INTERPRETACIÓN DE RESULTADOS:
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * ✓ PASSED = Test exitoso, la validación se cumplió
 * ✗ FAILED = Test falló, algo no funcionó como se esperaba
 * S = SKIPPED = Test no se ejecutó
 *
 * Ejemplo de salida:
 *   ✓ Tests\Feature\DashboardEarningsCalculationTest::it_calculates_monthly_earnings_correctly
 *   ✓ Tests\Feature\DashboardEarningsCalculationTest::it_calculates_total_yearly_earnings_correctly
 *   ✓ Tests\Feature\DashboardEarningsCalculationTest::it_does_not_mix_earnings_from_different_gestions
 *   ✓ Tests\Feature\DashboardEarningsCalculationTest::it_returns_zero_when_no_clients_exist
 *   ✓ Tests\Feature\DashboardEarningsCalculationTest::it_handles_null_values_as_zero_in_earnings
 *
 *   PASSED (5 tests) - 4.56 seconds
 *
 *
 * ═══════════════════════════════════════════════════════════════════════════
 * REQUISITOS PREVIOS (IMPORTANTE PARA MYSQL):
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 1. Estar en la carpeta raíz del proyecto:
 *    cd C:\Users\Public\PROGRAMACION\Laravel\GestorCuentasOficialLaravel
 *
 * 2. Tener PHP instalado (verificar con: php -v)
 *
 * 3. Tener MySQL instalado y ejecutándose
 *    Verificar: mysql --version
 *    Verificar que el servicio MySQL está activo
 *
 * 4. Archivo .env.testing debe existir (Laravel lo crea automáticamente)
 *    Si no existe, crear copia de .env y renombrar a .env.testing
 *
 *    Configuración recomendada en .env.testing:
 *    DB_CONNECTION=mysql
 *    DB_HOST=127.0.0.1
 *    DB_PORT=3306
 *    DB_DATABASE=test_gestor_cuentas
 *    DB_USERNAME=root
 *    DB_PASSWORD=
 *
 * 5. Base de datos de test debe existir en MySQL:
 *    Opción A: Crear manualmente en phpMyAdmin o comando:
 *      CREATE DATABASE test_gestor_cuentas;
 *
 *    Opción B: Laravel lo hace automáticamente con RefreshDatabase
 *
 * 6. Dependencias de Composer instaladas (si no: composer install)
 *
 * 7. IMPORTANTE: RefreshDatabase
 *    - Los tests usan RefreshDatabase (trait)
 *    - Esto significa que antes de cada test:
 *      • Se limpia la BD de test
 *      • Se ejecutan las migraciones
 *      • Se crean las tablas necesarias
 *    - Después de cada test, los datos se limpian
 *    - NO afecta tu BD de producción
 *
 * 8. Permisos MySQL:
 *    El usuario MySQL (root) debe poder:
 *    - Crear/eliminar bases de datos
 *    - Crear/eliminar tablas
 *    - Insertar/modificar/eliminar datos
 *
 */
class DashboardEarningsCalculationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST 1: it_calculates_monthly_earnings_correctly()
     *
     * CÓMO EJECUTAR ESTE TEST:
     * ┌─────────────────────────────────────────────────────────────────────┐
     * │ Comando:                                                             │
     * │   php artisan test tests/Feature/DashboardEarningsCalculationTest.php --filter=it_calculates_monthly_earnings_correctly
     * │                                                                     │
     * │ Resultado esperado:                                                 │
     * │   ✓ Tests\Feature\DashboardEarningsCalculationTest::it_calculates_monthly_earnings_correctly
     * │   PASSED (1 test) - 1.23 seconds                                    │
     * └─────────────────────────────────────────────────────────────────────┘
     *
     * REGLA DE NEGOCIO VALIDADA:
     * → La suma total de importes de un mes debe ser exacta
     *
     * VALIDACIÓN:
     * - Crea una gestión
     * - Crea 3 clientes con importes diferentes para enero
     * - Calcula la suma total
     *
     * RESULTADO ESPERADO: La suma debe ser exactamente 4500.00
     *
     * @test
     */
    public function it_calculates_monthly_earnings_correctly()
    {
        // Paso 1: Crear una gestión
        $gestion = Gestion::create([
            'anio' => 2026,
            'nombre' => 'Gestión 2026',
            'activa' => true
        ]);

        // Paso 2: Crear cliente 1 con importe enero 1000.00
        Cliente::create([
            'id_cliente' => 'CLI001',
            'gestion_id' => $gestion->id,
            'nombre' => 'Cliente 1',
            'Correo_Electronico' => 'cliente1@test.com',
            'Password' => 'pass123',
            'ENERO' => 1000.00,
            'ENERO_CONCEPTO' => 'Servicios enero'
        ]);

        // Paso 3: Crear cliente 2 con importe enero 2000.00
        Cliente::create([
            'id_cliente' => 'CLI002',
            'gestion_id' => $gestion->id,
            'nombre' => 'Cliente 2',
            'Correo_Electronico' => 'cliente2@test.com',
            'Password' => 'pass123',
            'ENERO' => 2000.00,
            'ENERO_CONCEPTO' => 'Servicios enero'
        ]);

        // Paso 4: Crear cliente 3 con importe enero 1500.00
        Cliente::create([
            'id_cliente' => 'CLI003',
            'gestion_id' => $gestion->id,
            'nombre' => 'Cliente 3',
            'Correo_Electronico' => 'cliente3@test.com',
            'Password' => 'pass123',
            'ENERO' => 1500.00,
            'ENERO_CONCEPTO' => 'Servicios enero'
        ]);

        // Paso 5: Calcular total de enero (misma lógica que DashboardController)
        $totalEnero = (float) Cliente::where('gestion_id', $gestion->id)->sum('ENERO');

        // Paso 6: Verificar que la suma es correcta (1000 + 2000 + 1500 = 4500)
        $this->assertEquals(4500.00, $totalEnero, 'La suma de ganancias de enero debe ser 4500.00');
    }

    /**
     * TEST 2: it_calculates_total_yearly_earnings_correctly()
     *
     * REGLA DE NEGOCIO: La suma total de todos los meses del año debe ser exacta
     *
     * VALIDACIÓN:
     * - Crea una gestión
     * - Crea 1 cliente con importes para todos los 12 meses:
     *   • Enero: 1000.00, Febrero: 1100.00, ..., Diciembre: 2100.00
     * - Calcula la suma de todos los meses
     *
     * RESULTADO ESPERADO: La suma anual debe ser exactamente 18600.00
     *
     * IMPORTANCIA: Asegura que el total anual es la suma correcta de todos los meses
     * Esto es crítico para generar reportes anuales y análisis de ingresos
     *
     * @test
     */
    public function it_calculates_total_yearly_earnings_correctly()
    {
        // Paso 1: Crear una gestión
        $gestion = Gestion::create([
            'anio' => 2026,
            'nombre' => 'Gestión 2026',
            'activa' => true
        ]);

        // Paso 2: Crear cliente con importes para todos los 12 meses
        Cliente::create([
            'id_cliente' => 'CLI001',
            'gestion_id' => $gestion->id,
            'nombre' => 'Cliente Test',
            'Correo_Electronico' => 'test@test.com',
            'Password' => 'pass123',
            'ENERO' => 1000.00,
            'FEBRERO' => 1100.00,
            'MARZO' => 1200.00,
            'ABRIL' => 1300.00,
            'MAYO' => 1400.00,
            'JUNIO' => 1500.00,
            'JULIO' => 1600.00,
            'AGOSTO' => 1700.00,
            'SEPTIEMBRE' => 1800.00,
            'OCTUBRE' => 1900.00,
            'NOVIEMBRE' => 2000.00,
            'DICIEMBRE' => 2100.00
        ]);

        // Paso 3: Mapeo de meses (igual que en DashboardController)
        $monthColumns = [
            1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
            5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
            9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
        ];

        // Paso 4: Calcular suma total de todos los meses
        $totalEarnings = 0.0;
        foreach ($monthColumns as $monthColumn) {
            $monthTotal = (float) Cliente::where('gestion_id', $gestion->id)->sum($monthColumn);
            $totalEarnings += $monthTotal;
        }

        // Paso 5: Total esperado = 1000+1100+1200+1300+1400+1500+1600+1700+1800+1900+2000+2100
        $expectedTotal = 18600.00;

        // Paso 6: Verificar que el total anual es correcto
        $this->assertEquals($expectedTotal, $totalEarnings, 'El total anual de ganancias debe ser 18600.00');
    }

    /**
     * TEST 3: it_does_not_mix_earnings_from_different_gestions()
     *
     * REGLA DE NEGOCIO: Los clientes de una gestión NO deben contabilizarse
     * en los cálculos de otra gestión
     *
     * VALIDACIÓN:
     * - Crea gestión 2025 con cliente que tiene 5000.00 en enero
     * - Crea gestión 2026 con cliente que tiene 3000.00 en enero
     * - Calcula solo para gestión 2026
     * - Verifica que se cuenta solo 3000.00, sin incluir los 5000 de 2025
     *
     * RESULTADO ESPERADO: Suma de 2026 debe ser 3000.00 exactamente
     *
     * IMPORTANCIA: Asegura que los datos de gestiones no se mezclan
     * Esto es crítico para que cada gestión tenga datos independientes y precisos
     *
     * @test
     */
    public function it_does_not_mix_earnings_from_different_gestions()
    {
        // Paso 1: Crear gestión 2025 (inactiva)
        $gestion2025 = Gestion::create([
            'anio' => 2025,
            'nombre' => 'Gestión 2025',
            'activa' => false
        ]);

        // Paso 2: Crear gestión 2026 (activa)
        $gestion2026 = Gestion::create([
            'anio' => 2026,
            'nombre' => 'Gestión 2026',
            'activa' => true
        ]);

        // Paso 3: Crear cliente en gestión 2025 con 5000.00 en enero
        Cliente::create([
            'id_cliente' => 'CLI001',
            'gestion_id' => $gestion2025->id,
            'nombre' => 'Cliente Antigua',
            'ENERO' => 5000.00
        ]);

        // Paso 4: Crear cliente en gestión 2026 con 3000.00 en enero
        Cliente::create([
            'id_cliente' => 'CLI002',
            'gestion_id' => $gestion2026->id,
            'nombre' => 'Cliente Nueva',
            'ENERO' => 3000.00
        ]);

        // Paso 5: Calcular SOLO para gestión 2026
        $total2026 = (float) Cliente::where('gestion_id', $gestion2026->id)->sum('ENERO');

        // Paso 6: Verificar que NO incluye los 5000 de 2025, solo 3000 de 2026
        $this->assertEquals(3000.00, $total2026, 'Gestión 2026 debe sumar solo 3000.00, sin incluir datos de 2025');
    }

    /**
     * TEST 4: it_returns_zero_when_no_clients_exist()
     *
     * REGLA DE NEGOCIO: Si una gestión no tiene clientes, el total debe ser 0
     *
     * VALIDACIÓN:
     * - Crea una gestión vacía (sin clientes)
     * - Intenta calcular el total de enero
     *
     * RESULTADO ESPERADO: La suma debe ser 0.00
     *
     * IMPORTANCIA: Asegura que el cálculo maneja correctamente el caso sin datos
     * Evita valores nulos o resultados impredecibles cuando no hay clientes
     *
     * @test
     */
    public function it_returns_zero_when_no_clients_exist()
    {
        // Paso 1: Crear gestión vacía (sin clientes)
        $gestion = Gestion::create([
            'anio' => 2026,
            'nombre' => 'Gestión Vacía',
            'activa' => true
        ]);

        // Paso 2: Calcular total de enero sin clientes
        $totalEnero = (float) Cliente::where('gestion_id', $gestion->id)->sum('ENERO');

        // Paso 3: Verificar que el resultado es 0
        $this->assertEquals(0.0, $totalEnero, 'Sin clientes, el total debe ser 0.00');
    }

    /**
     * TEST 5: it_handles_null_values_as_zero_in_earnings()
     *
     * REGLA DE NEGOCIO: Los campos NULL o vacíos en importes deben contabilizarse como 0
     *
     * VALIDACIÓN:
     * - Crea cliente con algunos campos en NULL:
     *   • ENERO: NULL
     *   • FEBRERO: 1000.00
     *   • MARZO: NULL
     * - Calcula suma de cada mes
     *
     * RESULTADO ESPERADO:
     * - ENERO total = 0.00 (NULL cuenta como 0)
     * - FEBRERO total = 1000.00 (tiene valor)
     * - MARZO total = 0.00 (NULL cuenta como 0)
     *
     * IMPORTANCIA: Asegura que se manejan correctamente valores NULL
     * Esto previene errores en cálculos cuando hay campos vacíos
     *
     * @test
     */
    public function it_handles_null_values_as_zero_in_earnings()
    {
        // Paso 1: Crear gestión
        $gestion = Gestion::create([
            'anio' => 2026,
            'nombre' => 'Gestión 2026',
            'activa' => true
        ]);

        // Paso 2: Crear cliente con valores NULL en algunos meses
        Cliente::create([
            'id_cliente' => 'CLI001',
            'gestion_id' => $gestion->id,
            'nombre' => 'Cliente Con Nulls',
            'ENERO' => null,           // NULL
            'FEBRERO' => 1000.00,      // Valor
            'MARZO' => null            // NULL
        ]);

        // Paso 3: Calcular totales para cada mes
        $totalEnero = (float) Cliente::where('gestion_id', $gestion->id)->sum('ENERO');
        $totalFebrero = (float) Cliente::where('gestion_id', $gestion->id)->sum('FEBRERO');
        $totalMarzo = (float) Cliente::where('gestion_id', $gestion->id)->sum('MARZO');

        // Paso 4: Verificar que NULL se cuenta como 0
        $this->assertEquals(0.0, $totalEnero, 'NULL en ENERO debe contar como 0.00');

        // Paso 5: Verificar que el valor se cuenta correctamente
        $this->assertEquals(1000.00, $totalFebrero, 'FEBRERO debe ser 1000.00');

        // Paso 6: Verificar que NULL en marzo también es 0
        $this->assertEquals(0.0, $totalMarzo, 'NULL en MARZO debe contar como 0.00');
    }
}

