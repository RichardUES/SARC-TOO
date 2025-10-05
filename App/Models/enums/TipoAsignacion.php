<?php

namespace App\Models\enums;

enum TipoAsignacion: string
{

  case AUTOMATIC    = 'AUTOMATICA';
  case MANUAL       = 'MANUAL';
  case REASSIGNMENT = 'REASIGNACION';

}
