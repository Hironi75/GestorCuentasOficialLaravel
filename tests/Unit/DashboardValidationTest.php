<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * SUITE DE TESTS: DashboardValidationTest
 *
 * Validación de la función privada validateNotEmpty() del DashboardController.
 * Estos tests unitarios verifican que:
 * - La validación lanza excepciones correctamente cuando hay datos vacíos o nulos
 * - No lanza excepciones cuando los datos son válidos
 * - Funciona correctamente con múltiples campos simultáneamente
 *
 * ═══════════════════════════════════════════════════════════════════════════
 * CÓMO EJECUTAR ESTOS TESTS:
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * OPCIÓN 1: Ejecutar todos los tests de esta suite
 * ────────────────────────────────────────────────────
 * Comando:
 *   php artisan test tests/Unit/DashboardValidationTest.php
 *
 * Qué hace: Ejecuta los 5 tests unitarios de validación
 * Tiempo aproximado: 1-2 segundos
 * Salida esperada: ✓ PASSED (5 tests)
 *
 *
 * OPCIÓN 2: Ejecutar solo un test específico
 * ────────────────────────────────────────────────────
 * Ejemplo: Ejecutar TEST 1 (campo vacío)
 *   php artisan test tests/Unit/DashboardValidationTest.php --filter=it_throws_exception_when_required_field_is_empty
 *
 * Ejemplo: Ejecutar TEST 3 (datos válidos)
 *   php artisan test tests/Unit/DashboardValidationTest.php --filter=it_does_not_throw_exception_with_valid_data
 *
 * Nota: Reemplaza "filter" con el nombre exacto del método del test
 *
 *
 * OPCIÓN 3: Ejecutar con modo verbose (más detalle)
 * ────────────────────────────────────────────────────
 * Comando:
 *   php artisan test tests/Unit/DashboardValidationTest.php --verbose
 *
 * Qué hace: Muestra cada test mientras se ejecuta y más información
 * Salida: Verás el nombre de cada test: ✓ it_throws_exception_when_required_field_is_empty
 *
 *
 * OPCIÓN 4: Ejecutar todos los tests del proyecto
 * ────────────────────────────────────────────────────
 * Comando:
 *   php artisan test
 *
 * Qué hace: Ejecuta TODOS los tests (unitarios + features)
 * Esto incluirá los 5 tests unitarios + 5 tests de características = 10 tests total
 *
 *
 * OPCIÓN 5: Ejecutar con coverage (cobertura de código)
 * ────────────────────────────────────────────────────
 * Comando:
 *   php artisan test --coverage
 *
 * Qué hace: Genera reporte de qué porcentaje de código está cubierto por tests
 *
 *
 * ═══════════════════════════════════════════════════════════════════════════
 * INTERPRETACIÓN DE RESULTADOS:
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * ✓ PASSED = Test ejecutado correctamente, todas las validaciones pasaron
 * ✗ FAILED = Test falló, alguna validación no se cumplió
 * S = SKIPPED = Test salteado (no se ejecutó)
 *
 * Ejemplo de salida exitosa:
 *   ✓ Tests\Unit\DashboardValidationTest::it_throws_exception_when_required_field_is_empty
 *   ✓ Tests\Unit\DashboardValidationTest::it_throws_exception_when_required_field_is_missing
 *   ✓ Tests\Unit\DashboardValidationTest::it_does_not_throw_exception_with_valid_data
 *   ✓ Tests\Unit\DashboardValidationTest::it_validates_multiple_required_fields
 *   ✓ Tests\Unit\DashboardValidationTest::it_validates_all_fields_successfully
 *
 *   PASSED (5 tests)
 *
 *
 * ═══════════════════════════════════════════════════════════════════════════
 * REQUISITOS PREVIOS:
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 1. Estar en la carpeta raíz del proyecto:
 *    cd C:\Users\Public\PROGRAMACION\Laravel\GestorCuentasOficialLaravel
 *
 * 2. Tener PHP instalado (verificar con: php -v)
 *
 * 3. Tener Composer instalado (verificar con: composer -v)
 *
 * 4. Tener las dependencias instaladas (si no, ejecutar: composer install)
 *
 * 5. Tener la base de datos configurada (verificar .env)
 *
 */
