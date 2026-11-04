# Despliegue en cPanel / MochaHost

Esta carpeta deja listo el proyecto para moverlo a un VPS o hosting con cPanel.

## Opcion recomendada

Si en cPanel puedes apuntar el dominio o subdominio directamente a la carpeta `public` del proyecto, usa esta estructura:

- Aplicacion: `/home/USUARIO/inventario_innovacion`
- Document root del dominio: `/home/USUARIO/inventario_innovacion/public`

En ese escenario no necesitas copiar los archivos de `deploy/cpanel/public_html`.

## Opcion compatible con public_html

Si el dominio principal solo usa `public_html`, trabaja asi:

1. Sube el proyecto completo fuera de `public_html`.
   Ejemplo:
   - `/home/USUARIO/inventario_innovacion`

2. Copia estos archivos a `public_html`:
   - `deploy/cpanel/public_html/index.php`
   - `deploy/cpanel/public_html/.htaccess`

3. Edita `public_html/index.php` y reemplaza:
   - `__APP_BASE_PATH__`
   por la ruta real del proyecto.

   Ejemplo:
   ```php
   $appBasePath = '/home/USUARIO/inventario_innovacion';
   ```

4. Copia el contenido de la carpeta `public` del proyecto a `public_html`, excepto `index.php` y `.htaccess` si ya usaste los de esta carpeta.

## Pasos de servidor

1. Crear base de datos MySQL y usuario desde cPanel.
2. Subir el proyecto.
3. Crear `.env` a partir de `.env.example`.
4. Configurar:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://tu-dominio`
   - `DB_*`
   - `SESSION_SECURE_COOKIE=true` si usas HTTPS
5. Instalar dependencias:
   - `composer install --no-dev --optimize-autoloader`
6. Generar clave si hace falta:
   - `php artisan key:generate`
7. Migrar base:
   - `php artisan migrate --force`
8. Limpiar y optimizar:
   - `php artisan optimize:clear`
   - `php artisan config:cache`
   - `php artisan route:cache`
   - `php artisan view:cache`

## Permisos sugeridos

- `storage/`
- `bootstrap/cache/`

Deben quedar escribibles por PHP.

## Admin inicial opcional

Si quieres crear un administrador al correr el seeder, define en `.env`:

- `SEED_ADMIN_EMAIL`
- `SEED_ADMIN_PASSWORD`

Opcionales:

- `SEED_ADMIN_NAME`
- `SEED_ADMIN_PHONE`
- `SEED_ADMIN_POSITION`

Luego ejecuta:

```bash
php artisan db:seed --force
```

## Nota

Antes de publicar, conviene actualizar dependencias y resolver la vulnerabilidad reportada por `composer audit` en `league/commonmark`.
