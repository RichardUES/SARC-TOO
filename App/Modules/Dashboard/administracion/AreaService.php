<?php

namespace App\Modules\Dashboard\administracion;

use App\Core\GenericCrud;
use App\Models\Area;
use App\Modules\Dashboard\administracion\repositories\AreaRepository;

class AreaService implements GenericCrud {

  private GenericCrud $adminRepository;

  public function __construct() {

    $this->adminRepository = new AreaRepository();

  }

  public function findAll(): array {

    return $this->adminRepository->findAll();

  }

  public function save(mixed $area): bool {

    return $this->adminRepository->save($area);

  }

  public function findById(int $id)
  {
    
  }

  public function delete(int $id): bool
  {
    return false;
  }

}