class DashboardValidationTest extends TestCase
{
    /**
     * TEST 1: it_throws_exception_when_required_field_is_empty()
     *
     * CÓMO EJECUTAR ESTE TEST:
     * ┌─────────────────────────────────────────────────────────────────────┐
     * │ Comando:                                                             │
     * │   php artisan test tests/Unit/DashboardValidationTest.php --filter=it_throws_exception_when_required_field_is_empty
     * │                                                                     │
     * │ Resultado esperado:                                                 │
     * │   ✓ Tests\Unit\DashboardValidationTest::it_throws_exception_when_required_field_is_empty
     * │   PASSED (1 test)                                                   │
     * └─────────────────────────────────────────────────────────────────────┘
     *
     * VALIDACIÓN: Verifica que la función validateNotEmpty() lanza una excepción
     * cuando un campo requerido está vacío (string vacío '')
     *
     * ESCENARIO:
     * - Se pasa un array con un campo cuyo valor es string vacío: ['gestionId' => '']
     * - Se indica que 'gestionId' es un campo requerido
     *
     * RESULTADO ESPERADO:
     * - Debe lanzar una Exception con el mensaje "El campo 'gestionId' no puede estar vacío."
     *
     * IMPORTANCIA: Asegura que no se permiten campos críticos con valores vacíos
     *
     * @test
     */
    public function it_throws_exception_when_required_field_is_empty()
    {
        $controller = new DashboardController();

        // Usar reflexión para acceder al método privado validateNotEmpty
        $method = new \ReflectionMethod($controller, 'validateNotEmpty');
        $method->setAccessible(true);

        // Configurar que se espera una excepción
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("El campo 'gestionId' no puede estar vacío.");

        // Ejecutar: intentar validar con gestionId vacío
        $method->invoke($controller, ['gestionId' => ''], ['gestionId']);
    }

    /**
     * TEST 2: it_throws_exception_when_required_field_is_missing()
     *
     * CÓMO EJECUTAR ESTE TEST:
     * ┌─────────────────────────────────────────────────────────────────────┐
     * │ Comando:                                                             │
     * │   php artisan test tests/Unit/DashboardValidationTest.php --filter=it_throws_exception_when_required_field_is_missing
     * │                                                                     │
     * │ Resultado esperado:                                                 │
     * │   ✓ Tests\Unit\DashboardValidationTest::it_throws_exception_when_required_field_is_missing
     * │   PASSED (1 test)                                                   │
     * └─────────────────────────────────────────────────────────────────────┘
     *
     * VALIDACIÓN: Verifica que la función validateNotEmpty() lanza una excepción
     * cuando un campo requerido está completamente ausente del array
     *
     * ESCENARIO:
     * - Se pasa un array que NO contiene la clave 'gestionId': ['nombre' => 'Test']
     * - Se indica que 'gestionId' es un campo requerido
     *
     * RESULTADO ESPERADO:
     * - Debe lanzar una Exception con el mensaje "El campo 'gestionId' no puede estar vacío."
     *
     * IMPORTANCIA: Asegura que se valida la existencia de campos, no solo su valor
     *
     * @test
     */
    public function it_throws_exception_when_required_field_is_missing()
    {
        $controller = new DashboardController();

        // Usar reflexión para acceder al método privado validateNotEmpty
        $method = new \ReflectionMethod($controller, 'validateNotEmpty');
        $method->setAccessible(true);

        // Configurar que se espera una excepción
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("El campo 'gestionId' no puede estar vacío.");

        // Ejecutar: intentar validar con datos que no contienen la clave
        $method->invoke($controller, ['nombre' => 'Test'], ['gestionId']);
    }

    /**
     * TEST 3: it_does_not_throw_exception_with_valid_data()
     *
     * CÓMO EJECUTAR ESTE TEST:
     * ┌─────────────────────────────────────────────────────────────────────┐
     * │ Comando:                                                             │
     * │   php artisan test tests/Unit/DashboardValidationTest.php --filter=it_does_not_throw_exception_with_valid_data
     * │                                                                     │
     * │ Resultado esperado:                                                 │
     * │   ✓ Tests\Unit\DashboardValidationTest::it_does_not_throw_exception_with_valid_data
     * │   PASSED (1 test)                                                   │
     * └─────────────────────────────────────────────────────────────────────┘
     *
     * VALIDACIÓN: Verifica que la función validateNotEmpty() NO lanza excepción
     * cuando se proporcionan datos válidos (no vacíos, con todas las claves)
     *
     * ESCENARIO:
     * - Se pasa un array con gestionId con valor válido: ['gestionId' => '123']
     * - Se valida que 'gestionId' es requerido
     *
     * RESULTADO ESPERADO:
     * - La función debe ejecutarse sin lanzar excepciones
     * - El test pasa si no hay excepciones
     *
     * IMPORTANCIA: Asegura que datos válidos se aceptan sin problemas
     *
     * @test
     */
    public function it_does_not_throw_exception_with_valid_data()
    {
        $controller = new DashboardController();

        // Usar reflexión para acceder al método privado validateNotEmpty
        $method = new \ReflectionMethod($controller, 'validateNotEmpty');
        $method->setAccessible(true);

        // Ejecutar: validar con datos válidos (no debe lanzar excepción)
        $result = $method->invoke($controller, ['gestionId' => '123'], ['gestionId']);

        // Si llegamos aquí sin excepción, el test pasa
        $this->assertTrue(true);
    }

