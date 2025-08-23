<?php

namespace App\Models;

use DateTime;

class Cliente
{

public function __construct(
  private string $codigo,
  private Usuario $usuario,
  private DateTime $fecha_nac,
  private string $primer_nombre,
  private string $segundo_nombre,
  private string $primer_apellido,
  private string $segundo_apellido,
  private string $telefono,
  private string $dui,
  private string $estado
  ) { }

  public function __get($name){
    return $this->$name;
  }

  /* ====================== SETTER METHODS ====================== */

  public function setCodigo(string $codigo): void
  {
    $this->codigo = $codigo;
  }

  public function setUsuario(Usuario $usuario): void
  {
    $this->usuario = $usuario;
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

  public function setEstado(string $estado): void
  {
    $this->estado = $estado;
  }

}