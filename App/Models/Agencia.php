<?php

namespace App\Models;

use App\Models\enums\Status;

class Agencia
{

  private int $codigo;
  private string $nombre;
  private string $direccion;
  private string $telefono;
  private Status|string $estado;

  public function __construct(){

  }

  public function __get($name){
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

  public function setDireccion(string $direccion): void
  {
    $this->direccion = $direccion;
  }

  public function setTelefono(string $telefono): void
  {
    $this->telefono = $telefono;
  }

  public function setEstado(Status|string $estado): void
  {
    $this->estado = $estado;
  }


}