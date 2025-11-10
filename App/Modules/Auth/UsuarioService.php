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

  /**
   * Obtiene lista completa de agentes activos
   * @return array Lista de agentes para reportes y administración
   */
  public function getAgentesList(): array {
    return $this->userRepository->obtenerListaAgentes();
  }

  /**
   * Obtiene un agente disponible para asignación de tickets
   * @return array|null Agente disponible o null si no hay ninguno
   */
  public function obtenerAgenteDisponible(): ?array {
    return $this->userRepository->obtenerAgenteDisponible();
  }

}
