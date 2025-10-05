<?php

namespace App\Models;

use DateTime;

class HistorialTicket
{

  private int $codigo;
  private Ticket $ticket;
  private Usuario $usuario;
  private EstadoTicket $estado_anterior;
  private EstadoTicket $estado_actual;
  private DateTime $fecha;
  private string $comentario;
  private AsignacionTicket $asignacionTicket;

  public function __construnct() {}

  public function __get($name) {
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

  public function setEstadoAnterior(EstadoTicket $estado_anterior): void
  {
    $this->estado_anterior = $estado_anterior;
  }

  public function setEstadoActual(EstadoTicket $estado_actual): void
  {
    $this->estado_actual = $estado_actual;
  }

  public function setFecha(DateTime $fecha): void
  {
    $this->fecha = $fecha;
  }

  public function setComentario(string $comentario): void
  {
    $this->comentario = $comentario;
  }

  public function setAsignacionTicket(AsignacionTicket $asignacionTicket): void
  {
    $this->asignacionTicket = $asignacionTicket;
  }

}