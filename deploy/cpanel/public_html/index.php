<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Ruta base del proyecto
|--------------------------------------------------------------------------
|
| Ajusta esta ruta antes de mover el archivo a public_html.
| Ejemplo:
| /home/USUARIO/inventario_innovacion
|
*/

$appBasePath = '__APP_BASE_PATH__';

if (file_exists($maintenance = $appBasePath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $appBasePath.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once $appBasePath.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
