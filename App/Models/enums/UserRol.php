<?php

namespace App\Models\enums;

enum UserRol: int
{
  case ADMIN        = 1; // "ADMINISTRADOR";

  case SUPERVISOR   = 2; // "SUPERVISOR";

  case AGENT        = 3; // "AGENTE";

  case CLIENT       = 4; // "CLIENTE";
}
