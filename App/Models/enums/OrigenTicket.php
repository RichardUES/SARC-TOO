<?php

namespace App\Models\enums;

enum OrigenTicket: string
{

  case WEB       = "WEB";
  case CALL      = "LLAMADA";
  case IN_PERSON = "PRESENCIAL";

}