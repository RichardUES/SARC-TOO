<?php

namespace App\Models;

use DateTime;

class ComentarioTicket
{

  private int $codigo;
  private Ticket $ticket;
  private Usuario $usuario;
  private DateTime $fecha;
  private string $mensaje;

  public function __construct(){}

  public function __get($name){
    return $this->$name;
  }

  public function setCodigo(int $codigo): void
  {
    $this->codigo = $codigo;
  }

  public function setTicket(Ticket $ticket): void
  {
    $this->ticket = $ticket;
  }

  public function setUsuario(Usuario $usuario): void
  {
    $this->usuario = $usuario;
  }

  public function setFecha(DateTime $fecha): void
  {
    $this->fecha = $fecha;
  }

  public function setMensaje(string $mensaje): void
  {
    $this->mensaje = $mensaje;
  }

}