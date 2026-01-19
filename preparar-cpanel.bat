@echo off
echo ========================================
echo   PREPARAR PROYECTO PARA cPANEL
echo   (Sin acceso a NPM en el hosting)
echo ========================================
echo.

REM Verificar que estamos en el directorio correcto
if not exist "artisan" (
    echo ERROR: No se encuentra el archivo artisan
    echo Asegurate de estar en el directorio raiz del proyecto
    pause
    exit /b 1
)

echo [1/6] Compilando assets para produccion...
call npm run build
if %errorlevel% neq 0 (
    echo ERROR: Fallo al compilar assets
    pause
    exit /b 1
)
echo       - Assets compilados exitosamente
echo.

echo [2/6] Verificando archivos criticos...

REM Verificar manifest.json
if not exist "public\build\manifest.json" (
    echo ERROR: No existe public\build\manifest.json
    pause
    exit /b 1
)
echo       - public/build/manifest.json: OK

REM Contar archivos en assets
for /f %%A in ('dir /b /a-d "public\build\assets\*" 2^>nul ^| find /c /v ""') do set ASSET_COUNT=%%A
echo       - public/build/assets/: %ASSET_COUNT% archivos

if %ASSET_COUNT% lss 2 (
    echo ERROR: Faltan archivos en public/build/assets/
    pause
    exit /b 1
)
echo.

echo [3/6] Instalando dependencias de Composer (produccion)...
composer install --optimize-autoloader --no-dev --no-interaction
if %errorlevel% neq 0 (
    echo ERROR: Fallo al instalar dependencias
    pause
    exit /b 1
)
echo       - Dependencias instaladas
echo.

echo [4/6] Optimizando autoload...
composer dump-autoload --optimize
echo       - Autoload optimizado
echo.

echo [5/6] Verificando archivos de setup...
if exist "setup.php" (
    echo       - setup.php: OK
) else (
    echo ERROR: Falta setup.php
    pause
    exit /b 1
)

if exist "migrate.php" (
    echo       - migrate.php: OK
) else (
    echo ERROR: Falta migrate.php
    pause
    exit /b 1
)
echo.

echo [6/6] Verificando .gitignore...
findstr /C:"# /public/build" .gitignore >nul
if %errorlevel% equ 0 (
    echo       - .gitignore configurado para incluir public/build/: OK
) else (
    echo ADVERTENCIA: /public/build no esta comentado en .gitignore
    echo              Esto significa que public/build/ podria no subirse al repo
)
echo.

echo ========================================
echo   PREPARACION COMPLETADA
echo ========================================
echo.
echo ARCHIVOS LISTOS:
echo   - public/build/manifest.json
echo   - public/build/assets/ (%ASSET_COUNT% archivos)
echo   - vendor/ (dependencias PHP)
echo   - setup.php
echo   - migrate.php
echo.
echo AHORA PUEDES:
echo.
echo   1. SUBIR AL REPOSITORIO (Git)
echo      git add .
echo      git add public/build/ -f
echo      git commit -m "Assets compilados para cPanel"
echo      git push origin main
echo.
echo   2. EN EL HOSTING (cPanel):
echo      a. Git Pull o subir via FTP
echo      b. Crear .env con credenciales del hosting
echo      c. Visitar: https://tudominio.com/setup.php
echo      d. Visitar: https://tudominio.com/migrate.php
echo      e. ELIMINAR setup.php y migrate.php
echo.
echo IMPORTANTE:
echo   - public/build/ debe estar en el hosting
echo   - vendor/ debe estar en el hosting
echo   - .env debe configurarse con credenciales del hosting
echo.
pause

