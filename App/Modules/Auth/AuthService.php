<?php

namespace App\Modules\Auth;

use App\Models\Usuario;
use App\Modules\Auth\Repositories\interfaces\IUsuario;
use App\Modules\Auth\Repositories\RolRepository;
use App\Modules\Auth\Repositories\UsuarioRepository;

class AuthService
{

  private UsuarioRepository $userRepository;
  private RolRepository $rolRepository;

  public function __construct() {
    $this->userRepository = new UsuarioRepository();
    $this->rolRepository = new RolRepository();
  }

  public function logout(Usuario $usuario): void
  {

  }

  public function signin($username_or_email, $password): mixed {

    return $this->userRepository->signin($username_or_email, $password);

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

  public function obtenerRoles(): array {
    return $this->rolRepository->findAll();
  }

}