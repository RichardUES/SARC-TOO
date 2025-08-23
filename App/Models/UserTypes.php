<?php

namespace App\Models;

enum UserTypes: string
{
  case ADMIN = "ADMINISTRADOR";

  case SUPERVISOR = "SUPERVISOR";

  case AGENTE = "AGENTE";

  case CLIENTE = "CLIENTE";
}
