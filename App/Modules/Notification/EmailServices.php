<?php

namespace App\Modules\Notification;

use App\Models\Usuario;

class EmailServices
{
  public function enviarConfirmacion(Usuario $user, string $token): void
  {

  }

  public function enviarRecuperacion(Usuario $user, string $token): void
  {

  }

  public function enviarNotificacionLoginBloqueado(Usuario $user): void
  {

  }
}