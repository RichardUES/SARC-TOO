<?php

// ====== public/index.php - Front Controller con Router ======

require_once "../bootstrap.php";

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