@echo off
pause >nul
echo Presiona cualquier tecla para salir...
echo.
echo Vite:    http://localhost:5173
echo Laravel: http://localhost:8000
echo.
echo ====================================
echo   Servidores iniciados!
echo ====================================
echo.

start "Vite Dev Server" cmd /k "npm run dev"
echo.
echo Iniciando Vite dev server...

timeout /t 2 /nobreak >nul

start "Laravel Server" cmd /k "php artisan serve"

echo.
echo Iniciando servidor Laravel en el puerto 8000...
echo.
echo ====================================
echo   Modo: DESARROLLO (con Vite)
echo   Iniciando Gestor de Cuentas
echo ====================================

