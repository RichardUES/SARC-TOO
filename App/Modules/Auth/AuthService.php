<?php

namespace App\Modules\Auth;

use App\Models\Usuario;
use App\Modules\Auth\Repositories\interfaces\IUsuario;
use App\Modules\Auth\Repositories\UsuarioRepository;

class AuthService
{

  private IUsuario $usuarioRepository;

  public function __construct() {
    $this->usuarioRepository = new UsuarioRepository();
  }

  public function login(string $userOrEmail, string $clave): Usuario
  {
    return new Usuario();

  }

  public function logout(Usuario $usuario): void
  {

  }

  /**
   * Metodo de servicio para registrar un nuevo usuario
   * @return bool
   */
  public function userRegister(Usuario $user): bool
  {
    return $this->usuarioRepository->save($user);
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