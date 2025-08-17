<?php

namespace App\Core;

use Exception;

class Router
{
  private $uri;
  private $method;
  private $module;
  private $controller;
  private $action;
  private $params = [];

  public function __construct()
  {
    $this->parseRequest();
  }

  /**
   * Analizar la request actual
   */
  private function parseRequest()
  {
    // Obtener URI y método
    $this->uri = $this->getUri();
    $this->method = $_SERVER['REQUEST_METHOD'];

    // Parsear la URI
    $this->parseUri();
  }

  /**
   * Obtener y limpiar la URI
   */
  private function getUri()
  {
    $uri = $_SERVER['REQUEST_URI'] ?? '/';

    // Remover query string
    $uri = parse_url($uri, PHP_URL_PATH);

    // Limpiar y normalizar
    $uri = trim($uri, '/');

    return $uri;
  }

  /**
   * Parsear la URI en módulo/controlador/acción/parámetros
   */
  private function parseUri()
  {
    // Si está vacía, usar defaults
    if (empty($this->uri)) {
      $this->module = 'Home';
      $this->controller = 'HomeController';
      $this->action = 'index';
      return;
    }

    // Separar en partes
    $segments = explode('/', $this->uri);

    // Asignar módulo (requerido)
    $this->module = $this->sanitize($segments[0] ?? 'home');

    // Asignar acción (opcional, default: index)
    $this->action = $this->sanitize($segments[1] ?? 'index');

    // El resto son parámetros
    $this->params = array_slice($segments, 2);

    // Construir nombre del controlador
    $this->controller = ucfirst($this->module) . 'Controller';
  }

  /**
   * Sanitizar nombres de módulos/acciones
   */
  private function sanitize($string)
  {
    return preg_replace('/[^a-zA-Z0-9_-]/', '', $string);
  }

  /**
   * Ejecutar el routing
   */
  public function dispatch()
  {
    try {
      // Construir ruta del controlador
      $controllerPath = $this->getControllerPath();

      // Verificar si existe el archivo
      if (!file_exists($controllerPath)) {
        throw new Exception("Módulo '{$this->module}' no encontrado");
      }

      // Incluir el controlador
      require_once $controllerPath;

      // Construir nombre completo con namespace
      $controllerClass = "App\\Modules\\{$this->getModuleName()}\\Controllers\\{$this->controller}";

      // Verificar si existe la clase
      if (!class_exists($controllerClass)) {
        throw new Exception("Controlador '{$this->controller}' no encontrado");
      }

      // Instanciar controlador
      $controllerInstance = new $controllerClass();

      // Verificar si existe la acción
      if (!method_exists($controllerInstance, $this->action)) {
        throw new Exception("Acción '{$this->action}' no encontrada en {$this->controller}");
      }

      // Ejecutar la acción con parámetros
      call_user_func_array([$controllerInstance, $this->action], $this->params);

    } catch (Exception $e) {
      $this->handleError($e);
    }
  }

  /**
   * Obtener ruta del controlador
   */
  private function getControllerPath()
  {
    $moduleName = $this->getModuleName();
    return APP_PATH . "/Modules/{$moduleName}/Controllers/{$this->controller}.php";
  }

  /**
   * Obtener nombre del módulo con formato correcto
   */
  private function getModuleName()
  {
    return ucfirst(strtolower($this->module));
  }

  /**
   * Manejar errores de routing
   */
  private function handleError(Exception $e)
  {
    // Log del error (opcional)
    error_log("Router Error: " . $e->getMessage());

    // Establecer código de respuesta
    http_response_code(404);

    // Intentar cargar vista de error personalizada
    $this->loadErrorView($e);
  }

  /**
   * Cargar vista de error
   */
  private function loadErrorView(Exception $e)
  {
    $errorView = RESOURCES_PATH . '/views/errors/404.php';

    if (file_exists($errorView)) {
      $error_message = $e->getMessage();
      $error_code = 404;
      require $errorView;
    } else {
      // Fallback simple
      $this->showSimpleError($e);
    }
  }

  /**
   * Mostrar error simple si no hay vista personalizada
   */
  private function showSimpleError(Exception $e)
  {
    echo "<!DOCTYPE html>";
    echo "<html><head><title>Error 404</title></head><body>";
    echo "<h1>Página No Encontrada</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><a href='/'>Volver al inicio</a></p>";
    echo "</body></html>";
  }

  /**
   * Obtener información de debugging
   */
  public function getDebugInfo()
  {
    return [
      'uri' => $this->uri,
      'method' => $this->method,
      'module' => $this->module,
      'controller' => $this->controller,
      'action' => $this->action,
      'params' => $this->params,
      'controller_path' => $this->getControllerPath()
    ];
  }

  /**
   * Verificar si es una request AJAX
   */
  public function isAjax()
  {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
  }

  /**
   * Obtener método HTTP
   */
  public function getMethod()
  {
    return $this->method;
  }

  /**
   * Verificar método HTTP
   */
  public function isPost()
  {
    return $this->method === 'POST';
  }

  public function isGet()
  {
    return $this->method === 'GET';
  }

  public function isPut()
  {
    return $this->method === 'PUT';
  }

  public function isDelete()
  {
    return $this->method === 'DELETE';
  }

  /**
   * Obtener parámetros de la URL
   */
  public function getParams()
  {
    return $this->params;
  }

  /**
   * Obtener un parámetro específico por índice
   */
  public function getParam($index, $default = null)
  {
    return $this->params[$index] ?? $default;
  }
}