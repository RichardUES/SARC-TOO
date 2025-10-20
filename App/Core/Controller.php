<?php

namespace App\Core;

use Exception;

class Controller {

  protected function view($view, $data = []) {
    // Extraer variables para la vista
    extract($data);

    // Construir ruta de la vista
    $viewPath = RESOURCES_PATH . '/views/' . $view . '.php';

    if (file_exists($viewPath)) {
      require $viewPath;
    } else {
      throw new Exception("Vista '{$view}' no encontrada");
    }
  }

  protected function redirect($url) {
    header("Location: {$url}");
    exit;
  }

  protected function json($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
  }

  protected function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
  }

  protected function isGet() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
  }
}