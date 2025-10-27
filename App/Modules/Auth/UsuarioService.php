<?php

namespace App\Modules\Auth;

use App\Models\Usuario;
use App\Modules\Auth\Repositories\interfaces\IUsuario;
use App\Modules\Auth\Repositories\UsuarioRepository;

class UsuarioService implements IUsuario
{

  private UsuarioRepository $userRepository;

  public function __construct()
  {
    $this->userRepository = new UsuarioRepository();
  }

  public function save(Usuario $user): ?Usuario
  {
    return $this->userRepository->save($user);
  }

  public function delete(int $id): bool{
    return false;
  }

  public function findAll(): array {
    return $this->userRepository->findAll();
  }

  public function findById(int $id): Usuario | null{
    return null;
  }

}
