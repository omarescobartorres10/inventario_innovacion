# Inventario Innovacion

Sistema de gestion de inventario desarrollado con Laravel, PHP y MySQL para controlar entradas y salidas de insumos.

## Modulos iniciales

- Catalogo de insumos con nombre, marca, modelo y unidad.
- Registro de movimientos de entrada y salida.
- Validacion para evitar salidas mayores al stock disponible.
- Resumen general de stock, entradas y salidas.
- Datos semilla con ejemplos:
  - Tinta Brother BTD60BK
  - Teclado Logitech MK120

## Configuracion base

El proyecto esta preparado para usar MySQL con estos valores en `.env`:

- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=inventario_innovacion`
- `DB_USERNAME=root`
- `DB_PASSWORD=`

## Pasos para levantarlo

1. Crear la base de datos `inventario_innovacion` en MySQL.
2. Verificar que `php` y `composer` esten instalados en el equipo.
3. Entrar al proyecto `inventario_innovacion`.
4. Ejecutar `composer install` si necesitas reconstruir dependencias.
5. Ejecutar `php artisan migrate --seed`.
6. Ejecutar `npm install && npm run dev` para compilar los assets.
7. Ejecutar `php artisan serve`.

## Despliegue en VPS / cPanel

Se agrego una carpeta lista para despliegue en cPanel:

- [deploy/cpanel/README.md](/Users/innovacion/Documents/New%20project/inventario_innovacion/deploy/cpanel/README.md)
- [deploy/cpanel/public_html/index.php](/Users/innovacion/Documents/New%20project/inventario_innovacion/deploy/cpanel/public_html/index.php)
- [deploy/cpanel/public_html/.htaccess](/Users/innovacion/Documents/New%20project/inventario_innovacion/deploy/cpanel/public_html/.htaccess)

Eso te cubre dos escenarios:

- dominio apuntando directamente a `public`
- dominio principal usando `public_html`

Para MochaHost con cPanel, revisa primero la guia dentro de `deploy/cpanel/README.md`.

## Estructura principal

- `app/Http/Controllers/InventoryController.php`
- `app/Models/Item.php`
- `app/Models/InventoryMovement.php`
- `database/migrations/2026_03_10_000003_create_items_table.php`
- `database/migrations/2026_03_10_000004_create_inventory_movements_table.php`
- `database/seeders/InventorySeeder.php`
- `resources/views/inventory/index.blade.php`
