<?php

namespace App\Modules\Auth;

use App\Core\Controller;
use App\Modules\Auth\Repositories\UsuarioRepository;

class AuthController extends Controller
{
  private UsuarioRepository $authRepository;

  public function __construct(){
    $this->authRepository = new UsuarioRepository();
  }

  public function index(){

    $this->view("auth/login");

  }

  public function login(): void{
    echo "Te estas logueando";
  }

  public function encriptador(): void{

    $data = [];

    try{

      if ( $this->isPost() ) {
        $pass_user = $_POST["password"];
        $pass_crypt = password_hash($pass_user, PASSWORD_BCRYPT, ['cost' => 6]);
        $data = ["pass_encryp" => $pass_crypt];
      }

    } catch(\Exception $e){
      echo $e->getMessage();
    }

    $this->view("auth/encriptar", $data);

  }

}