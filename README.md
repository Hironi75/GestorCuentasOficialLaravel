README técnico — GestorCuentasOficialLaravel

Resumen rápido
--------------
Este documento resume la arquitectura actual del proyecto, los módulos/componentes detectados y propuestas de mejoras arquitectónicas para aumentar la mantenibilidad, testabilidad y escalabilidad.

1) Tipo de arquitectura
-----------------------
- Es una aplicación monolítica construida sobre Laravel (PHP) con patrón MVC (Model-View-Controller).
- Uso de Eloquent ORM para acceso a datos (models: Cliente, Gestion, Usuario, User).
- Controladores HTTP en `app/Http/Controllers` gestionan la lógica de rutas y respuestas (vistas Blade y JSON para AJAX).
- Rutas definidas en `routes/web.php` (rutas públicas y protegidas con middleware `auth.check`).
- Activos públicos en `public/` y vistas Blade en `resources/views`.
- Persistencia mediante MySQL y migraciones en `database/migrations`.
- Hay uso de caché a nivel de controlador (`cache()->remember`) para algunas consultas.

2) Módulos / componentes identificados
--------------------------------------
- Modelos (Eloquent):
  - `App\Models\Cliente` — entidad principal con muchas columnas por mes (ENERO..DICIEMBRE).
  - `App\Models\Gestion` — gestión anual, relación con clientes.
  - `App\Models\Usuario` / `App\Models\User` — dos modelos de usuario; `Usuario` es usado por el controlador de autenticación personalizado.

- Controladores (en `app/Http/Controllers`):
  - `AuthController` — login/registro/session usando `Session` y `Hash`.
  - `ClienteController` — CRUD y validación inline de clientes.
  - `GestionController` — CRUD de gestiones, cambio de gestión activa y listados con caché.
  - `DashboardController` — cálculo de métricas y sumas mensuales.
  - `ExportarController` — exportación CSV/PDF basada en columnas seleccionadas.
  - `UsuarioController`, `ExportarController`, `TraspasarController`.

- Rutas: `routes/web.php` (públicas y protegidas; uso de `Route::resource` para clientes y usuarios).
- Vistas Blade: vistas para login, gestor y dashboard (en `resources/views`).
- Migraciones y seeders: tablas `clientes`, `gestiones`, `usuarios`, índices y fulltext (en `database/migrations`).
- Middleware: `app/Http/Middleware` (se usa `auth.check` en rutas).
- Assets: CSS/JS en `public/css`, `public/js` y recursos compilados por Vite.

3) Observaciones y mejoras propuestas (priorizadas)
---------------------------------------------------
A — Correcciones estructurales y buenas prácticas (alta prioridad)
- Normalizar datos de pagos: reemplazar las 12 columnas mensuales (ENERO..DICIEMBRE) por una tabla relacionada `pagos` o `importes` (columns: id, cliente_id, gestion_id, mes, importe, concepto, creado_at). Esto mejora escalabilidad, consultas y evita columnas repetitivas.
- Separar validación a Form Requests (`app/Http/Requests`) en lugar de validar en controladores. Mejora claridad y reutilización.
- Mover la lógica de negocio fuera de controladores hacia Services/Repositories (`app/Services`, `app/Repositories`). Ej.: lógica compleja de `GestionController` y `ClienteController` a `GestionService` / `ClienteService`.
- Usar Resource classes para respuestas JSON (`App\Http\Resources`) en endpoints AJAX para mantener formato consistente.

B — Autenticación y seguridad
- Usar el sistema de autenticación de Laravel (sanctum / auth scaffolding) o adaptar `User` y `Usuario` para evitar duplicidad. Favorecer `Authenticatable` y middleware `auth` en lugar de `Session` manual.
- Añadir policies y gates (`app/Policies`) para autorización por recurso.
- Evitar enviar contraseñas sin hash y asegurar hashing consistente (ya usan Hash::make en registro; revisar `UsuarioController` creación directa).

C — Mantenibilidad, pruebas y calidad
- Añadir tests automated (Feature y Unit) en `tests/` para controladores, servicios y modelos.
- Integrar análisis estático (PHPStan/Psalm) y linters (PHP-CS-Fixer) en CI.
- Centralizar manejo de excepciones y errores (custom exceptions + report) en `app/Exceptions`.

D — Rendimiento y operaciones
- Mantener índices y fulltext en migraciones; documentar claves de cache y usar claves constantes en un servicio de cache para invalidación predecible.
- Externalizar operaciones pesadas (export CSV/PDF, importaciones) a jobs/queues (`app/Jobs`) y workers (usar `queue` y `redis`/database driver en producción).

E — Arquitectura y organización recomendada de carpetas
- app/
  - Controllers/
  - Models/
  - Http/Requests/  (FormRequests)
  - Http/Resources/ (API Resources)
  - Services/       (reglas de negocio)
  - Repositories/   (acceso a datos complejos)
  - Policies/
  - Jobs/
  - Exceptions/

F — Migraciones y modelo de datos: pasos sugeridos
- Crear nueva tabla `pagos` y migración para mover/normalizar datos.
- Migrar datos existentes con un script: iterar clientes y crear registros por mes con valor y concepto.
- Eliminar columnas mensuales una vez migrado y probado.

G — Pasos prácticos inmediatos (plan de acción mínimo)
1. Extraer validaciones a FormRequests para `Cliente` y `Gestion`.
2. Extraer consultas complejas a métodos en Models (scopes) o a Repositories.
3. Implementar `app/Services/ClienteService.php` para encapsular la creación/actualización con transacción y asignación de meses.
4. Añadir tests para endpoints CRUD críticos.
5. Planificar migración de esquema mensual a tabla `pagos` en una rama protegida y con backup/restore (hay scripts .bat en repo).

Conclusión
----------
El proyecto es un monolito Laravel bien estructurado a nivel básico (Models, Controllers, Migrations, Views). Sin embargo, tiene lógica de negocio y validaciones en los controladores y un diseño de datos que complicará su evolución (columnas por mes). Recomiendo normalizar los datos mensuales, extraer lógica a capas de servicio/repository, usar FormRequests y Resources, e incorporar pruebas y análisis estático. Estos cambios mejorarán mantenibilidad, reducirá duplicación y facilitarán escalar nuevas funcionalidades.

Archivos revisados (ejemplos):
- `app/Models/Cliente.php`, `Gestion.php`, `Usuario.php`, `User.php`
- `app/Http/Controllers/AuthController.php`, `ClienteController.php`, `GestionController.php`, `DashboardController.php`, `ExportarController.php`
- `routes/web.php`
- `database/migrations/*` (índices y fulltext detectados)

Si quieres, puedo:
- Generar los FormRequests y Services iniciales (ej.: `StoreClienteRequest`, `ClienteService`).
- Crear una migración y script para normalizar los pagos mensuales con pruebas en una rama separada.



