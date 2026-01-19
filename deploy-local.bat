@echo off
echo ========================================
echo   SETUP DESARROLLO LOCAL - GESTOR CUENTAS
echo ========================================
echo.

REM Verificar si existe .env
if not exist .env (
    echo Copiando .env.example a .env...
    copy .env.example .env
    echo Generando APP_KEY...
    php artisan key:generate
)

echo [1/6] Instalando dependencias de Composer...
composer install
if %errorlevel% neq 0 (
    echo ERROR: Fallo al instalar dependencias de Composer
    pause
    exit /b 1
)

echo.
echo [2/6] Instalando dependencias de Node...
call npm install
if %errorlevel% neq 0 (
    echo ERROR: Fallo al instalar dependencias de Node
    pause
    exit /b 1
)

echo.
echo [3/6] Compilando assets...
call npm run build
if %errorlevel% neq 0 (
    echo ERROR: Fallo al compilar assets
    pause
    exit /b 1
)

echo.
echo [4/6] Limpiando cache...
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo.
echo [5/6] Creando enlace simbolico de storage...
php artisan storage:link

echo.
echo [6/6] Ejecutando migraciones...
set /p migrate="Deseas ejecutar las migraciones ahora? (s/n): "
if /i "%migrate%"=="s" (
    php artisan migrate
    set /p seed="Deseas ejecutar los seeders? (s/n): "
    if /i "!seed!"=="s" (
        php artisan db:seed
    )
)

echo.
echo ========================================
echo   SETUP COMPLETADO
echo ========================================
echo.
echo Puedes iniciar el servidor con:
echo   - iniciar-desarrollo.bat (servidor + vite dev)
echo   - iniciar-simple.bat (solo servidor)
echo   - php artisan serve
echo.
pause

