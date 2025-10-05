<?php

namespace App\Models;

use App\Models\enums\Status;
use DateTime;

class Usuario
{

  private string $codigo;
  private Rol $rol;
  private Agencia $agencia;
  private string $username;
  private string $email;
  private string $clave;
  private DateTime $fecha_registro;
  private DateTime $fum;
  private Status $estado;

  public function __construct()
  {
  }

  public function __get($name)
  {
    return $this->$name;
  }

  /* ================ SETTERS METHODS ================ */

  public function setCodigo(string $codigo): void
  {
    $this->codigo = $codigo;
  }

  public function setRol(Rol $rol): void
  {
    $this->rol = $rol;
  }

  public function setAgencia(Agencia $agencia): void
  {
    $this->agencia = $agencia;
  }

  public function setUsername(string $username): void
  {
    $this->username = $username;
  }

  public function setEmail(string $email): void
  {
    $this->email = $email;
  }

  public function setClave(string $clave): void
  {
    $this->clave = $clave;
  }

  public function setFechaRegistro(DateTime $fecha_registro): void
  {
    $this->fecha_registro = $fecha_registro;
  }

  public function setFum(DateTime $fum): void
  {
    $this->fum = $fum;
  }

  public function setEstado(Status $estado): void
  {
    $this->estado = $estado;
  }
}