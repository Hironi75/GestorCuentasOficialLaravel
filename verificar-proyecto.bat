@echo off
echo ========================================
echo   VERIFICACION DE PROYECTO
echo   Gestor de Cuentas
echo ========================================
echo.

set ERRORS=0

REM Verificar PHP
echo [1/15] Verificando PHP...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP no esta instalado o no esta en PATH
    set /a ERRORS+=1
) else (
    echo [OK] PHP instalado
)

REM Verificar Composer
echo [2/15] Verificando Composer...
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Composer no esta instalado o no esta en PATH
    set /a ERRORS+=1
) else (
    echo [OK] Composer instalado
)

REM Verificar Node
echo [3/15] Verificando Node.js...
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Node.js no esta instalado o no esta en PATH
    set /a ERRORS+=1
) else (
    echo [OK] Node.js instalado
)

REM Verificar NPM
echo [4/15] Verificando NPM...
npm --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] NPM no esta instalado o no esta en PATH
    set /a ERRORS+=1
) else (
    echo [OK] NPM instalado
)

REM Verificar archivo .env
echo [5/15] Verificando archivo .env...
if not exist .env (
    echo [WARNING] Archivo .env no existe
    echo           Ejecuta deploy-local.bat para crearlo
    set /a ERRORS+=1
) else (
    echo [OK] Archivo .env existe
)

REM Verificar vendor
echo [6/15] Verificando dependencias de Composer...
if not exist vendor (
    echo [WARNING] Carpeta vendor no existe
    echo           Ejecuta: composer install
    set /a ERRORS+=1
) else (
    echo [OK] Dependencias de Composer instaladas
)

REM Verificar node_modules
echo [7/15] Verificando dependencias de Node...
if not exist node_modules (
    echo [WARNING] Carpeta node_modules no existe
    echo           Ejecuta: npm install
    set /a ERRORS+=1
) else (
    echo [OK] Dependencias de Node instaladas
)

REM Verificar build de Vite
echo [8/15] Verificando build de Vite...
if not exist public\build\manifest.json (
    echo [WARNING] Build de Vite no existe
    echo           Ejecuta: npm run build
    set /a ERRORS+=1
) else (
    echo [OK] Build de Vite existe
)

REM Verificar permisos storage
echo [9/15] Verificando carpeta storage...
if not exist storage (
    echo [ERROR] Carpeta storage no existe
    set /a ERRORS+=1
) else (
    echo [OK] Carpeta storage existe
)

REM Verificar permisos bootstrap/cache
echo [10/15] Verificando carpeta bootstrap/cache...
if not exist bootstrap\cache (
    echo [ERROR] Carpeta bootstrap/cache no existe
    set /a ERRORS+=1
) else (
    echo [OK] Carpeta bootstrap/cache existe
)

REM Verificar APP_KEY
echo [11/15] Verificando APP_KEY...
if exist .env (
    findstr /C:"APP_KEY=base64:" .env >nul 2>&1
    if %errorlevel% neq 0 (
        echo [WARNING] APP_KEY no configurada
        echo           Ejecuta: php artisan key:generate
        set /a ERRORS+=1
    ) else (
        echo [OK] APP_KEY configurada
    )
)

REM Verificar conexión base de datos
echo [12/15] Verificando conexión a base de datos...
if exist .env (
    php artisan db:show >nul 2>&1
    if %errorlevel% neq 0 (
        echo [WARNING] No se puede conectar a la base de datos
        echo           Verifica las credenciales en .env
        set /a ERRORS+=1
    ) else (
        echo [OK] Conexión a base de datos exitosa
    )
)

REM Verificar migraciones
echo [13/15] Verificando estado de migraciones...
if exist .env (
    php artisan migrate:status >nul 2>&1
    if %errorlevel% neq 0 (
        echo [WARNING] Migraciones no ejecutadas
        echo           Ejecuta: php artisan migrate
    ) else (
        echo [OK] Migraciones ejecutadas
    )
)

REM Verificar archivos de configuración
echo [14/15] Verificando archivos de configuración...
if not exist config\app.php (
    echo [ERROR] Falta config/app.php
    set /a ERRORS+=1
) else (
    echo [OK] Archivos de configuración presentes
)

REM Verificar rutas
echo [15/15] Verificando archivo de rutas...
if not exist routes\web.php (
    echo [ERROR] Falta routes/web.php
    set /a ERRORS+=1
) else (
    echo [OK] Archivo de rutas presente
)

echo.
echo ========================================
if %ERRORS% equ 0 (
    echo   VERIFICACION COMPLETADA - TODO OK
    echo ========================================
    echo.
    echo El proyecto esta listo para usar.
    echo.
    echo Puedes iniciar con:
    echo   - iniciar-desarrollo.bat ^(desarrollo con HMR^)
    echo   - iniciar-simple.bat ^(solo servidor^)
    echo   - php artisan serve ^(manual^)
) else (
    echo   VERIFICACION COMPLETADA - %ERRORS% ERRORES
    echo ========================================
    echo.
    echo Hay problemas que deben corregirse.
    echo Revisa los mensajes anteriores.
    echo.
    echo Para configurar el proyecto por primera vez:
    echo   - Ejecuta: deploy-local.bat
)
echo.
pause

