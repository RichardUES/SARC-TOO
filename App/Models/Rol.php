<?php

namespace App\Models;
use DateTime;

class Rol
{
  public function __construct(
    private string   $codigo,
    private string   $nombre,
    private DateTime $fecha_registro,
    private DateTime $fum,
    private string   $estado
  ) { }

  public function __get($name)
  {
    return $this->$name;
  }

  public function __toString(): string
  {
    return $this->codigo . " - " . $this->nombre . " - " . $this->fecha_registro . " - " . $this->fum . " - " . $this->estado;
  }

  /*  =========== SETTER METHODS =========== */
  public function setCodigo(string $codigo): void
  {
    $this->codigo = $codigo;
  }

  public function setNombre(string $nombre): void
  {
    $this->nombre = $nombre;
  }

  public function setFum(DateTime $fum): void
  {
    $this->fum = $fum;
  }

  public function setFechaRegistro(DateTime $fecha_registro): void
  {
    $this->fecha_registro = $fecha_registro;
  }

    public function setEstado(string $estado): void
  {
    $this->estado = $estado;
  }

}