    /**
     * TEST 4: it_validates_multiple_required_fields()
     *
     * CÓMO EJECUTAR ESTE TEST:
     * ┌─────────────────────────────────────────────────────────────────────┐
     * │ Comando:                                                             │
     * │   php artisan test tests/Unit/DashboardValidationTest.php --filter=it_validates_multiple_required_fields
     * │                                                                     │
     * │ Resultado esperado:                                                 │
     * │   ✓ Tests\Unit\DashboardValidationTest::it_validates_multiple_required_fields
     * │   PASSED (1 test)                                                   │
     * └─────────────────────────────────────────────────────────────────────┘
     *
     * VALIDACIÓN: Verifica que la función validateNotEmpty() detecta cuando
     * alguno de múltiples campos requeridos está vacío
     *
     * ESCENARIO:
     * - Se pasan múltiples campos a validar: ['gestionId' => '123', 'name' => '', 'year' => '2026']
     * - Se requiere que gestionId, name, y year sean válidos
     * - El campo 'name' está vacío (string vacío '')
     *
     * RESULTADO ESPERADO:
     * - Debe lanzar una Exception porque 'name' está vacío
     * - El mensaje debe indicar que 'name' no puede estar vacío
     *
     * IMPORTANCIA: Asegura que la validación funciona correctamente con múltiples campos
     *
     * @test
     */
    public function it_validates_multiple_required_fields()
    {
        $controller = new DashboardController();

        // Usar reflexión para acceder al método privado validateNotEmpty
        $method = new \ReflectionMethod($controller, 'validateNotEmpty');
        $method->setAccessible(true);

        // Configurar que se espera una excepción
        $this->expectException(\Exception::class);

        // Ejecutar: validar múltiples campos donde uno está vacío
        $data = [
            'gestionId' => '123',      // Válido
            'name' => '',              // VACÍO - debe fallar
            'year' => '2026'           // Válido
        ];

        $method->invoke($controller, $data, ['gestionId', 'name', 'year']);
    }

    /**
     * TEST 5: it_validates_all_fields_successfully()
     *
     * CÓMO EJECUTAR ESTE TEST:
     * ┌─────────────────────────────────────────────────────────────────────┐
     * │ Comando:                                                             │
     * │   php artisan test tests/Unit/DashboardValidationTest.php --filter=it_validates_all_fields_successfully
     * │                                                                     │
     * │ Resultado esperado:                                                 │
     * │   ✓ Tests\Unit\DashboardValidationTest::it_validates_all_fields_successfully
     * │   PASSED (1 test)                                                   │
     * └─────────────────────────────────────────────────────────────────────┘
     *
     * VALIDACIÓN: Verifica que todos los campos múltiples con valores válidos
     * pasen la validación sin lanzar excepciones
     *
     * ESCENARIO:
     * - Se pasan múltiples campos, todos con valores válidos
     * - Datos: ['gestionId' => '123', 'name' => 'Gestión 2026', 'year' => '2026']
     *
     * RESULTADO ESPERADO:
     * - La función debe ejecutarse sin lanzar excepciones
     * - Todos los campos se consideran válidos
     *
     * IMPORTANCIA: Asegura que datos completos y válidos pasan sin problemas
     *
     * @test
     */
    public function it_validates_all_fields_successfully()
    {
        $controller = new DashboardController();

        // Usar reflexión para acceder al método privado validateNotEmpty
        $method = new \ReflectionMethod($controller, 'validateNotEmpty');
        $method->setAccessible(true);

        // Ejecutar: validar múltiples campos, todos válidos
        $data = [
            'gestionId' => '123',
            'name' => 'Gestión 2026',
            'year' => '2026'
        ];

        $result = $method->invoke($controller, $data, ['gestionId', 'name', 'year']);

        // Si llegamos aquí sin excepción, todos los campos pasaron validación
        $this->assertTrue(true);
    }
}


