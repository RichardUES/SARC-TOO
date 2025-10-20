<?php

namespace App\Modules\Dashboard\administracion;

use App\Models\Agencia;
use App\Modules\Dashboard\administracion\repositories\AgenciaRepository;

class AgenciaService {

  private AgenciaRepository $agenciaRepository;

  public function __construct() {

    $this->agenciaRepository = new AgenciaRepository();

  }

  public function listAgencias(): array {

    return $this->agenciaRepository->findAll();

  }

  public function createAgency(Agencia $agency): bool {

    return $this->agenciaRepository->save($agency);

  }

}