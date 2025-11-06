<?php

namespace App\Models;

use App\Models\enums\Status;
use App\Models\enums\Priority;
use App\Models\enums\OrigenTicket;
use DateTime;

class Ticket
{

  private int $codigo;
  private int $clienteID;
  private int $agenciaID;
  private int $areaID;
  private int $estadoTicket;
  private string $asunto;
  private string $descripcion;
  private DateTime $fecha_creacion;
  private DateTime $fecha_asignacion;
  private DateTime $fecha_cierre;
  private string $prioridad;
  private string $origen;
  private string $status;

  public function __construct() {}

  public function __get($name) {
    return $this->$name;
  }

  public function setCodigo(int $codigo): void
  {
    $this->codigo = $codigo;
  }

  public function setClienteID(int $clienteID): void
  {
    $this->clienteID = $clienteID;
  }

  public function setAgenciaID(int $agenciaID): void
  {
    $this->agenciaID = $agenciaID;
  }

  public function setAreaID(int $areaID): void
  {
    $this->areaID = $areaID;
  }

  public function setEstadoTicket(int $estadoTicket): void
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

  public function setPrioridad(string $prioridad): void
  {
    $this->prioridad = $prioridad;
  }

  public function setOrigen(string $origen): void
  {
    $this->origen = $origen;
  }

  public function setStatus(string $status): void
  {
    $this->status = $status;
  }

}