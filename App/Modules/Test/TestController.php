<?php 

namespace App\Modules\Test;

use App\Core\Controller;
use App\Modules\Auth\Repositories\UsuarioRepository;

class TestController extends Controller
{


  private UsuarioRepository $userrepo;

  public function __construct() 
  {
    $this->userrepo = new UsuarioRepository();
  }


  public function testing()
  {
    // echo "pase por acá";
    // echo $this->userrepo->obtenerAgenteDisponible();
  }

}