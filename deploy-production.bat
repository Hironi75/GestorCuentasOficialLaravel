@echo off
echo ========================================
echo   DEPLOY A PRODUCCION - GESTOR CUENTAS
echo ========================================
echo.

REM Verificar si existe .env
if not exist .env (
    echo ERROR: No existe archivo .env
    echo Copia .env.production a .env y configuralo primero
    pause
    exit /b 1
)

echo [1/7] Actualizando dependencias de Composer...
composer install --optimize-autoloader --no-dev
if %errorlevel% neq 0 (
    echo ERROR: Fallo al instalar dependencias de Composer
    pause
    exit /b 1
)

echo.
echo [2/7] Instalando dependencias de Node...
call npm install
if %errorlevel% neq 0 (
    echo ERROR: Fallo al instalar dependencias de Node
    pause
    exit /b 1
)

echo.
echo [3/7] Compilando assets para produccion...
call npm run build
if %errorlevel% neq 0 (
    echo ERROR: Fallo al compilar assets
    pause
    exit /b 1
)

echo.
echo [4/7] Limpiando cache de Laravel...
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo.
echo [5/7] Optimizando Laravel...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo.
echo [6/7] Ejecutando migraciones...
set /p migrate="Deseas ejecutar las migraciones? (s/n): "
if /i "%migrate%"=="s" (
    php artisan migrate --force
)

echo.
echo [7/7] Optimizando autoload...
composer dump-autoload --optimize

echo.
echo ========================================
echo   DEPLOY COMPLETADO EXITOSAMENTE
echo ========================================
echo.
echo IMPORTANTE:
echo - Verifica los permisos de storage y bootstrap/cache
echo - Asegurate de que APP_DEBUG=false en .env
echo - Verifica la configuracion de la base de datos
echo - Configura tu servidor web (Apache/Nginx)
echo.
pause

