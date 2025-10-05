<?php

namespace App\Models;

use App\Models\enums\Status;

class Area
{

  private int $codigo;
  private string $nombre;
  private string $descripcion;
  private Status $estado;

  public function __construct() { }

  public function __get($name) {
    return $this->$name;
  }

  public function setCodigo(int $codigo): void
  {
    $this->codigo = $codigo;
  }

  public function setNombre(string $nombre): void
  {
    $this->nombre = $nombre;
  }

  public function setDescripcion(string $descripcion): void
  {
    $this->descripcion = $descripcion;
  }

  public function setEstado(Status $estado): void
  {
    $this->estado = $estado;
  }

}