# 🏦 Gestor de Cuentas - Sistema de Gestión

Sistema completo de gestión de cuentas desarrollado en Laravel 11 con Vite, Tailwind CSS 4 y MySQL.

## 🚀 Inicio Rápido

### 1️⃣ Primera vez
```bash
# Verificar requisitos
verificar-proyecto.bat

# Setup automático
deploy-local.bat

# Iniciar desarrollo
iniciar-desarrollo.bat
```

### 2️⃣ Desarrollo diario
```bash
# Con hot reload (recomendado)
iniciar-desarrollo.bat

# O simple
iniciar-simple.bat
```

### 3️⃣ Acceder
🌐 http://localhost:8000

## 📋 Requisitos

- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js 18+
- NPM

## 🔧 Configuración

### Base de Datos
```sql
CREATE DATABASE bd_gestor_cuentas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Archivo .env
```env
DB_DATABASE=bd_gestor_cuentas
DB_USERNAME=root
DB_PASSWORD=
```

### Migraciones
```bash
php artisan migrate
php artisan db:seed  # Opcional
```

## 📝 Scripts Disponibles

| Script | Descripción |
|--------|-------------|
| `verificar-proyecto.bat` | ✅ Verificar instalación |
| `deploy-local.bat` | 🔨 Setup desarrollo |
| `deploy-production.bat` | 🚀 Preparar producción |
| `iniciar-desarrollo.bat` | 🔥 Dev + HMR |
| `iniciar-simple.bat` | ⚡ Solo servidor |
| `backup-db.bat` | 💾 Backup BD |
| `restore-db.bat` | 📥 Restaurar BD |

## 🔄 Modos de Operación

| Modo | Comando | Tailwind CSS | Hot Reload | Uso |
|------|---------|--------------|------------|-----|
| **Desarrollo** | `npm run dev` | ✅ | ✅ | Desarrollo diario |
| **Producción** | `npm run build` | ✅ | ❌ | Deploy |
| **Simple** | Solo PHP | ❌ | ❌ | Testing rápido |

## 🐛 Solución de Problemas

### Error: "Vite manifest not found"
**Solución:** Este error ya está resuelto. Las vistas verifican si Vite está disponible antes de cargarlo.

### Los cambios en CSS no se reflejan
```bash
php artisan view:clear    # Limpia cache de vistas
# Refresca el navegador con Ctrl+F5
```

### Error de base de datos
```bash
# Verifica tu archivo .env
# Asegúrate que MySQL esté corriendo
php artisan migrate:fresh --seed
```

## 📚 Documentación

- **[INICIO_RAPIDO.md](INICIO_RAPIDO.md)** - Guía de inicio rápido (3 pasos)
- **[GUIA_DEPLOYMENT.md](GUIA_DEPLOYMENT.md)** - Guía completa de deployment
- **[SOLUCION_COMPLETA_VITE.md](SOLUCION_COMPLETA_VITE.md)** - Solución al error de Vite
- **[DEPLOY_CHECKLIST.md](DEPLOY_CHECKLIST.md)** - Checklist de deployment

## 🛠️ Tecnologías

- **Backend:** Laravel 11.x
- **Frontend:** Vite 7.x + Tailwind CSS 4.x
- **Base de Datos:** MySQL 8.0+
- **PHP:** 8.2+
- **Node.js:** 18+

## 📞 Soporte

Si encuentras algún problema:
1. Ejecuta `verificar-proyecto.bat` para diagnóstico
2. Revisa la documentación en los archivos `.md`
3. Limpia los cachés: `php artisan config:clear`
4. Verifica `storage/logs/laravel.log`

## 🎯 Estado del Proyecto

✅ **LISTO PARA USAR**
- Assets compilados con Vite
- Base de datos configurada
- Scripts de deployment listos
- Documentación completa

## 📄 Licencia

Este proyecto es de código propietario.

---

**Versión:** 1.0.0  
**Última actualización:** 2026-01-19  
**Stack:** Laravel 11 | PHP 8.2+ | Vite 7.0 | Tailwind CSS 4.0
