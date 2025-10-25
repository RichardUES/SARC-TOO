<?php

// public/index.php - Front Controller con Router

// Configuración de errores para desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Definir rutas base
define('BASE_PATH', __DIR__);
//echo "base_path: ".BASE_PATH."<br>";
define('APP_PATH', BASE_PATH . '/App');
//echo "app_path: ".APP_PATH."<br>";
define('PUBLIC_PATH', __DIR__);
//echo "public_path: ".PUBLIC_PATH."<br>";
define('RESOURCES_PATH', BASE_PATH . '/resources');
define("VIEWS_PATH", RESOURCES_PATH . "/views");
//echo "RESOURCES_path: ".RESOURCES_PATH."<br>";
define("VIRTUAL_PATH", "http://luzelfaro.com");

// Autoload con Composer PSR-4
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Incluir funciones auxiliares si existen
if (file_exists(APP_PATH . '/Helpers/functions.php')) {
  require_once APP_PATH . '/Helpers/functions.php';
}