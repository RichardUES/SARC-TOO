<?php

namespace App\Models;

use App\Models\enums\Status;
use DateTime;
use App\Models\Usuario;

class Cliente extends Usuario
{

  private int $codigo;
  private int $usuarioID;
  private DateTime $fecha_nac;
  private string $primer_nombre;
  private string $segundo_nombre;
  private string $primer_apellido;
  private string $segundo_apellido;
  private string $telefono;
  private string $dui;
  private DateTime $fecha_registro;
  private string $estado;

public function __construct() { }

  public function getFullName(): string {
    return  $this->primer_nombre . " " . $this->segundo_nombre . " " . $this->primer_apellido . " " . $this->segundo_apellido;
  }

  public function getShortFullName(): string {
    return  $this->primer_nombre . " " . $this->primer_apellido;
  }


  public function __get($name){
    return $this->$name;
  }

  /* ====================== SETTER METHODS ====================== */

  public function setCodigo(int $codigo): void
  {
    $this->codigo = $codigo;
  }

  public function setUsuarioID(int $usuarioID): void
  {
    $this->usuarioID = $usuarioID;
  }

  public function setFechaNac(DateTime $fecha_nac): void
  {
    $this->fecha_nac = $fecha_nac;
  }

  public function setPrimerNombre(string $primer_nombre): void
  {
    $this->primer_nombre = $primer_nombre;
  }

  public function setSegundoNombre(string $segundo_nombre): void
  {
    $this->segundo_nombre = $segundo_nombre;
  }

  public function setPrimerApellido(string $primer_apellido): void
  {
    $this->primer_apellido = $primer_apellido;
  }

  public function setSegundoApellido(string $segundo_apellido): void
  {
    $this->segundo_apellido = $segundo_apellido;
  }

  public function setTelefono(string $telefono): void
  {
    $this->telefono = $telefono;
  }

  public function setDui(string $dui): void
  {
    $this->dui = $dui;
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