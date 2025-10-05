<?php

namespace App\Models;

use App\Models\enums\TipoAsignacion;
use DateTime;

class AsignacionTicket
{

  private int $codigo;
  private Ticket $ticket;
  private Usuario $usuario;
  private DateTime $fecha;
  private TipoAsignacion $asignacion;
  private string $observacion;
  private string $is_finalizada; // S o N

  public function __contsruct() {}

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

  public function setFecha(DateTime $fecha): void
  {
    $this->fecha = $fecha;
  }

  public function setAsignacion(TipoAsignacion $asignacion): void
  {
    $this->asignacion = $asignacion;
  }

  public function setObservacion(string $observacion): void
  {
    $this->observacion = $observacion;
  }

  public function setIsFinalizada(string $is_finalizada): void
  {
    $this->is_finalizada = $is_finalizada;
  }

}