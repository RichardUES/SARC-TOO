<?php

namespace App\Modules\Auth\Repositories\interfaces;

use App\Models\Usuario;

interface IUsuarioDAO
{

  public function save(Usuario $data): bool;

  public function delete(int $id): bool;

  public function findAll(): array;

  public function findById(int $id): Usuario | null;

}