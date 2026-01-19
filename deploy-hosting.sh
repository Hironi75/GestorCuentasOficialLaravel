#!/bin/bash
echo ""
echo "  Estado migraciones: php artisan migrate:status"
echo "  Ver rutas:          php artisan route:list"
echo "  Limpiar cache:      php artisan cache:clear"
echo "  Ver logs:           tail -f storage/logs/laravel.log"
echo "COMANDOS ÚTILES:"
echo ""
echo "3. Si hay problemas con .env, edítalo: nano .env"
echo "2. Verifica los logs si hay errores: tail -f storage/logs/laravel.log"
echo "1. Verifica que el sitio funciona: http://tudominio.com"
echo "PRÓXIMOS PASOS:"
echo ""
echo "✓ Optimizaciones aplicadas"
echo "✓ Assets compilados"
echo "✓ Dependencias instaladas"
echo "✓ Código actualizado"
echo ""
echo "========================================"
echo "  DEPLOYMENT COMPLETADO"
echo "========================================"
echo ""

fi
    echo "⚠️  bootstrap/cache/ NO tiene permisos de escritura"
else
    echo "✓ bootstrap/cache/ tiene permisos de escritura"
if [ -w "bootstrap/cache" ]; then

fi
    echo "⚠️  storage/ NO tiene permisos de escritura"
else
    echo "✓ storage/ tiene permisos de escritura"
if [ -w "storage" ]; then
# Verificar permisos

fi
    echo "❌ FALTA: public/build/assets"
else
    echo "✓ Assets compilados: $ASSET_COUNT archivos"
    ASSET_COUNT=$(ls -1 public/build/assets | wc -l)
if [ -d "public/build/assets" ]; then
# Verificar assets compilados

fi
    echo "❌ FALTA: public/build/manifest.json"
else
    echo "✓ public/build/manifest.json existe"
if [ -f "public/build/manifest.json" ]; then
# Verificar manifest.json

echo "Verificando archivos críticos..."

echo ""
echo "========================================"
echo "  VERIFICACIONES FINALES"
echo "========================================"
# Verificaciones finales

echo ""
echo "✓ Optimización completada"
composer dump-autoload --optimize
php artisan view:cache
php artisan route:cache
php artisan config:cache
echo "[10/10] Optimizando para producción..."
# 10. Optimizar para producción

echo ""
fi
    echo "⊘ Migraciones omitidas"
else
    fi
        echo "✓ Migraciones ejecutadas"
    else
        echo "   Verifica la configuración de la base de datos en .env"
        echo "⚠️  ADVERTENCIA: Error al ejecutar migraciones"
    if [ $? -ne 0 ]; then
    php artisan migrate --force
if [[ $REPLY =~ ^[SsYy]$ ]]; then
echo ""
read -p "¿Deseas ejecutar las migraciones? (s/n): " -n 1 -r
echo "[9/10] Ejecutando migraciones..."
# 9. Ejecutar migraciones

echo ""
echo "✓ Symlink creado"
php artisan storage:link
echo "[8/10] Creando symlink de storage..."
# 8. Crear symlink de storage

echo ""
echo "✓ Permisos configurados"
chmod -R 775 bootstrap/cache
chmod -R 775 storage
echo "[7/10] Configurando permisos..."
# 7. Configurar permisos

echo ""
echo "✓ Cachés limpiados"
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
echo "[6/10] Limpiando cachés..."
# 6. Limpiar cachés

echo ""
fi
    echo "✓ APP_KEY ya existe"
else
    echo "✓ APP_KEY generada"
    php artisan key:generate --force
    echo "   Generando APP_KEY..."
if ! grep -q "APP_KEY=base64:" .env; then
echo "[5/10] Verificando APP_KEY..."
# 5. Generar APP_KEY si no existe

echo ""
echo "✓ Archivo .env existe"
fi
    read -p "Presiona ENTER después de editar .env..."
    echo ""
    echo "   nano .env"
    echo "   ⚠️  IMPORTANTE: Debes editar .env con las credenciales del hosting"
    cp .env.example .env
    echo "   Creando desde .env.example..."
    echo "⚠️  ADVERTENCIA: No existe archivo .env"
if [ ! -f ".env" ]; then
echo "[4/10] Verificando archivo .env..."
# 4. Verificar que existe .env

echo ""
echo "✓ Assets compilados exitosamente"
fi
    exit 1
    echo "❌ ERROR: Falló la compilación de assets"
if [ $? -ne 0 ]; then
npm run build
echo "[3/10] Compilando assets para producción..."
# 3. Compilar assets con Vite

echo ""
echo "✓ Dependencias de Node instaladas"
fi
    exit 1
    echo "❌ ERROR: Falló la instalación de dependencias de Node"
if [ $? -ne 0 ]; then
npm install --production=false
echo "[2/10] Instalando dependencias de Node..."
# 2. Instalar dependencias de Node

echo ""
echo "✓ Dependencias de Composer instaladas"
fi
    exit 1
    echo "❌ ERROR: Falló la instalación de dependencias de Composer"
if [ $? -ne 0 ]; then
composer install --optimize-autoloader --no-dev --no-interaction
echo "[1/10] Instalando dependencias de Composer..."
# 1. Instalar dependencias de Composer (sin dependencias de desarrollo)

echo ""
echo "✓ Directorio correcto verificado"

fi
    exit 1
    echo "   Asegúrate de estar en el directorio raíz del proyecto"
    echo "❌ ERROR: No se encuentra el archivo artisan"
if [ ! -f "artisan" ]; then
# Verificar que estamos en el directorio correcto

echo ""
echo "========================================"
echo "  Gestor de Cuentas"
echo "  DEPLOYMENT EN HOSTING"
echo "========================================"

# ./deploy-hosting.sh
# chmod +x deploy-hosting.sh
# Ejecutar después de hacer git pull
# ========================================
# DEPLOY EN HOSTING - Gestor de Cuentas
# ========================================

