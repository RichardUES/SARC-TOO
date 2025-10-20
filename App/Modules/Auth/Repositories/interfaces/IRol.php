<?php

namespace App\Modules\Auth\Repositories\interfaces;

use App\Models\Rol;

interface IRol
{
  public function save(Rol $data): bool;

  public function delete(int $id): bool;

  public function findAll(): array;

  public function findById(int $id): Rol | null;

}