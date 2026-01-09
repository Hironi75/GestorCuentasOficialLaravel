@echo off
REM Script para restaurar un backup de la base de datos

echo.
echo ==========================================
echo   RESTAURAR BACKUP - Gestor Cuentas
echo ==========================================
echo.

set DB_NAME=bd_gestor_cuentas
set DB_USER=root
set MYSQL_PATH=C:\xampp\mysql\bin
set BACKUP_DIR=C:\Users\Public\Tomas\Programacion\Laravel\GestorCuentas\backups

echo ¡ADVERTENCIA! Este proceso sobrescribirá la base de datos actual.
echo.
echo Backups disponibles:
echo.

REM Listar backups disponibles
dir /B "%BACKUP_DIR%\backup_*.sql" 2>nul

if %ERRORLEVEL% NEQ 0 (
    echo No hay backups disponibles.
    goto :FIN
)

echo.
set /p BACKUP_FILE="Ingresa el nombre del archivo de backup: "

if not exist "%BACKUP_DIR%\%BACKUP_FILE%" (
    echo.
    echo ✗ ERROR: El archivo no existe
    goto :FIN
)

echo.
echo Restaurando backup: %BACKUP_FILE%
echo.

"%MYSQL_PATH%\mysql.exe" -u %DB_USER% %DB_NAME% < "%BACKUP_DIR%\%BACKUP_FILE%" 2>&1

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ✓ Backup restaurado exitosamente
    echo.
    echo IMPORTANTE: Ejecuta estos comandos en Laravel:
    echo   php artisan cache:clear
    echo   php artisan config:clear
) else (
    echo.
    echo ✗ ERROR al restaurar backup
)

:FIN
echo.
echo Presiona cualquier tecla para salir...
pause >nul
