<?php

namespace App\Config;
use PDO;
use PDOException;

class Database
{

  private $con;
  private static $instance;

  private function __construct()
  {
    $this->connect();
  }

  public static function getIntance() {

    if( !self::$instance instanceof self ){
      self::$instance = new self();
    }

    return self::$instance;
    
  }

  private function connect(): void
  {

    try {

      $this->con = new PDO("mysql:host=" . $_ENV["SERVER"] . ";dbname=" . $_ENV["DBNAME"], $_ENV["USER"], $_ENV["PASSWORD"]);
      $setnames = $this->con->prepare("SET NAMES 'utf8'");
      $setnames->execute();
      // Configurar el modo de error para excepciones
      $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // Deshabilitar autocommit
      $this->con->setAttribute(PDO::ATTR_AUTOCOMMIT, false);

      //echo 'conexion campeon';

    } catch (PDOException $exc) {

      echo '<h1> Error de conexión: ' . $exc->getMessage() . '</h1>';
      echo '<h2> En la linea: ' . $exc->getLine() . '</h2>';
      echo '<pre>';
      var_dump($exc->getTrace());
      echo '</pre>';

    }

  }

  public function getConnection(): PDO
  {
    return $this->con;
  }

}