# 📋 Checklist para Deploy - Gestor de Cuentas

## ✅ Problemas Corregidos

### 1. **Corrupción de Base de Datos XAMPP**
- ✅ Todas las operaciones DB ahora usan transacciones
- ✅ Try-catch en todos los controladores
- ✅ Rollback automático en caso de error
- ✅ Cambio de SESSION y CACHE a `file` (antes `database`)

### 2. **ExportarController - Manejo de Archivos**
- ✅ Eliminado `fclose()` en `php://output` 
- ✅ Validación de `fopen()` antes de usar
- ✅ Laravel maneja el stream correctamente

### 3. **Transacciones DB Seguras**
- ✅ ClienteController: store, update, destroy
- ✅ GestionController: store, update, destroy, setActiva
- ✅ UsuarioController: store
- ✅ TraspasarController: ya tenía transacciones

## 🔧 Pasos para Deploy

### 1. Configuración de .env
```bash
# Verificar que estos valores estén configurados:
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
DB_CONNECTION=mysql
```

### 2. Optimizar MySQL en XAMPP
1. Hacer backup de `C:\xampp\mysql\bin\my.ini`
2. Copiar configuración de `config/mysql-optimizado.ini`
3. Reiniciar MySQL desde el panel de XAMPP

### 3. Limpiar Cache de Laravel
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 4. Optimizar para Producción
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Verificar Permisos
```bash
# Storage debe tener permisos de escritura
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 6. Verificar Base de Datos
```bash
php artisan migrate:status
# Si hay migraciones pendientes:
php artisan migrate
```

### 7. Test Final
1. Crear una gestión
2. Agregar un cliente
3. Editar el cliente
4. Exportar a Excel/PDF
5. Traspasar datos
6. Eliminar cliente/gestión

## ⚠️ IMPORTANTE

### Antes de cada arranque de XAMPP:
1. Cerrar XAMPP correctamente (Stop All)
2. Esperar 5 segundos
3. Iniciar MySQL primero
4. Luego Apache

### Nunca hacer:
- ❌ Apagar la PC sin cerrar XAMPP
- ❌ Forzar cierre de procesos MySQL
- ❌ Editar archivos de base de datos directamente
- ❌ Usar CACHE_STORE=database (corrompe BD)
- ❌ Usar SESSION_DRIVER=database sin tabla sessions

### Backup regular:
```bash
# Hacer backup de la base de datos diariamente
mysqldump -u root bd_gestor_cuentas > backup_$(date +%Y%m%d).sql
```

## 🚀 Deploy a Producción

1. Subir código al servidor
2. Configurar .env con datos de producción
3. Instalar dependencias: `composer install --no-dev`
4. Generar key: `php artisan key:generate`
5. Migrar BD: `php artisan migrate --force`
6. Optimizar: `php artisan optimize`
7. Permisos: `chmod -R 775 storage bootstrap/cache`

## 📊 Monitoreo Post-Deploy

- Revisar logs: `storage/logs/laravel.log`
- Monitorear errores 500
- Verificar tiempos de respuesta
- Confirmar que las exportaciones funcionan
- Test de traspasos entre gestiones

---
**Última actualización:** Enero 9, 2026
**Problemas críticos resueltos:** 5/5 ✅
