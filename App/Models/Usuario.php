<?php

namespace App\Models;

use App\Models\enums\Status;
use App\Models\enums\RolType;
use DateTime;

class Usuario
{

  private int $codigo;
  private int $rolID;
  private int $agenciaID;
  private string $username;
  private string $email;
  private string $clave;
  private DateTime $fecha_registro;
  private DateTime $fum;
  private string $estado;

  public function __construct()
  {
  }

  public function __get($name)
  {
    return $this->$name;
  }

  /* ================ SETTERS METHODS ================ */

  public function setCodigo(int $codigo): void
  {
    $this->codigo = $codigo;
  }

  public function setRolID(int $rolID): void
  {
    $this->rolID = $rolID;
  }

  public function setAgenciaID(int $agenciaID): void
  {
    $this->agenciaID = $agenciaID;
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

  public function setEstado(string $estado): void
  {
    $this->estado = $estado;
  }
}