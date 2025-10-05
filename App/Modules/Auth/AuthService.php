<?php

namespace App\Modules\Auth;

use App\Models\Usuario;
use App\Modules\Auth\Repositories\interfaces\IUsuarioDAO;
use App\Modules\Auth\Repositories\UsuarioRepository;

class AuthService
{

  private IUsuarioDAO $usuario;

  public function __construct() {
    $this->usuario = new UsuarioRepository();
  }

  public function login(string $userOrEmail, string $clave): Usuario
  {
    return new Usuario();

  }

  public function logout(Usuario $usuario): void
  {

  }

  public function registrarUsuario(): Usuario | null
  {
    var_dump($_POST);
    die();
    return null;
  }

  public function activarCuenta(string $token): void
  {

  }

  public function solicitarRecuperacion(string $email): void
  {

  }

  public function restablecerConToken(string $token, string $nuevaClave): void
  {

  }

}