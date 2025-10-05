<?php

namespace App\Models;

use App\Models\enums\Status;
use App\Models\enums\Priority;
use App\Models\enums\OrigenTicket;
use DateTime;

class Ticket
{

  private int $codigo;
  private Cliente $cliente;
  private Agencia $agencia;
  private Area $area;
  private EstadoTicket $estadoTicket;
  private string $asunto;
  private string $descripcion;
  private DateTime $fecha_creacion;
  private DateTime $fecha_asignacion;
  private DateTime $fecha_cierre;
  private Priority $prioridad;
  private OrigenTicket $origen;
  private Status $status;

  public function __construct() {}

  public function __get($name) {
    return $this->$name;
  }

  public function setCodigo(int $codigo): void
  {
    $this->codigo = $codigo;
  }

  public function setCliente(Cliente $cliente): void
  {
    $this->cliente = $cliente;
  }

  public function setAgencia(Agencia $agencia): void
  {
    $this->agencia = $agencia;
  }

  public function setArea(Area $area): void
  {
    $this->area = $area;
  }

  public function setEstadoTicket(EstadoTicket $estadoTicket): void
  {
    $this->estadoTicket = $estadoTicket;
  }

  public function setAsunto(string $asunto): void
  {
    $this->asunto = $asunto;
  }

  public function setDescripcion(string $descripcion): void
  {
    $this->descripcion = $descripcion;
  }

  public function setFechaCreacion(DateTime $fecha_creacion): void
  {
    $this->fecha_creacion = $fecha_creacion;
  }

  public function setFechaAsignacion(DateTime $fecha_asignacion): void
  {
    $this->fecha_asignacion = $fecha_asignacion;
  }

  public function setFechaCierre(DateTime $fecha_cierre): void
  {
    $this->fecha_cierre = $fecha_cierre;
  }

  public function setPrioridad(Priority $prioridad): void
  {
    $this->prioridad = $prioridad;
  }

  public function setOrigen(OrigenTicket $origen): void
  {
    $this->origen = $origen;
  }

  public function setStatus(Status $status): void
  {
    $this->status = $status;
  }

}