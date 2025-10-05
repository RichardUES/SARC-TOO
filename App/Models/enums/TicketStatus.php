<?php

namespace App\Models\enums;

enum TicketStatus: int
{

  case RECEIVED   = 1; // "RECIBIDO";
  case ASSIGNED   = 2; // "ASIGNADO";
  case IN_PROCESS = 3; // "EN PROCESO";
  case PENDING    = 4; // "PENDIENTE";
  case SCALING    = 5; // "ESCALADO"; // Estado en que el agente es libre, para signar otro TKT
  case COMPLETED  = 6; // "COMPLETADO"; // Estado en que el agente es libre, para signar otro TKT

}
