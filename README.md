Despliegue en hosting (GestorCuentas)


Ejecuta los siguientes comandos paso a paso en la terminal de cPanel (o en una sesión SSH en el servidor). Ejecuta cada línea en orden y espera a que termine antes de continuar.

```bash
cd ~/apps/gestoracuentas
git pull
/opt/cpanel/ea-php82/root/usr/bin/php $(which composer) install --no-dev --optimize-autoloader
/opt/cpanel/ea-php82/root/usr/bin/php artisan config:clear
/opt/cpanel/ea-php82/root/usr/bin/php artisan cache:clear
/opt/cpanel/ea-php82/root/usr/bin/php artisan config:cache
/opt/cpanel/ea-php82/root/usr/bin/php artisan route:cache
/opt/cpanel/ea-php82/root/usr/bin/php artisan view:cache
```

Nota importante: en este hosting la carpeta del proyecto y la carpeta `public` están enlazadas mediante symlink; ambas rutas se resuelven a la misma ubicación. Ten cuidado al mover archivos o cambiar la raíz del proyecto.

Verificaciones rápidas (opcionales):
- Revisar logs: `tail -n 100 storage/logs/laravel.log`
- Comprobar permisos: `storage/` y `bootstrap/cache/` deben ser escribibles por el usuario del servidor web.
