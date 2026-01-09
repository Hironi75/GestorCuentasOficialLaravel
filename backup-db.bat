@echo off
REM Script de backup automatico para la base de datos
REM Ejecutar diariamente antes de trabajar

echo.
echo ========================================
echo   BACKUP BASE DE DATOS - Gestor Cuentas
echo ========================================
echo.

REM Configuracion
set DB_NAME=bd_gestor_cuentas
set DB_USER=root
set DB_PASS=
set MYSQL_PATH=C:\xampp\mysql\bin
set BACKUP_DIR=C:\Users\Public\Tomas\Programacion\Laravel\GestorCuentas\backups

REM Crear directorio de backups si no existe
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

REM Generar nombre de archivo con fecha y hora
for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value') do set datetime=%%I
set FECHA=%datetime:~0,8%_%datetime:~8,6%
set BACKUP_FILE=%BACKUP_DIR%\backup_%FECHA%.sql

echo Creando backup...
echo Archivo: %BACKUP_FILE%
echo.

REM Ejecutar mysqldump
"%MYSQL_PATH%\mysqldump.exe" -u %DB_USER% %DB_NAME% > "%BACKUP_FILE%" 2>&1

if %ERRORLEVEL% EQU 0 (
    echo ✓ Backup creado exitosamente
    echo.
    
    REM Mostrar tamaño del archivo
    for %%A in ("%BACKUP_FILE%") do (
        echo Tamaño: %%~zA bytes
    )
    
    REM Eliminar backups antiguos (más de 7 días)
    echo.
    echo Eliminando backups antiguos (más de 7 días)...
    forfiles /P "%BACKUP_DIR%" /M backup_*.sql /D -7 /C "cmd /c del @path" 2>nul
    
    echo.
    echo ✓ Proceso completado
) else (
    echo ✗ ERROR al crear backup
    echo.
    echo Posibles causas:
    echo - MySQL no está iniciado en XAMPP
    echo - El usuario 'root' no tiene permisos
    echo - La base de datos no existe
)

echo.
echo Presiona cualquier tecla para salir...
pause >nul
