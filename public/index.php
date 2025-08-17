<?php

//var_dump("En el front: ",$_SERVER['REQUEST_URI']);
//die();
// public/index.php - Front Controller con Router

// Configuración de errores para desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Definir rutas base
define('BASE_PATH', dirname(__DIR__));
//echo "base_path: ".BASE_PATH."<br>";
define('APP_PATH', BASE_PATH . '/App');
//echo "app_path: ".APP_PATH."<br>";
define('PUBLIC_PATH', __DIR__);
//echo "public_path: ".PUBLIC_PATH."<br>";
define('RESOURCES_PATH', BASE_PATH . '/resources');
//echo "RESOURCES_path: ".RESOURCES_PATH."<br>";

// Autoload con Composer PSR-4
require_once BASE_PATH . '/vendor/autoload.php';

// Incluir funciones auxiliares si existen
if (file_exists(APP_PATH . '/Helpers/functions.php')) {
  require_once APP_PATH . '/Helpers/functions.php';
}

// Usar el Router para manejar la request
use App\Core\Router;

$router = new Router();

// Para debugging (remover en producción)
if (isset($_GET['debug'])) {
  echo '<pre>';
  print_r($router->getDebugInfo());
  echo '</pre>';
  exit;
}

// Ejecutar el routing
$router->dispatch();